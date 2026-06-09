<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade',
        'jurusan',
        'capacity',
        'slug',
        'description',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'kelas_id');
    }
}
