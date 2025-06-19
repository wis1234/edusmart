<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'user_id',
        'student_id',
        'teacher_id',
        'name',
        'email',
        'address',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
