<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'phone', 'email'])]
class Supplier extends Model
{
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
