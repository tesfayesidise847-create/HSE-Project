<?php

namespace App\Models;

use App\Models\MaterialHistory;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'material_name',
        'material_description',
        'quantity',
        'unit_of_measure_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
        ];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(MaterialProjectAssignment::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(MaterialHistory::class)->latest();
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }

    public function hasAvailableQuantity(int $amount): bool
    {
        return $this->quantity >= $amount;
    }

    public function recordHistory(string $eventType, int $quantityChange, string $description, ?int $createdBy = null)
    {
        return MaterialHistory::record($this, $eventType, $quantityChange, $description, $createdBy);
    }

    public function deductQuantity(int $amount): void
    {
        $this->decrement('quantity', $amount);
    }

    public function addQuantity(int $amount): void
    {
        $this->increment('quantity', $amount);
    }
}
