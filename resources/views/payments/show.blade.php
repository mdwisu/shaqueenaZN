@extends('layouts.main')

@section('title', 'Payment Proof Detail')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">Products</a>
                    <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">Orders</a>
                    <a href="{{ route('admin.payments.index') }}"
                        class="list-group-item list-group-item-action active">Payment
                        Verification</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Payment Proof Detail</h4>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-light">Back to List</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order information</h5>
                            <table class="table">
                                <tr>
                                    <th>Order Number:</th>
                                    <td>{{ $paymentProof->order->order_number }}</td>
                                </tr>
                                <tr>
                                    <th>Customer:</th>
                                    <td>{{ $paymentProof->order->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Total Amount:</th>
                                    <td>Rp {{ number_format($paymentProof->order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $paymentProof->order->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Payment Upload Date:</th>
                                    <td>{{ $paymentProof->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Notes:</th>
                                    <td>{{ $paymentProof->notes ?? '-' }}</td>
                                </tr>
                            </table>

                            @if ($paymentProof->status == 'pending')
                                <div class="mt-4">
                                    <h5>Action</h5>
                                    <div class="d-flex">
                                        <form action="{{ route('admin.payments.verify', $paymentProof->id) }}"
                                            method="POST" class="me-2">
                                            @csrf
                                            <input type="hidden" name="status" value="verified">
                                            <button type="submit" class="btn btn-success">Verify Payment</button>
                                        </form>

                                        <form action="{{ route('admin.payments.verify', $paymentProof->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-danger">Reject Payment</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="alert {{ $paymentProof->status == 'verified' ? 'alert-success' : 'alert-danger' }}">
                                    This payment has been {{ $paymentProof->status }} on
                                    {{ $paymentProof->verified_at->format('d M Y H:i') }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>Gambar Bukti Pembayaran</h5>
                            <div class="border p-2">
                                <img src="{{ asset('storage/' . $paymentProof->image_path) }}" alt="Payment Proof"
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
