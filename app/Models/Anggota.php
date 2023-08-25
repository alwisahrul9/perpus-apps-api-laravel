<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function peminjaman()
    {
        return $this->hasMany(Pinjam::class);
    }

    public function pengembalian()
    {
        return $this->hasMany(Pengembalian::class);
    }
}
