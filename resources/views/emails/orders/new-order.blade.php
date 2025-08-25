<x-mail::message>
# Pesanan Baru - #{{ $order->order_number }}

Halo Admin,

Ada pesanan baru yang perlu diproses:

**Detail Pesanan:**
- Order Number: #{{ $order->order_number }}
- Customer: {{ $order->user->name }} ({{ $order->user->email }})
- Total Amount: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
- Estimasi Ongkir: Rp {{ number_format($order->estimated_shipping_cost, 0, ',', '.') }}
- Status: {{ ucfirst($order->status) }}

**Alamat Pengiriman:**
{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zipcode }}
Phone: {{ $order->shipping_phone }}

<x-mail::button :url="url('/admin/orders/' . $order->id)">
Lihat Detail Pesanan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
