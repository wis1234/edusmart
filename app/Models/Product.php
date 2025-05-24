<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'teacher_id',
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image',
    ];

    // Relation avec l'utilisateur qui a créé ou possède le produit
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec l'étudiant lié au produit (si applicable)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relation avec l'enseignant lié au produit (si applicable)
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
// Relation avec les articles de commande
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}