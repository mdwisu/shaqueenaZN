<!-- resources/views/dashboard/admin.blade.php -->
@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Sidebar -->
        <div class="card">
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action active">Dashboard</a>
                {{-- <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">Products</a> --}}
                {{-- <a href="{{ route('admin.categories') }}" class="list-group-item list-group-item-action">Categories</a> --}}
                {{-- <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action">Users</a> --}}
                {{-- <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">Orders</a> --}}
                <a href="{{ route('payment.index') }}" class="list-group-item list-group-item-action">Payment Verification</a>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Admin Dashboard</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Total Products</h5>
                                <h2>{{ $totalProducts ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Total Users</h5>
                                <h2>{{ $totalUsers ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Total Orders</h5>
                                <h2>{{ $totalOrders ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4">Recent Orders</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Processing</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($order->status == 'declined')
                                    <span class="badge bg-danger">Declined</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No recent orders</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
