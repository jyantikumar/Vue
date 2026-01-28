<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Entity extends Model
{
    protected $fillable = ['name', 'entity_type', 'identifier', 'status', 'description', 'notes'];

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'account_entity');
    }
}