@component('mail::message')
# Pesanan Dibatalkan, {{ $sales_order->customer->full_name }} 😔

Pesanan Anda dengan nomor **#{{ $sales_order->trx_id }}** telah dibatalkan dan tidak akan diproses lebih lanjut.

---

## 📄 Ringkasan Pesanan:

**Alamat Pengiriman:**
{{ $sales_order->address_line }}
{{ $sales_order->destination->regency }}, {{ $sales_order->destination->province }}, {{ $sales_order->destination->postal_code }}

**Tanggal Pemesanan:**
{{ $sales_order->created_at_formatted }}

---

## 🛍️ Item dalam Pesanan

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

Jika ini terjadi karena kesalahan atau Anda ingin memesan ulang, silakan hubungi tim kami.

Terima kasih telah mempercayai kami 🙏
Kami berharap dapat membantu Anda di kesempatan berikutnya.

@endcomponent
