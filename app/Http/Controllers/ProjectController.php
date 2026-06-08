<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('projects.index', [
            'projects' => Project::with('siteOfficer')->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'project' => new Project(),
            'siteOfficers' => $this->siteOfficers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'project_code' => ['required', 'string', 'max:255', 'unique:projects,project_code'],
            'site_officer_id' => ['required', 'exists:users,id'],
        ]);

        $this->ensureSiteOfficer($data['site_officer_id']);

        Project::create($data);

        return Redirect::route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', [
            'project' => $project,
            'siteOfficers' => $this->siteOfficers(),
        ]);
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'project_name' => ['required', 'string', 'max:255'],
            'project_code' => ['required', 'string', 'max:255', 'unique:projects,project_code,'.$project->id],
            'site_officer_id' => ['required', 'exists:users,id'],
        ]);

        $this->ensureSiteOfficer($data['site_officer_id']);

        $project->update($data);

        return Redirect::route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return Redirect::route('projects.index')->with('success', 'Project deleted successfully.');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    private function siteOfficers()
    {
        return User::role('HSE Site Officer')->orderBy('name')->get();
    }

    private function ensureSiteOfficer(int $userId): void
    {
        $user = User::findOrFail($userId);

        if (! $user->hasRole('HSE Site Officer')) {
            abort(422, 'Selected user must have the HSE Site Officer role.');
        }
    }
}
