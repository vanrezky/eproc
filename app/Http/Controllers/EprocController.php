<?php

namespace App\Http\Controllers;

use App\Models\Eproc;
use App\Models\EprocLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EprocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eprocs = Eproc::with(['updatedUser' => fn($Q) => $Q->select('id', 'name')])->select(['id', 'nama_perusahaan_rekanan', 'link_website', 'keterangan', 'user_id', 'created_at', 'updated_at', 'updated_user_id'])->paginate(10);

        return Inertia::render('eproc/Index', [
            'eprocs' => $eprocs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('eproc/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan_rekanan' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'link_website' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $eproc = Eproc::create([
            'nama_perusahaan_rekanan' => $request->nama_perusahaan_rekanan,
            'username' => $request->username,
            'password' => $request->password,
            'link_website' => $request->link_website,
            'keterangan' => $request->keterangan,
            'user_id' => Auth::id(),
        ]);

        // Log create action
        EprocLog::create([
            'eproc_id' => $eproc->id,
            'user_id' => Auth::id(),
            'action' => 'create',
            'old_data' => null,
            'new_data' => $eproc->toArray(),
        ]);

        return Redirect::route('eproc/index')->with('success', 'Eproc berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $eproc = Eproc::with(['user', 'updatedUser'])->findOrFail($id);

        $eproc->updated_user_name = $eproc->updatedUser?->name ?? null;
        $logs = EprocLog::with('user')
            ->where('eproc_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();


        return Inertia::render('eproc/Show', [
            'eproc' => $eproc,
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $eproc = Eproc::select(['id', 'nama_perusahaan_rekanan', 'username', 'link_website', 'keterangan'])->findOrFail($id);

        return Inertia::render('eproc/Edit', [
            'eproc' => $eproc,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $eproc = Eproc::findOrFail($id);

        $request->validate([
            'nama_perusahaan_rekanan' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'nullable|string|max:255',
            'link_website' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $oldData = $eproc->toArray();

        $data = [
            'nama_perusahaan_rekanan' => $request->nama_perusahaan_rekanan,
            'username' => $request->username,
            'link_website' => $request->link_website,
            'keterangan' => $request->keterangan,
            'updated_user_id' => Auth::id(),
        ];

        if ($request->password) {
            $data['password'] = $request->password;
        }

        $eproc->update($data);

        // Log update action
        EprocLog::create([
            'eproc_id' => $eproc->id,
            'user_id' => Auth::id(),
            'action' => 'update',
            'old_data' => $oldData,
            'new_data' => $eproc->toArray(),
        ]);

        return Redirect::route('eproc/index')->with('success', 'Eproc berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $eproc = Eproc::findOrFail($id);
        $oldData = $eproc->toArray();

        $eproc->delete();

        // Log delete action
        EprocLog::create([
            'eproc_id' => $oldData['id'],
            'user_id' => Auth::id(),
            'action' => 'delete',
            'old_data' => $oldData,
            'new_data' => null,
        ]);

        return Redirect::route('eproc/index')->with('success', 'Eproc berhasil dihapus.');
    }
}
