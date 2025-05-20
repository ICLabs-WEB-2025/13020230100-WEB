@extends('layouts.app')
@section('title', 'Update Status Pesanan')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4>Update Status Pesanan #{{ $order->id }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="status">Pilih Status Baru</label>
                    <select name="status" id="status" class="form-control">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Update Status</button>
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
