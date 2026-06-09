<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurhatMessage extends Model
{
    /** @use HasFactory<\Database\Factories\CurhatMessageFactory> */
    use HasFactory;

    protected $fillable = [
        'curhat_id',
        'user_id',
        'sender_type',
        'is_read',
        'read_at',
        'message',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'message' => 'array',
    ];

    public function curhat()
    {
        return $this->belongsTo(Curhat::class, 'curhat_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
