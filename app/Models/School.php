<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class School extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'description',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'principal_name',
        'type',
        'capacity',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the user who created the school.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the school.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the classrooms for the school.
     */
    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }

    /**
     * Get the teachers for the school.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Get the students for the school.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
