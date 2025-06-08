<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'kategori_id', 'judul', 'lokasi', 'isi_aduan', 'gambar_aduan', 'status', 'tanggal_aduan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function tanggapan()
    {
        return $this->hasMany(Tanggapan::class);
    }
}
