<?php

namespace App\Http\Controllers;

use App\Models\Eproc;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with eproc overview.
     */
    public function index()
    {
        $eprocs = Eproc::with(['updatedUser' => fn($q) => $q->select('id', 'name')])
            ->select(['id', 'nama_perusahaan_rekanan', 'keterangan', 'created_at', 'updated_at', 'updated_user_id'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return Inertia::render('Dashboard', [
            'eprocs' => $eprocs,
        ]);
    }
}
