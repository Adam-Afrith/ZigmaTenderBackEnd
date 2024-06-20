<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class calltobdm extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the calltobdm
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the comments for the calltobdm
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customer()
    {
        return $this->hasMany(calltobdm_has_customer::class, 'calltobdm_id');
    }
    
}
