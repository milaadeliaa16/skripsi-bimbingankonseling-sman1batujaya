<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curhat extends Model
{
    /** @use HasFactory<\Database\Factories\CurhatFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'title',
        'is_anonymous',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function messages()
    {
        return $this->hasMany(CurhatMessage::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(CurhatMessage::class)->latestOfMany();
    }
}
