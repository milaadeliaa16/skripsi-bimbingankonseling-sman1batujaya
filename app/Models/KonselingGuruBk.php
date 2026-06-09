<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KonselingGuruBk extends Model
{
    use HasFactory;

    protected $fillable = [

        'student_id',
        'counselor_id',
        'problem',
        'summary',
        'solution',
        'notes',
        'type_of_violation',
        'point_of_violation',
        'history_of_violation',
        'scheduled_at',
        'is_read_by_student',
        'read_at_by_student',

        // sini, hapus kalau tidak lagi di pakai
        // 'student_id',
        // 'counselor_id',
        // 'type',
        // 'status',
    ];

    protected $casts = [
        'problem' => 'array',
        'summary' => 'array',
        'solution' => 'array',
        'notes' => 'array',
        'is_read_by_student' => 'boolean',
        'read_at_by_student' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function studentViolationHistories(): HasMany
    {
        return $this->hasMany(self::class, 'student_id', 'student_id');
    }
}
