<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KonselingSiswa extends Model
{
    /** @use HasFactory<\Database\Factories\KonselingSiswaFactory> */
    use HasFactory;

    protected $fillable = [
        'student_id',
        'counselor_id',
        'problem',
        'status',
        'content',
        'scheduled_at',
        'is_read_by_counselor',
        'read_at_by_counselor',
    ];

    protected $casts = [
        'content' => 'array',
        'is_read_by_counselor' => 'boolean',
        'read_at_by_counselor' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}
