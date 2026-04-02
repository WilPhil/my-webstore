@component('mail::message')
# Pesanan Anda Telah Dikirim, {{ $sales_order->customer->full_name }} 🚚

Pesanan Anda dengan nomor **#{{ $sales_order->trx_id }}** telah dikirim dan sedang dalam perjalanan ke alamat tujuan.

---

## 📦 Informasi Pengiriman

- **Nomor Resi:** {{ $sales_order->shipping->receipt_number ?? 'Belum tersedia' }}
- **Kurir:** {{ $sales_order->shipping->courier }}
- **Layanan:** {{ $sales_order->shipping->service }}
- **Estimasi Tiba:** {{ $sales_order->shipping->estimated_delivery }}
- **Berat Paket:** {{ $sales_order->shipping->weight }} gram
- **Biaya Pengiriman:** {{ $sales_order->shipping_cost_formatted }}

---

## 📍 Alamat Pengiriman

{{ $sales_order->address_line }}
{{ $sales_order->destination->regency }}, {{ $sales_order->destination->province }}, {{ $sales_order->destination->postal_code }}

**Tanggal Pemesanan:**
{{ $sales_order->created_at_formatted }}

---

## 🛍️ Item yang Dipesan

@component('mail::table')
| Produk         | Qty | Harga Satuan | Subtotal   |
|----------------|-----|---------------|------------|
@foreach ($sales_order->items as $item)
| {{ $item->name }} | {{ $item->quantity }} | {{ $item->price_formatted }} | {{ $item->total_formatted }} |
@endforeach
@endcomponent

---

## 💰 Rincian Pembayaran

- **Subtotal**: {{ $sales_order->sub_total_formatted }}
- **Ongkir**: {{ $sales_order->shipping_cost_formatted }}
- **Total**: **{{ $sales_order->grand_total_formatted }}**

---

Terima kasih telah berbelanja bersama kami 🙏
Semoga pesanan Anda segera sampai dengan selamat.

@endcomponent
