<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AduanController extends Controller
{
    public function create()
    {
        $kategoris = \App\Models\Kategori::all();
        return view('fe.aduan.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'isi_aduan'      => 'required|string',
            'kategori_id'    => 'required|exists:kategoris,id',
            'lokasi'         => 'required|string|max:255',
            'status'         => 'in:diproses,selesai', // status diisi otomatis
            'tanggal_aduan'  => 'nullable|date',
            'gambar'         => 'nullable|image|max:2048',
        ]);

        $aduan = new Aduan();
        $aduan->judul          = $validated['judul'];
        $aduan->isi_aduan      = $validated['isi_aduan'];
        $aduan->kategori_id    = $validated['kategori_id'];
        $aduan->lokasi         = $validated['lokasi'];
        $aduan->status         = 'diproses'; // default sesuai `AduanResource`
        $aduan->user_id        = Auth::id(); // pelapor otomatis dari user login
        $aduan->tanggal_aduan  = $validated['tanggal_aduan'] ?? now(); // default sekarang jika tidak diisi

        if ($request->hasFile('gambar')) {
            $aduan->gambar_aduan = $request->file('gambar')->store('aduan', 'public');
        }

        $aduan->save();

        return redirect()->route('dashboard')->with('success', 'Aduan berhasil ditambahkan.');
    }
}
