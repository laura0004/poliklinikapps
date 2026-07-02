<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();

        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::all();
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        // 1. Validasi input dasar
        $request->validate([
            'obat_json' => 'required',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obatIds = json_decode($request->obat_json, true);

        // 2. [TAMBAHAN UAS] VALIDASI STOK OBAT
        // Mengecek semua obat yang dipilih sebelum data periksa disimpan ke database
        foreach ($obatIds as $idObat) {
            $obat = Obat::find($idObat);
            
            // Jika obat tidak ditemukan atau stoknya sudah 0 (habis)
            if (!$obat || $obat->stok < 1) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Stok obat "' . ($obat->nama_obat ?? 'Tidak Diketahui') . '" habis atau tidak mencukupi!');
            }
        }

        // 3. Simpan data ke tabel periksa jika semua stok aman
        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa' => now(),
            'catatan' => $request->catatan,
            'biaya_periksa' => $request->biaya_periksa + 150000,
        ]);

        // 4. [TAMBAHAN UAS] SIMPAN DETAIL & POTONG STOK OTOMATIS
        foreach ($obatIds as $idObat) {
            $obat = Obat::find($idObat);

            // Menyimpan resep ke tabel detail_periksa dengan kolom 'jumlah' yang baru kita buat kemarin
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat' => $idObat,
                'jumlah' => 1, // Default berkurang 1 biji per obat
            ]);

            // Mengurangi stok obat di database secara otomatis
            $obat->stok = $obat->stok - 1;
            $obat->save();
        }

        return redirect()->route('periksa-pasien.index')->with('success', 'Data periksa berhasil disimpan dan stok obat telah diperbarui.');
    }
}