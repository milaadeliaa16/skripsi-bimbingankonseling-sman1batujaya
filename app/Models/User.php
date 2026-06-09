<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Absence;
use App\Models\Curhat;
use App\Models\CurhatMessage;
use App\Models\Kelas;
use App\Models\KonselingGuruBk;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    public const ROLE_KEPALA_SEKOLAH = 'kepala_sekolah';
    public const ROLE_GURU_BK = 'guru_bk';
    public const ROLE_SISWA = 'siswa';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'nip',
        'nisn',
        'kelas_id',
        'alamat',
        'no_hp_orang_tua',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function konselingGuruBk(): HasMany
    {
        return $this->hasMany(KonselingGuruBk::class, 'student_id');
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'student_id');
    }

    public function konselingSiswa(): HasMany
    {
        return $this->hasMany(KonselingSiswa::class, 'student_id');
    }

    public function curhatsAsStudent(): HasMany
    {
        return $this->hasMany(Curhat::class, 'student_id');
    }

    public function curhatsAsTeacher(): HasMany
    {
        return $this->hasMany(Curhat::class, 'teacher_id');
    }

    public function curhatMessages(): HasMany
    {
        return $this->hasMany(CurhatMessage::class, 'user_id');
    }

    public function scopeKepalaSekolah($query)
    {
        return $query->where('type', self::ROLE_KEPALA_SEKOLAH);
    }

    public function scopeGuruBk($query)
    {
        return $query->where('type', self::ROLE_GURU_BK);
    }

    public function scopeSiswa($query)
    {
        return $query->where('type', self::ROLE_SISWA);
    }
}
