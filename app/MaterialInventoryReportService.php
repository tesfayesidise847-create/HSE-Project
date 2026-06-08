<?php

namespace App;

use App\Models\Material;
use App\Models\MaterialEmployeeAssignment;
use App\Models\MaterialProjectAssignment;
use App\Models\Project;
use Illuminate\Support\Collection;

class MaterialInventoryReportService
{
    /**
     * @return array{summary: array<string, int>, materials: list<array<string, mixed>>}
     */
    public function forHeadOffice(): array
    {
        $projectIds = Project::query()->pluck('id');
        $materials = $this->buildMaterialRows($projectIds, isHeadOffice: true);

        return [
            'summary' => $this->buildHeadOfficeSummary($materials),
            'materials' => $materials,
        ];
    }

    /**
     * @param  Collection<int, Project>  $projects
     * @return array{summary: array<string, int>, materials: list<array<string, mixed>>}
     */
    public function forSiteOfficer(Collection $projects): array
    {
        $projectIds = $projects->pluck('id');
        $materials = $this->buildMaterialRows($projectIds, isHeadOffice: false);

        return [
            'summary' => $this->buildSiteOfficerSummary($materials),
            'materials' => $materials,
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $materials
     * @return array<string, int>
     */
    private function buildHeadOfficeSummary(array $materials): array
    {
        return [
            'total_materials' => count($materials),
            'total_stocked_quantity' => (int) collect($materials)->sum('total_stocked_quantity'),
            'total_head_office_available' => (int) collect($materials)->sum('head_office_available'),
            'total_assigned_to_projects' => (int) collect($materials)->sum('assigned_to_projects'),
            'total_assigned_to_employees' => (int) collect($materials)->sum('assigned_to_employees'),
            'total_site_remaining' => (int) collect($materials)->sum('site_remaining'),
            'total_in_system' => (int) collect($materials)->sum('total_in_system'),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $materials
     * @return array<string, int>
     */
    private function buildSiteOfficerSummary(array $materials): array
    {
        return [
            'total_materials' => count($materials),
            'total_received' => (int) collect($materials)->sum('assigned_to_projects'),
            'total_distributed' => (int) collect($materials)->sum('assigned_to_employees'),
            'total_available' => (int) collect($materials)->sum('site_remaining'),
        ];
    }

    /**
     * @param  Collection<int, int>  $projectIds
     * @return list<array<string, mixed>>
     */
    private function buildMaterialRows(Collection $projectIds, bool $isHeadOffice): array
    {
        $assignedToProjectsByMaterial = $this->assignmentsByMaterial(
            MaterialProjectAssignment::query(),
            $projectIds,
        );

        $assignedToEmployeesByMaterial = $this->assignmentsByMaterial(
            MaterialEmployeeAssignment::query(),
            $projectIds,
        );

        if ($isHeadOffice) {
            $materials = Material::query()->orderBy('material_name')->get();
        } else {
            $materialIds = $assignedToProjectsByMaterial->keys()
                ->merge($assignedToEmployeesByMaterial->keys())
                ->unique();

            if ($materialIds->isEmpty()) {
                return [];
            }

            $materials = Material::query()
                ->whereIn('id', $materialIds)
                ->orderBy('material_name')
                ->get();
        }

        return $materials->map(function (Material $material) use (
            $assignedToProjectsByMaterial,
            $assignedToEmployeesByMaterial,
            $isHeadOffice
        ): array {
            $assignedToProjects = (int) ($assignedToProjectsByMaterial[$material->id] ?? 0);
            $assignedToEmployees = (int) ($assignedToEmployeesByMaterial[$material->id] ?? 0);
            $headOfficeAvailable = $isHeadOffice ? $material->quantity : null;
            $siteRemaining = max(0, $assignedToProjects - $assignedToEmployees);
            $totalStockedQuantity = ($headOfficeAvailable ?? 0) + $assignedToProjects;
            $totalInSystem = ($headOfficeAvailable ?? 0) + $siteRemaining;

            return [
                'id' => $material->id,
                'name' => $material->material_name,
                'description' => $material->material_description,
                'head_office_available' => $headOfficeAvailable,
                'total_stocked_quantity' => $totalStockedQuantity,
                'assigned_to_projects' => $assignedToProjects,
                'assigned_to_employees' => $assignedToEmployees,
                'site_remaining' => $siteRemaining,
                'total_in_system' => $totalInSystem,
            ];
        })->values()->all();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @param  Collection<int, int>  $projectIds
     * @return Collection<int|string, int>
     */
    private function assignmentsByMaterial($query, Collection $projectIds): Collection
    {
        if ($projectIds->isEmpty()) {
            return collect();
        }

        return $query
            ->whereIn('project_id', $projectIds)
            ->selectRaw('material_id, SUM(quantity) as total')
            ->groupBy('material_id')
            ->pluck('total', 'material_id')
            ->map(fn ($total): int => (int) $total);
    }
}
