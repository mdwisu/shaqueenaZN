# Task List: Implementasi Ongkos Kirim + Notifikasi Pembelian

## 📋 Overview
Implementasi sistem **estimasi ongkir + konfirmasi admin** dan **sistem notifikasi pembelian** untuk web e-commerce shaqueenaZN.

## ✅ Progress Completed

### 1. Database & Model Updates
- [x] **Update migration** untuk shipping fields baru
  - File: `database/migrations/2025_08_21_041409_add_shipping_cost_to_orders_table.php`
  - Fields: `estimated_shipping_cost`, `final_shipping_cost`, `shipping_confirmed`, `shipping_notes`
  
- [x] **Update Order model** 
  - File: `app/Models/Order.php`
  - Tambah fields baru ke `$fillable`

### 2. Controller Updates (In Progress)
- [x] **Update CheckoutController** - method `index()` dan `process()`
  - File: `app/Http/Controllers/CheckoutController.php`
  - Ubah dari `calculateShippingCost()` ke `calculateEstimatedShippingCost()`
  - Update order creation untuk save estimasi shipping

## 🔄 Tasks Remaining

### 3. Finalisasi CheckoutController
- [ ] **Selesaikan update CheckoutController**
  - Update method `calculateShippingCost()` jadi `calculateEstimatedShippingCost()`
  - Tambah logic untuk handle estimated vs final shipping cost

### 4. Frontend Updates
- [ ] **Update checkout view**
  - File: `resources/views/checkout/index.blade.php`
  - Ubah tampilan dari "Ongkos Kirim" jadi "Estimasi Ongkos Kirim"
  - Tambah disclaimer bahwa ongkir akan dikonfirmasi admin
  - Update total calculation (subtotal + estimasi, bukan final total)

### 5. Admin Interface untuk Konfirmasi Shipping
- [ ] **Update admin order view**
  - File: `resources/views/admin/orders/show.blade.php`
  - Tambah form untuk confirm shipping cost
  - Show estimated vs final shipping cost
  - Tambah field shipping notes

- [ ] **Update AdminOrderController** 
  - File: `app/Http/Controllers/Admin/OrderController.php`
  - Tambah method `confirmShipping()`
  - Handle update final_shipping_cost dan shipping_confirmed

### 6. Customer Interface Updates
- [ ] **Update customer order view**
  - File: `resources/views/customer/orders/show.blade.php`
  - Show shipping status: "Menunggu Konfirmasi Ongkir" / "Ongkir Dikonfirmasi"
  - Display estimated vs final shipping cost
  - Show shipping notes dari admin

### 7. Order Status Enhancement
- [ ] **Update order status enum**
  - File: `database/migrations/2025_04_06_161247_create_orders_table.php`
  - Pertimbangkan tambah status: 'shipping_pending', 'shipping_confirmed'
  - Atau gunakan shipping_confirmed boolean yang sudah ada

### 8. Notification System
- [ ] **Buat Notification model & migration**
  ```bash
  php artisan make:model Notification -m
  ```
  - Fields: user_id, type, title, message, read_at, data (JSON)

- [ ] **Buat NotificationController**
  - Method untuk mark as read, get unread notifications
  - API endpoints untuk real-time notifications

- [ ] **Update layout untuk show notifications**
  - File: `resources/views/layouts/app.blade.php` atau `main.blade.php`
  - Badge notifikasi di navbar
  - Dropdown list notifikasi

### 9. Email Notifications
- [ ] **Buat Mail classes**
  ```bash
  php artisan make:mail NewOrderNotification --markdown=emails.orders.new-order
  php artisan make:mail ShippingConfirmedNotification --markdown=emails.orders.shipping-confirmed
  ```

- [ ] **Update CheckoutController** 
  - Send email ke admin saat ada order baru
  - Send email ke customer saat shipping dikonfirmasi

### 10. Testing & Migration
- [ ] **Run migration**
  ```bash
  php artisan migrate
  ```

- [ ] **Test flow lengkap:**
  1. Customer checkout dengan estimasi ongkir
  2. Admin dapat notifikasi order baru
  3. Admin konfirmasi ongkir final
  4. Customer dapat notifikasi ongkir dikonfirmasi
  5. Customer bayar total final (subtotal + ongkir final)

## 🔧 Technical Notes

### Shipping Cost Calculation Logic
```php
private function calculateEstimatedShippingCost($subtotal) {
    // Zona Jakarta/sekitar
    if ($subtotal >= 500000) return 0;      // Gratis
    if ($subtotal >= 200000) return 15000;  // Rp 15k
    if ($subtotal >= 100000) return 25000;  // Rp 25k
    return 35000;                           // Rp 35k
    
    // TODO: Tambah logic zona lain berdasarkan shipping_city
}
```

### Flow Bisnis Process
1. **Customer Checkout**: Lihat estimasi ongkir
2. **Order Created**: Status = pending, shipping_confirmed = false
3. **Admin Notification**: Email + in-app notification
4. **Admin Confirm**: Set final_shipping_cost, shipping_confirmed = true
5. **Customer Notification**: Ongkir dikonfirmasi, total final diupdate
6. **Payment**: Customer bayar total final

## 📁 Files Modified/Created

### Modified Files:
- `database/migrations/2025_08_21_041409_add_shipping_cost_to_orders_table.php`
- `app/Models/Order.php`
- `app/Http/Controllers/CheckoutController.php` (partial)

### Files to Modify:
- `resources/views/checkout/index.blade.php`
- `resources/views/admin/orders/show.blade.php`
- `resources/views/customer/orders/show.blade.php`
- `app/Http/Controllers/Admin/OrderController.php`

### Files to Create:
- `app/Models/Notification.php`
- `app/Http/Controllers/NotificationController.php`
- `app/Mail/NewOrderNotification.php`
- `app/Mail/ShippingConfirmedNotification.php`
- `resources/views/emails/orders/new-order.blade.php`
- `resources/views/emails/orders/shipping-confirmed.blade.php`

## 🚀 Cara Melanjutkan

1. **Lanjutkan dari CheckoutController** yang sedang dikerjakan
2. **Jalankan migration** setelah semua perubahan selesai
3. **Test step by step** setiap fitur yang diimplementasi
4. **Update dokumentasi** jika diperlukan

---
*Generated: 2025-08-21*
*Status: 30% Complete*