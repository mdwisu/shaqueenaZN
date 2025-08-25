<x-mail::message>
# Ongkir Dikonfirmasi - #{{ $order->order_number }}

Halo {{ $order->user->name }},

Ongkos kirim untuk pesanan Anda telah dikonfirmasi:

**Detail Pesanan:**
- Order Number: #{{ $order->order_number }}
- Ongkos Kirim Final: 
  @if($order->final_shipping_cost == 0)
    **GRATIS**
  @else
    **Rp {{ number_format($order->final_shipping_cost, 0, ',', '.') }}**
  @endif
- Total Amount: **Rp {{ number_format($order->total_amount, 0, ',', '.') }}**

@if($order->shipping_notes)
**Catatan Admin:**
{{ $order->shipping_notes }}
@endif

Silakan lanjutkan pembayaran dengan total yang telah diperbarui.

<x-mail::button :url="url('/customer/orders/' . $order->id)">
Lihat Detail Pesanan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
