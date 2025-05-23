<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    // Tampilkan semua layanan
    public function index()
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    // Tampilkan form tambah layanan
    public function create()
    {
        return view('services.create');
    }

    // Simpan layanan baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        Service::create($request->only(['name', 'price_per_kg']));

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan!');
    }

    // Tampilkan detail layanan
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('services.show', compact('service'));
    }

    // Form edit layanan
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('services.edit', compact('service'));
    }

    // Proses update layanan
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->update($request->only(['name', 'price_per_kg']));

        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui!');
    }

    // Hapus layanan
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        // Cek apakah service masih punya order
        if ($service->orders()->count() > 0) {
            return redirect()->route('services.index')
                ->with('error', 'Layanan masih memiliki pesanan. Hapus semua pesanan yang menggunakan layanan ini terlebih dahulu.');
        }
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus!');
    }
}
