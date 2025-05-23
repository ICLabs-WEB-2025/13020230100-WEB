<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Payment;
use App\Services\WhatsappHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller; // Pastikan ini diimpor

class OrderController extends Controller // Pastikan mewarisi Controller
{
    public function __construct()
    {
        // Terapkan middleware auth untuk metode tertentu
        $this->middleware('auth')->except(['index', 'show']);
    }

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
        
        // Jika tidak ada pelanggan untuk admin, tampilkan pesan
        if (Auth::check() && Auth::user()->role === 'admin' && $customers->isEmpty()) {
            return redirect()->route('orders.index')->with('error', 'Tidak ada pelanggan tersedia. Tambahkan pelanggan terlebih dahulu.');
        }

        return view('orders.create', compact('customers', 'services'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $this->validateOrderRequest($request);

        // Untuk non-admin, pastikan customer_id adalah ID pelanggan yang sesuai
        if (Auth::check() && Auth::user()->role !== 'admin') {
            $customer = Customer::where('user_id', Auth::id())->first();
            if (!$customer) {
                Log::warning('Pengguna tidak memiliki entri pelanggan', ['user_id' => Auth::id()]);
                return redirect()->route('user.profile')->with('error', 'Profil pelanggan belum diatur. Silakan lengkapi profil Anda.');
            }
            $validated['customer_id'] = $customer->id;
        }

        $validated['total_price'] = $this->cleanPriceFormat($validated['total_price']);
        $validated['pickup_service'] = $request->input('pickup_service');
        $validated['delivery_service'] = $request->input('delivery_service');
        $validated['payment_method'] = $request->input('payment_method');

        // Logic status pesanan sesuai workflow laundry
        if ($validated['payment_method'] === 'cash') {
            $validated['status'] = 'processing';
        } elseif ($validated['payment_method'] === 'online') {
            $validated['status'] = 'pending';
        }

        $order = null;
        DB::transaction(function () use ($validated, &$order) {
            $order = Order::create($validated);

            if ($validated['payment_method'] === 'online') {
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $validated['total_price'],
                    'status' => 'pending',
                ]);
                // Integrasi gateway pembayaran dapat ditambahkan di sini
            }
        });

        $message = 'Pesanan berhasil dibuat';
        if (Auth::check() && Auth::user()->role === 'admin' && $validated['payment_method'] === 'online') {
            $message .= ' (Konfirmasi pembayaran online diperlukan, status pesanan: processing)';
        }

        // Jika request dari modal (AJAX atau form dengan ?from_dashboard_modal=1), balas JSON dan sertakan view struk
        if ($request->ajax() || $request->has('from_dashboard_modal')) {
            $strukView = view('orders.struk', ['order' => $order])->render();
            return response()->json(['success' => true, 'message' => $message, 'struk' => $strukView]);
        }
        return redirect()->route('orders.index')->with('success', $message);
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $customers = Customer::orderBy('name')->get();
        $services = Service::orderBy('name')->get();
        
        // Pastikan order milik customer yang valid
        if (!Customer::where('id', $order->customer_id)->exists()) {
            return redirect()->route('orders.index')->with('error', 'Pelanggan tidak valid untuk pesanan ini.');
        }

        return view('orders.edit', compact('order', 'customers', 'services'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $this->validateOrderRequest($request);

        // Untuk non-admin, pastikan customer_id tidak diubah
        if (Auth::check() && Auth::user()->role !== 'admin') {
            $customer = Customer::where('user_id', Auth::id())->first();
            if (!$customer) {
                return redirect()->route('user.profile')->with('error', 'Profil pelanggan belum diatur. Silakan lengkapi profil Anda.');
            }
            $validated['customer_id'] = $customer->id;
        }

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
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pesanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus pesanan');
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

        // Jika request dari modal konfirmasi (AJAX atau form biasa), tampilkan pesan sukses tanpa redirect ke halaman lain
        if ($request->ajax() || $request->has('from_dashboard_modal')) {
            return response()->json(['success' => true, 'message' => 'Status pesanan berhasil dikonfirmasi!']);
        }

        return back()->with('success', 'Status pesanan berhasil dikonfirmasi!');
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
        // Untuk validasi, status tidak perlu diinput user, set default saja jika tidak ada
        $rules = [
            'customer_id' => [
                'required',
                'exists:customers,id',
            ],
            'service_id' => 'required|exists:services,id',
            'weight' => 'required|numeric|min:0.1',
            'total_price' => 'required|numeric',
            'pickup_date' => 'required|date',
            'delivery_date' => 'required|date|after:pickup_date',
            'pickup_service' => 'required|in:yes,no',
            'delivery_service' => 'required|in:yes,no',
            'payment_method' => 'required|in:online,cash',
            'notes' => 'nullable|string|max:500',
            'status' => 'required|in:pending,processing,completed,cancelled',
        ];

        // Untuk non-admin, customer_id tidak perlu divalidasi dari input
        if (Auth::check() && Auth::user()->role !== 'admin') {
            unset($rules['customer_id']);
        }

        return $request->validate($rules);
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

    public function userOrders(Request $request)
    {
        // Ambil customer berdasarkan user_id pengguna yang login
        $customer = Customer::where('user_id', Auth::id())->first();

        // Jika customer tidak ditemukan, arahkan ke profil dengan pesan error
        if (!$customer) {
            Log::warning('Pengguna tidak memiliki entri pelanggan', ['user_id' => Auth::id()]);
            return redirect()->route('user.profile')->with('error', 'Profil pelanggan belum diatur. Silakan lengkapi profil Anda.');
        }

        // Log untuk debugging
        Log::info('Mengambil pesanan untuk pengguna', [
            'user_id' => Auth::id(),
            'customer_id' => $customer->id,
        ]);

        // Ambil pesanan hanya untuk customer_id yang sesuai
        $query = Order::with('service')->where('customer_id', $customer->id);

        // Terapkan filter status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->appends($request->query());

        // Log jumlah pesanan yang ditemukan
        Log::info('Jumlah pesanan ditemukan', ['count' => $orders->count()]);

        return view('orders.user-orders', compact('orders'));
    }
}