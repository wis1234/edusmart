<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

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
        'status'
    ];

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
        return $this->hasMany(User::class)->role('enseignant');
    }

    /**
     * Get the students for the school.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
