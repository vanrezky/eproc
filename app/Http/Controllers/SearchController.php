<?php

namespace App\Http\Controllers;

use App\Models\Eproc;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return response()->json([]);
        }

        $eprocs = Eproc::query()
            ->where('nama_perusahaan_rekanan', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->orWhere('keterangan', 'like', "%{$query}%")
            ->orWhere('link_website', 'like', "%{$query}%")
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($eproc) {
                return [
                    'id' => $eproc->id,
                    'title' => $eproc->nama_perusahaan_rekanan,
                    'subtitle' => 'Eproc - ' . $eproc->username,
                    'type' => 'eproc',
                    'url' => route('eproc/show', $eproc->id),
                ];
            });

        $users = User::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'title' => $user->name,
                    'subtitle' => 'User - ' . $user->email,
                    'type' => 'user',
                    'url' => route('user/show', $user->id),
                ];
            });

        $results = $eprocs->concat($users)->take(10);

        return response()->json($results);
    }
}
