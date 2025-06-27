@extends('layouts.main')

@section('title', 'Manage Orders')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action">Dashboard</a>
                    <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">My Products</a>
                    <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action">Add New
                        Product</a>
                    <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action active">Orders</a>
                    <a href="{{ route('admin.payments.index') }}" class="list-group-item list-group-item-action">Payment
                        Verification</a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">All Orders</h4>
                </div>
                <div class="card-body">
                    <!-- Filter options -->
                    <div class="mb-3">
                        <form action="{{ route('admin.orders') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                        Processing</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>
                                        Declined</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select name="payment_status" class="form-select">
                                    <option value="">All Payment Statuses</option>
                                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                        Failed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.orders') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders ?? [] as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($order->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($order->status == 'processing')
                                                <span class="badge bg-info">Processing</span>
                                            @elseif($order->status == 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($order->status == 'declined')
                                                <span class="badge bg-danger">Declined</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->payment_status == 'unpaid')
                                                <span class="badge bg-secondary">Unpaid</span>
                                            @elseif($order->payment_status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($order->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($order->payment_status == 'failed')
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="btn btn-sm btn-info">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
