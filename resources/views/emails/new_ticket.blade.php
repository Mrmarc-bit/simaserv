<!DOCTYPE html>
<html>
<head>
    <title>Tiket Service Baru</title>
</head>
<body style="font-family: sans-serif; padding: 20px;">
    <h2>Halo, {{ $service->customer_name }}</h2>
    <p>Terima kasih telah mempercayakan service perangkat Anda kepada kami.</p>
    
    <div style="background: #f0f9ff; padding: 20px; border-radius: 10px; border: 1px solid #bae6fd; margin: 20px 0;">
        <h3 style="margin-top: 0; color: #0284c7;">Kode Tiket: {{ $service->ticket_code }}</h3>
        <p><strong>Perangkat:</strong> {{ $service->device }}</p>
        <p><strong>Keluhan:</strong> {{ $service->complaint }}</p>
        <p><strong>Keluhan:</strong> {{ $service->complaint }}</p>
    </div>

    <p>Pantau status perbaikan Anda kapan saja melalui tombol di bawah ini:</p>
    
    <a href="{{ route('public.ticket.show', $service->ticket_code) }}" style="background: #3b82f6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Cek Status Service</a>

    <p style="margin-top: 30px; font-size: 0.9em; color: #64748b;">
        Terima Kasih,<br>
        ServicePoint Team
    </p>
</body>
</html>
