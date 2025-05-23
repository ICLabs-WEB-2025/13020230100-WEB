@php
    $order = $order ?? null;
@endphp
@if(!$order || !$order->customer || !$order->service)
<div class="alert alert-danger m-3">Data pesanan tidak lengkap. Tidak dapat menampilkan struk.</div>
@else
<div class="struk-container">
    <div class="struk-header">
        <h2>Laundry-In</h2>
        <div class="text-muted">Struk Pesanan</div>
    </div>
    <div class="text-center mb-2">
        {!! QrCode::size(120)->generate(route('orders.updateStatus', ['id' => $order->id])) !!}
        <div style="font-size:0.9rem; color:#888;">ID: #{{ $order->id }}</div>
    </div>
    <table class="table struk-table table-borderless mb-2">
        <tr><td>ID Pesanan</td><td>: #{{ $order->id }}</td></tr>
        <tr><td>Nama</td><td>: {{ $order->customer->name }}</td></tr>
        <tr><td>Layanan</td><td>: {{ $order->service->name }}</td></tr>
        <tr><td>Berat</td><td>: {{ $order->weight }} kg</td></tr>
        <tr><td>Total</td><td>: Rp {{ number_format($order->total_price,0,',','.') }}</td></tr>
        <tr><td>Pembayaran</td><td>: {{ ucfirst($order->payment_method) }}</td></tr>
        <tr><td>Status</td><td>: {{ ucfirst($order->status) }}</td></tr>
        <tr><td>Tanggal Masuk</td><td>: {{ $order->pickup_date }}</td></tr>
        <tr><td>Tanggal Selesai</td><td>: {{ $order->delivery_date }}</td></tr>
    </table>
    <div class="struk-footer">
        Terima kasih telah menggunakan Laundry-In!<br>
        <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
    </div>
    <div class="text-center mt-3">
        <button class="btn btn-primary btn-cetak" onclick="window.print()"><i class="bi bi-printer"></i> Cetak Struk</button>
    </div>
</div>
@endif
<style>
.struk-container {
    max-width: 400px;
    margin: 30px auto;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 24px 20px;
}
.struk-header {
    text-align: center;
    margin-bottom: 18px;
}
.struk-header h2 {
    font-size: 1.3rem;
    font-weight: bold;
}
.struk-table td {
    padding: 4px 0;
}
.struk-footer {
    text-align: center;
    margin-top: 18px;
    font-size: 0.95rem;
    color: #888;
}
@media print {
    .btn-cetak { display: none; }
    .struk-container { box-shadow: none; }
}
</style>
