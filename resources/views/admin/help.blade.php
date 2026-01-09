@extends('layouts.admin')

@section('content')
<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="font-size: 1.875rem; color: #1e3a8a; font-weight: 700; margin-bottom: 0.5rem;">Help Center</h1>
        <p style="color: #64748b;">Panduan dan bantuan penggunaan sistem admin.</p>
    </div>
</div>

<div class="content-container">
    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
        
        <h3 style="color: #1e40af; margin-bottom: 1.5rem; font-size: 1.25rem;">Panduan Singkat</h3>

        <div style="display: grid; gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <!-- Card 1 -->
            <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="width: 40px; height: 40px; background: #dbeafe; color: #1d4ed8; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 1rem;">
                    <i class="fas fa-plus"></i>
                </div>
                <h4 style="font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600;">Membuat Service Baru</h4>
                <p style="color: #64748b; font-size: 0.9rem; line-height: 1.5;">Input data pelanggan seperti nama, telepon, dan tipe perangkat di halaman <strong>Dashboard</strong> atau <strong>Services</strong>. Sistem akan otomatis membuat Tiket ID.</p>
            </div>

            <!-- Card 2 -->
            <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="width: 40px; height: 40px; background: #dbeafe; color: #1d4ed8; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 1rem;">
                    <i class="fas fa-edit"></i>
                </div>
                <h4 style="font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600;">Update Status</h4>
                <p style="color: #64748b; font-size: 0.9rem; line-height: 1.5;">Di halaman Detail Service, ubah status dari <em>Menunggu</em> ke <em>Diperiksa</em>, <em>Diperbaiki</em>, lalu <em>Selesai</em>. Sistem akan mengirim notifikasi WHatsApp otomatis.</p>
            </div>

            <!-- Card 3 -->
            <div style="padding: 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div style="width: 40px; height: 40px; background: #dbeafe; color: #1d4ed8; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 1rem;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h4 style="font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600;">Cetak Invoice</h4>
                <p style="color: #64748b; font-size: 0.9rem; line-height: 1.5;">Klik tombol "Invoice" pada detail service untuk mencetak nota pembayaran/pengambilan barang untuk pelanggan.</p>
            </div>
        </div>

        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
             <h3 style="color: #1e40af; margin-bottom: 1rem; font-size: 1.25rem;">Kontak Administrator Sistem</h3>
             <p style="color: #64748b; margin-bottom: 1rem;">Jika mengalami kendala teknis pada sistem, silakan hubungi developer.</p>
             <a href="mailto:support@simaserv.com" style="display: inline-block; background: #1e3a8a; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                <i class="fas fa-envelope"></i> Hubungi Support
             </a>
        </div>

    </div>
</div>
@endsection
