<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $appends = ['full_account_name'];

    protected $fillable = [
        'account_name',
        'parent_id',
        'account_level',
        'is_deactivated',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        // You MUST include parent_id for the hierarchy to work
        return $query->where('is_deactivated', false)->select('id', 'account_name', 'parent_id');
    }

    public function getFullAccountNameAttribute(): string
    {
        // Important: check if relation is loaded or exists to prevent errors
        if ($this->parent_id && $this->parent) {
            return $this->parent->full_account_name . ' > ' . $this->account_name;
        }

        return $this->account_name;
    }

    public function scopeFiltered($query, string $type = 'all')
    {
        // Added parent_id here too
        $query->where('is_deactivated', false)->select('id', 'account_name', 'parent_id');

        return match ($type) {
            'leaves'  => $query->whereDoesntHave('children'),
            'parents' => $query->has('children'),
            default   => $query,
        };
    }
}