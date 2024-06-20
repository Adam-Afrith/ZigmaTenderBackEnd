<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;

    /**
     * Get all of the comments for the ExpenseType_has_Limits
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function limitsOfRole()
    {
        return $this->hasMany(ExpenseType_has_Limits::class, 'expnseType_id');
    }
}
