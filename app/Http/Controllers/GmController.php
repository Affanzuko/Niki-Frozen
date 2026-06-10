<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GmController extends Controller
{
    public function dashboard()
    {
        return view('pages.general-manager.dashboard');
    }

    public function keuangan()
    {
        return view('pages.general-manager.keuangan');
    }

    public function detailKeuangan($cabang)
    {
        return view('pages.general-manager.keuangan-detail', compact('cabang'));
    }

    public function kelolaAkun()
    {
        return view('pages.general-manager.kelola-akun');
    }

    public function tambahAkun()
    {
        return view('pages.general-manager.tambah-akun');
    }

    public function editAkun($id)
    {
        return view('pages.general-manager.edit-akun', compact('id'));
    }
}
