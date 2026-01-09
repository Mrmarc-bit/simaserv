<!DOCTYPE html>
<html>
<head>
    <title>Service Selesai</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
        <h2 style="color: #2563eb;">Kabar Gembira! Service Selesai 🛠️</h2>
        
        <p>Halo <strong>{{ $service->customer_name }}</strong>,</p>
        
        <p>Perangkat Anda dengan detail berikut telah <strong>SELESAI</strong> diperbaiki dan siap untuk diambil.</p>
        
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Kode Tiket:</strong> {{ $service->ticket_code }}</p>
            <p style="margin: 5px 0;"><strong>Perangkat:</strong> {{ $service->device }}</p>
            <p style="margin: 5px 0;"><strong>Total Biaya:</strong> Rp {{ number_format($service->total_price, 0, ',', '.') }}</p>
        </div>

        <p>Silakan datang ke toko kami untuk melakukan pembayaran dan pengambilan unit. Jangan lupa membawa nota tanda terima service Anda.</p>
        
        <p>
            <a href="{{ route('public.ticket.show', $service->ticket_code) }}" style="display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px;">Cek Detail Tiket</a>
        </p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 30px 0;">
        <p style="font-size: 0.9em; color: #666;">Terima kasih telah menggunakan jasa service kami.<br>SIMASERV Team</p>
    </div>
</body>
</html>
