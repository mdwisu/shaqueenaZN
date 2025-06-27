@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar -->
            <div class="card">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}"
                        class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="{{ route('admin.products') }}" class="list-group-item list-group-item-action">My Products</a>
                    <a href="{{ route('admin.products.create') }}" class="list-group-item list-group-item-action">Add New
                        Product</a>
                    <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">Orders</a>
                    <a href="{{ route('admin.payments.index') }}" class="list-group-item list-group-item-action">Payment
                        Verification</a>
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
                                    <h5>My Products</h5>
                                    <h2>{{ $totalProducts ?? 0 }}</h2>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('admin.products') }}" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>Total Sales</h5>
                                    <h2>{{ $totalSales ?? 0 }}</h2>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-primary">View Orders</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>Revenue</h5>
                                    <h2>Rp {{ number_format($revenue ?? 0, 0, ',', '.') }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">Total Revenue</div>
                                <div class="card-body">
                                    <h5 class="card-title">Rp {{ number_format($revenue, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-header">Total Profit</div>
                                <div class="card-body">
                                    <h5 class="card-title">Rp {{ number_format($profit, 2, ',', '.') }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">Total Products</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $totalProducts }}</h5>
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
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="btn btn-sm btn-info">View</a>
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
