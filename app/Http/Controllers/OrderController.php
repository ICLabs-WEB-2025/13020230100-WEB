<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Service; 
use App\Models\Customer; // Tambahkan ini

class OrderController extends Controller
{
    public function index(Request $request)
{
    $query = Order::with('customer', 'service');
    
    // Filter berdasarkan status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // Filter berdasarkan customer name
    if ($request->has('customer') && $request->customer != '') {
        $query->whereHas('customer', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->customer . '%');
        });
    }

    // Filter berdasarkan tanggal
    if ($request->has('date_from') && $request->date_from != '') {
        $query->whereDate('pickup_date', '>=', $request->date_from);
    }
    if ($request->has('date_to') && $request->date_to != '') {
        $query->whereDate('pickup_date', '<=', $request->date_to);
    }

    // Filter berdasarkan layanan
    if ($request->has('service_id') && $request->service_id != '') {
        $query->where('service_id', $request->service_id);
    }

    // Ambil data orders yang sudah difilter
    $orders = $query->latest()->get();

    // Ambil data layanan
    $services = Service::orderBy('name')->get();

    // Kirim data orders dan services ke tampilan
    return view('orders.index', compact('orders', 'services'));
}




    public function create()
    {
        $customers = \App\Models\Customer::orderBy('name')->get();
        $services = \App\Models\Service::orderBy('name')->get();
        \Log::debug('Services Data:', $services->toArray());
        return view('orders.create', compact('customers', 'services'));
    }

    // OrderController.php
public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'service_id' => 'required|exists:services,id',
        'weight' => 'required|numeric|min:0.1',
        'total_price' => 'required|numeric',
        'status' => 'required|in:pending,processing,completed,cancelled',
        'pickup_date' => 'required|date',
        'delivery_date' => 'required|date|after:pickup_date',
        'notes' => 'nullable|string'
    ]);

    // Konversi total_price dari format "Rp 1.000.000" ke angka
    $validated['total_price'] = str_replace(['Rp ', '.'], '', $validated['total_price']);

    Order::create($validated);

    return redirect()->route('orders.index')->with('success', 'Order created successfully.');
}

// Update method update() juga


    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
{
    $customers = Customer::orderBy('name')->get();
    $services = Service::orderBy('name')->get();
    return view('orders.edit', compact('order', 'customers', 'services'));
}

public function update(Request $request, Order $order)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'service_id' => 'required|exists:services,id',
        'weight' => 'required|numeric|min:0.1',
        'total_price' => 'required|numeric',
        'status' => 'required|in:pending,processing,completed,cancelled',
        'pickup_date' => 'required|date',
        'delivery_date' => 'required|date|after:pickup_date',
        'notes' => 'nullable|string'
    ]);
    
    $order->update($validated);
    
    return redirect()->route('orders.show', $order->id)
        ->with('success', 'Pesanan berhasil diperbarui');
}

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete order');
        }
    }
}