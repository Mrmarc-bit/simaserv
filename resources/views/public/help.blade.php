@extends('layouts.app')

@section('content')
<div class="glass" style="max-width: 800px; margin: 0 auto; padding: 2.5rem;">
    <h2 class="text-center" style="margin-bottom: 2rem; color: var(--primary-dark);">Pusat Bantuan</h2>

    <div style="display: grid; gap: 2rem;">
        <!-- FAQ Section -->
        <div class="faq-section">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">Pertanyaan Umum (FAQ)</h3>
            
            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Bagaimana cara cek status service?</h4>
                <p class="text-muted">Anda bisa mengecek status service dengan memasukkan Kode Tiket yang Anda terima via WhatsApp/Email pada halaman "Lacak Status" atau langsung klik link yang dikirimkan.</p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Berapa lama estimasi pengerjaan service?</h4>
                <p class="text-muted">Estimasi pengerjaan tergantung tingkat kerusakan. Pengecekan awal memakan waktu 1-2 hari kerja. Kami akan menginformasikan biaya dan waktu pengerjaan setelah pengecekan selesai.</p>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <h4 style="font-size: 1rem; color: var(--text-main); margin-bottom: 0.5rem;">Apakah ada garansi?</h4>
                <p class="text-muted">Ya, kami memberikan garansi service sesuai dengan jenis perbaikan. Detail garansi akan tertera pada nota pengambilan.</p>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section" style="border-top: 1px solid #e2e8f0; padding-top: 2rem;">
            <h3 style="font-size: 1.25rem; margin-bottom: 1rem;">Butuh Bantuan Lebih Lanjut?</h3>
            <p class="text-muted" style="margin-bottom: 1.5rem;">Hubungi Customer Service kami melalui WhatsApp untuk respon cepat.</p>
            
            <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fab fa-whatsapp" style="font-size: 1.2rem;"></i> Chat WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
