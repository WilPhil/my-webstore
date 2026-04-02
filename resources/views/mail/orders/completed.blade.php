@component('mail::message')
# Pesanan Selesai, {{ $sales_order->customer->full_name }} ✅

Pesanan Anda dengan nomor **#{{ $sales_order->trx_id }}** telah berhasil diselesaikan dan telah kami kirimkan ke alamat tujuan.

---

## 🧾 Ringkasan Pesanan:

**Alamat Pengiriman:**
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
Semoga produk kami bermanfaat untuk Anda. Sampai jumpa di pesanan berikutnya!

@endcomponent
