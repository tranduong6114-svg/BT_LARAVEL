<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $primaryKey = 'emp_id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'emp_id',
        'full_name',
        'email',
        'base_salary',
        'actual_salary',
        'birthday',
        'department_id',
        'position_id'
    ];

    public function getIdAttribute()
    {
        return $this->emp_id;
    }

    public function department(): BelongsTo {
        return $this->belongsTo(Department::class);
    }
    public function position(): BelongsTo {
        return $this->belongsTo(Position::class);
    }
}
