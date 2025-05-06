<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Service; 
use App\Models\Customer; // Tambahkan ini

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->latest()->get();
        return view('orders.index', compact('orders'));
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
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}