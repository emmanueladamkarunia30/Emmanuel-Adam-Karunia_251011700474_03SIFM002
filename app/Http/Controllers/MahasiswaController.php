<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = [
            'nim' => '251011700474',
            'nama' => 'emmanuel',
            'prodi' => 'Sistem Informasi',
            'kampus' => 'Universitas Pamulang',
            'email' => 'emmanuel@gmail.com',
            'status' => 'Aktif',
        ];

        return view('page.profile', compact('mahasiswa'));
    }
}
