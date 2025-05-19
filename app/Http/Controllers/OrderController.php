<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Customer;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer', 'service');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan nama customer
        if ($request->has('customer') && $request->customer != '') {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        // Filter berdasarkan tanggal pickup
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('pickup_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('pickup_date', '<=', $request->date_to);
        }

        // Filter berdasarkan jenis layanan
        if ($request->has('service_id') && $request->service_id != '') {
            $query->where('service_id', $request->service_id);
        }

        // Urutkan berdasarkan ID (A-Z atau Z-A)
        if ($request->has('sort_id') && in_array($request->sort_id, ['asc', 'desc'])) {
            $query->orderBy('id', $request->sort_id);
        } else {
            $query->latest(); // Default: urutkan dari ID terbaru
        }

        // Ambil data orders dan terapkan pagination
        $orders = $query->paginate(10)->appends($request->query());

        // Ambil data layanan
        $services = Service::orderBy('name')->get();

        // Kirim ke view
        return view('orders.index', compact('orders', 'services'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        return view('orders.create', compact('customers', 'services'));
    }

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

        // Bersihkan format total_price jika perlu
        $validated['total_price'] = str_replace(['Rp ', '.'], '', $validated['total_price']);

        Order::create($validated);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

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
