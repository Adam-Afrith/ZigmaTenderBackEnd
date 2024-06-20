<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherExpenses extends Model
{
    use HasFactory;
    protected $table = 'other_expenses';
    protected $fillable = [
        'expense_no',
        'entry_date',
        'executive_id',
        'description',
        'created_by',
    ];
}
