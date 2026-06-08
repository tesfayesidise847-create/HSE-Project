<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function hasAvailableQuantity(int $amount): bool
    {
        return $this->quantity >= $amount;
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
