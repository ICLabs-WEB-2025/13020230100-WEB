<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use App\Services\WhatsappHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer', 'service');

        $this->applyFilters($query, $request);
        $this->applySorting($query, $request);

        $orders = $query->paginate(10)->appends($request->query());
        $services = Service::orderBy('name')->get();

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
        $validated = $this->validateOrderRequest($request);
        $validated['total_price'] = $this->cleanPriceFormat($validated['total_price']);

        DB::transaction(function () use ($validated) {
            Order::create($validated);
        });

        return redirect()->route('orders.index')->with('success', 'Order berhasil dibuat');
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
        $validated = $this->validateOrderRequest($request);
        $validated['total_price'] = $this->cleanPriceFormat($validated['total_price']);

        $statusSebelumnya = $order->status;

        $order->update($validated);

        if ($validated['status'] === 'completed' && $statusSebelumnya !== 'completed') {
            $this->sendCompletionNotification($order);
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Pesanan berhasil diperbarui');
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus order: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus order');
        }
    }

    public function updateStatusForm($id)
    {
        $order = Order::findOrFail($id);
        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        return view('orders.update-status', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('customer', 'service')->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $statusSebelumnya = $order->status;

        DB::transaction(function () use ($order, $validated, $statusSebelumnya) {
            $order->update(['status' => $validated['status']]);

            if ($validated['status'] === 'completed' && $statusSebelumnya !== 'completed') {
                $this->sendCompletionNotification($order);
            }
        });

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Status pesanan berhasil diperbarui');
    }

    protected function sendCompletionNotification(Order $order)
    {
        try {
            $customer = $order->customer;
            $phone = $this->formatPhoneNumber($customer->phone);

            if (!$phone) {
                throw new \Exception('Nomor WhatsApp tidak tersedia');
            }

            $message = $this->buildCompletionMessage($order);
            $options = [
                'countryCode' => '62',
                'typing' => true,
                'preview' => true,
            ];

            $whatsapp = new WhatsappHelper();
            $response = $whatsapp->sendMessage($phone, $message, $options);

            if (!$response['success']) {
                throw new \Exception($response['message'] ?? 'Gagal mengirim notifikasi');
            }

            $order->update([
                'whatsapp_sent_at' => now(),
                'whatsapp_status' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Error mengirim notifikasi WhatsApp: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'error' => $e->getTraceAsString()
            ]);

            $order->update([
                'whatsapp_status' => 'failed',
                'whatsapp_error' => $e->getMessage()
            ]);
        }
    }

    protected function buildCompletionMessage(Order $order)
    {
        return "Halo {$order->customer->name},\n\n" .
               "📌 Pesanan Anda untuk *{$order->service->name}* telah selesai:\n" .
               "🆔 ID Pesanan: #{$order->id}\n" .
               "⏰ Waktu Selesai: " . $order->updated_at->format('d/m/Y H:i') . "\n" .
               "💵 Total Biaya: Rp " . number_format($order->total_price, 0, ',', '.') . "\n\n" .
               "Terima kasih telah menggunakan layanan kami!\n\n" .
               "📞 Hubungi kami jika ada pertanyaan.";
    }

    protected function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '+62')) {
            return substr($phone, 1);
        }

        return $phone;
    }

    protected function validateOrderRequest(Request $request)
    {
        return $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1',
            'total_price' => 'required|numeric',
            'status' => 'required|in:pending,processing,completed,cancelled',
            'pickup_date' => 'required|date',
            'delivery_date' => 'required|date|after:pickup_date',
            'notes' => 'nullable|string'
        ]);
    }

    protected function cleanPriceFormat($price)
    {
        return str_replace(['Rp ', '.', ','], '', $price);
    }

    protected function applyFilters($query, Request $request)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('pickup_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('pickup_date', '<=', $request->date_to);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
    }

    protected function applySorting($query, Request $request)
    {
        if ($request->filled('sort_id') && in_array($request->sort_id, ['asc', 'desc'])) {
            $query->orderBy('id', $request->sort_id);
        } else {
            $query->latest();
        }
    }
}
