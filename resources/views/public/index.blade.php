@extends('layouts.app')

@section('content')
<style>
    /* Premium Selection Cards */
    .selection-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .selection-card {
        background: #ffffff;
        border: 2px solid #f1f5f9;
        border-radius: 16px;
        padding: 1.25rem 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        position: relative;
        height: 110px;
    }
    /* Hover Effect */
    .selection-card:hover {
        border-color: #93c5fd;
        background: #f8fafc;
        transform: translateY(-4px);
        box-shadow: 0 10px 20px -5px rgba(148, 163, 184, 0.15);
    }
    .selection-card.active {
        background: #eff6ff;
        border-color: var(--primary);
        color: var(--primary);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }
    .selection-card i {
        font-size: 2rem;
        transition: transform 0.3s;
        color: #64748b;
    }
    .selection-card.active i { color: var(--primary); }
    .selection-card:hover i { transform: scale(1.1); }
    .selection-card span {
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.01em;
    }

    /* Refined Brand Chips */
    .brand-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.25rem;
        animation: slideDown 0.3s ease;
    }
    .brand-chip {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        color: #475569;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .brand-chip:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: #f0f9ff;
    }
    .brand-chip.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }

    /* Layout & Spacing */
    .hero-section {
        text-align: center;
        padding: 3rem 1rem 4rem;
    }
    .hero-title {
        font-size: 3.25rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.03em;
        background: linear-gradient(135deg, #0f172a 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1.25rem;
    }
    
    .main-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr; /* Golden-ish ratio for form vs sidebar */
        gap: 3rem;
        margin-bottom: 6rem;
        align-items: start;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Form Styles */
    .glass-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        box-shadow: 0 20px 40px -4px rgba(148, 163, 184, 0.1);
    }
    
    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    
    .input-field {
        width: 100%;
        padding: 1rem 1.25rem;
        border-radius: 12px;
        border: 2px solid #f1f5f9;
        background: #f8fafc;
        font-size: 1rem;
        transition: all 0.2s;
        color: #334155;
    }
    .input-field:focus {
        background: white;
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    /* Quantity Control */
    .qty-control {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 12px;
        padding: 4px;
        width: fit-content;
    }
    .qty-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        background: white;
        color: #334155;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .qty-btn:hover { background: #e2e8f0; }
    .qty-input {
        width: 60px; /* Increased width */
        text-align: center;
        background: transparent;
        border: none;
        font-weight: 700;
        font-size: 1.1rem;
        color: #0f172a;
        padding: 0;      /* Ensure no padding causing shifts */
        height: 36px;    /* Match button height */
        line-height: 36px; /* Vertically center text */
        appearance: textfield; /* Remove spinner buttons on some browsers */
        -moz-appearance: textfield;
    }
    .qty-input::-webkit-outer-spin-button,
    .qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Animation */
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* RESPONSIVE */
    @media (max-width: 1024px) {
        .main-grid {
            grid-template-columns: 1fr;
            gap: 3rem;
            max-width: 700px;
        }
        .hero-title { font-size: 2.75rem; }
    }

    .hero-btn {
        padding: 1rem 2.5rem;
        font-size: 1.05rem;
        border-radius: 99px;
        box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
    }
    .hero-btn-secondary {
        background: white;
        color: #334155;
        padding: 1rem 2.5rem;
        font-size: 1.05rem;
        border-radius: 99px;
        border: 1px solid #cbd5e1;
    }

    @media (max-width: 640px) {
        .hero-title { font-size: 2.25rem; }
        .selection-grid { grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }
        .selection-card { height: auto; padding: 1rem 0.25rem; }
        .selection-card i { font-size: 1.5rem; }
        .glass-panel { padding: 1.5rem !important; }
        
        /* Adjusted Button Sizes for Mobile */
        .hero-btn, .hero-btn-secondary {
            padding: 0.75rem 1.5rem; /* Smaller padding */
            font-size: 0.95rem;      /* Smaller text */
            width: 48%;              /* Fit side by side */
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }
    }

    /* Checkbox Card Style (New) */
    .checkbox-card {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
        color: #475569;
        font-weight: 500;
    }
</style>

<div class="hero-section">
    <h1 class="hero-title">
        Solusi Service Komputer<br>
        <span class="typewriter-text" style="color: var(--primary);"></span><span class="cursor">|</span>
    </h1>
    <p class="text-muted" style="font-size: 1.125rem; max-width: 650px; margin: 0 auto 2.5rem; line-height: 1.7; color: #64748b;">
        Pantau perbaikan gadget Anda secara realtime. Transparan, Cepat, dan Professional.
        Kami memberikan pengalaman terbaik untuk kebutuhan IT Anda.
    </p>
    
    <div style="display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
        <a href="#queue-form" class="btn btn-primary hero-btn">Mulai Service</a>
        <a href="#track-form" class="btn hero-btn-secondary">Cek Status</a>
    </div>
</div>

<div class="main-grid">
    
    <!-- LEFT COLUMN: Form Antrian -->
    <div id="queue-form" class="glass-panel" style="padding: 3rem;">
        <!-- Card Header -->
        <div style="display: flex; align-items: flex-start; gap: 1.25rem; margin-bottom: 2.5rem;">
            <div style="width: 52px; height: 52px; background: #eff6ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-plus"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; color: #0f172a;">Buat Tiket Baru</h3>
                <p style="margin: 0.25rem 0 0; font-size: 0.95rem; color: #64748b; line-height: 1.5;">Dapatkan nomor antrian service Anda dengan mudah.</p>
            </div>
        </div>
        
        <form action="{{ route('queue.store') }}" method="POST" id="newTicketForm">
            @csrf
            <input type="hidden" name="device" id="finalDeviceInput" required>

            <div style="display: flex; flex-direction: column; gap: 2rem; margin-bottom: 2rem;">
                <!-- Personal Info Stack -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div style="grid-column: span 2;">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="customer_name" class="input-field" placeholder="Contoh: Budi Santoso" required>
                    </div>
                    <div>
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" name="phone" class="input-field" placeholder="0812..." required>
                    </div>
                    <div>
                        <label class="form-label">Email (Opsional)</label>
                        <input type="email" name="email" class="input-field" placeholder="nama@email.com">
                    </div>
                </div>

                <!-- Device Type Stack -->
                <div>
                    <label class="form-label" style="font-size: 0.95rem; margin-bottom: 1rem;">Pilih Jenis Perangkat</label>
                    <div class="selection-grid" id="typeSelection">
                        <div class="selection-card" onclick="selectType('Laptop', this)">
                            <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                                <i class="fas fa-laptop" style="font-size: 1.25rem;"></i>
                            </div>
                            <span>Laptop</span>
                        </div>
                        <div class="selection-card" onclick="selectType('Komputer', this)">
                            <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                                <i class="fas fa-desktop" style="font-size: 1.25rem;"></i>
                            </div>
                            <span>PC / Komputer</span>
                        </div>
                        <div class="selection-card" onclick="selectType('Printer', this)">
                            <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 0.5rem;">
                                <i class="fas fa-print" style="font-size: 1.25rem;"></i>
                            </div>
                            <span>Printer</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Brand Selection Area (Collapsible) -->
            <!-- Brand Selection Area (Collapsible) -->
            <div id="brandSection" style="display: none; background: #f8fafc; padding: 2rem; border-radius: 16px; border: 1px dashed #cbd5e1; margin-bottom: 2rem; animation: slideDown 0.3s ease;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-tag text-muted"></i>
                    <label class="form-label" style="margin: 0;">Pilih Merek Perangkat</label>
                </div>
                
                <div id="brandContainer" class="brand-grid"></div>
                
                <input type="text" id="otherBrandInput" class="input-field" placeholder="Sebutkan merek perangkat..." 
                    style="display: none; margin-top: 1rem; border-style: dashed;">
            </div>

            <!-- Kelengkapan (Accessories) -->
            <div style="margin-bottom: 2rem;">
                <label class="form-label" style="margin-bottom: 0.75rem;">Kelengkapan</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 0.75rem;">
                    <label class="checkbox-card">
                        <input type="checkbox" name="accessories[]" value="Unit Only" checked>
                        <span>Unit Only</span>
                    </label>
                    <label class="checkbox-card">
                        <input type="checkbox" name="accessories[]" value="Charger">
                        <span>Charger</span>
                    </label>
                    <label class="checkbox-card">
                        <input type="checkbox" name="accessories[]" value="Tas">
                        <span>Tas/Case</span>
                    </label>
                    <label class="checkbox-card">
                        <input type="checkbox" name="accessories[]" value="Box">
                        <span>Box/Dus</span>
                    </label>
                </div>
            </div>


            <!-- Quantity & Description -->
            <div style="margin-bottom: 2rem;">
                 <label class="form-label">Jumlah Unit</label>
                 <div class="qty-control">
                     <button type="button" class="qty-btn" onclick="decrementQty()">-</button>
                     <input type="number" name="quantity" id="qtyInput" value="1" min="1" class="qty-input" readonly>
                     <button type="button" class="qty-btn" onclick="incrementQty()">+</button>
                 </div>
            </div>

            <div style="margin-bottom: 2.5rem;">
                <label class="form-label">Deskripsi Masalah</label>
                <textarea name="complaint" rows="3" class="input-field" placeholder="Jelaskan kerusakan yang dialami sedetail mungkin..." required 
                    style="resize: vertical; min-height: 120px; line-height: 1.6;"></textarea>
            </div>
            
            <button type="submit" onclick="return validateForm()" class="btn btn-primary btn-block" style="padding: 1.25rem; font-size: 1.1rem; border-radius: 16px; font-weight: 700; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3);">
                Ambil Nomor Antrian <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i>
            </button>
        </form>
    </div>

    <!-- RIGHT COLUMN: Tracking & Features -->
    <div id="track-form" style="display: flex; flex-direction: column; gap: 2rem;">
        
        <!-- Tracking Card -->
        <div class="glass-panel" style="padding: 2.5rem; background: linear-gradient(135deg, #ffffff 60%, #eff6ff 100%); border-left: 4px solid var(--primary);">
            <div style="display: flex; align-items: flex-start; gap: 1.25rem; margin-bottom: 2rem;">
                <div style="width: 52px; height: 52px; background: #dbeafe; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #1d4ed8; font-size: 1.5rem; flex-shrink: 0;">
                    <i class="fas fa-search-location"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; color: #0f172a;">Lacak Service</h3>
                    <p style="margin: 0.25rem 0 0; font-size: 0.9rem; color: #64748b;">Cek status perbaikan Anda</p>
                </div>
            </div>
            
            <p style="margin-bottom: 2rem; color: #475569; font-size: 0.95rem; line-height: 1.6;">
                Masukan Kode Tiket (Contoh: <strong>SRV-240001</strong>) yang tertera pada nota tanda terima service Anda.
            </p>
            
            <form action="" method="GET" id="trackFormReal">
                <div style="margin-bottom: 1rem;">
                    <input type="text" id="ticketCodeInput" class="input-field" placeholder="KODE TIKET" 
                        style="font-size: 1.25rem; letter-spacing: 2px; text-transform: uppercase; text-align: center; font-weight: 700; color: #0f172a; padding: 1rem;">
                </div>
                <button type="button" onclick="submitTrack()" class="btn btn-primary btn-block" style="background: #0f172a; border-radius: 12px; padding: 1rem;">
                    <i class="fas fa-search" style="margin-right: 0.5rem;"></i> Cek Status Sekarang
                </button>
            </form>
        </div>

        <!-- Feature Cards Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="glass-panel" style="padding: 2rem 1.5rem; text-align: center; border: 1px solid #f0fdf4;">
                <div style="width: 48px; height: 48px; background: #dcfce7; color: #15803d; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.25rem; box-shadow: 0 4px 6px -1px rgba(22, 163, 74, 0.1);">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4 style="margin: 0; font-weight: 700; color: #166534; font-size: 1rem;">Garansi Resmi</h4>
                <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #64748b; line-height: 1.4;">Jaminan kualitas setiap perbaikan.</p>
            </div>
            <div class="glass-panel" style="padding: 2rem 1.5rem; text-align: center; border: 1px solid #fefce8;">
                <div style="width: 48px; height: 48px; background: #fef9c3; color: #b45309; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.25rem; box-shadow: 0 4px 6px -1px rgba(202, 138, 4, 0.1);">
                    <i class="fas fa-bolt"></i>
                </div>
                <h4 style="margin: 0; font-weight: 700; color: #92400e; font-size: 1rem;">Pengerjaan Cepat</h4>
                <p style="margin-top: 0.5rem; font-size: 0.85rem; color: #64748b; line-height: 1.4;">Estimasi waktu yang transparan.</p>
            </div>
        </div>
    </div>
</div>

<script>
    const brands = {
        'Laptop': ['Asus', 'Acer', 'Lenovo', 'HP', 'Dell', 'MacBook', 'MSI', 'Axioo', 'Toshiba', 'Samsung', 'Lainnya'],
        'Komputer': ['PC Rakitan Intel', 'PC Rakitan AMD', 'All-in-One', 'Mini PC', 'Server', 'Lainnya'],
        'Printer': ['Epson', 'Canon', 'HP', 'Brother', 'Fuji Xerox', 'Lainnya']
    };

    let selectedType = '';
    let selectedBrand = '';

    function selectType(type, element) {
        selectedType = type;
        selectedBrand = ''; 
        document.getElementById('finalDeviceInput').value = ''; 
        document.getElementById('otherBrandInput').style.display = 'none';

        // Styling Active Type
        document.querySelectorAll('.selection-card').forEach(el => el.classList.remove('active'));
        element.classList.add('active');

        // Show Brand Section
        const brandSection = document.getElementById('brandSection');
        const brandContainer = document.getElementById('brandContainer');
        
        brandSection.style.display = 'block';
        brandContainer.innerHTML = ''; // Clear prev

        // Populate Brands
        brands[type].forEach(brand => {
            const chip = document.createElement('div');
            chip.className = 'brand-chip';
            chip.innerText = brand;
            chip.onclick = () => selectBrand(brand, chip);
            brandContainer.appendChild(chip);
        });
    }

    function selectBrand(brand, element) {
        selectedBrand = brand;
        
        // Styling Active Brand
        document.querySelectorAll('.brand-chip').forEach(el => el.classList.remove('active'));
        element.classList.add('active');

        // Handle 'Lainnya'
        const otherInput = document.getElementById('otherBrandInput');
        if(brand === 'Lainnya') {
            otherInput.style.display = 'block';
            otherInput.focus();
            document.getElementById('finalDeviceInput').value = '';
        } else {
            otherInput.style.display = 'none';
            document.getElementById('finalDeviceInput').value = `${selectedType} - ${selectedBrand}`;
        }
    }

    // Capture manual input for 'Lainnya'
    document.getElementById('otherBrandInput').addEventListener('input', function(e) {
        if(selectedBrand === 'Lainnya') {
            document.getElementById('finalDeviceInput').value = `${selectedType} - ${e.target.value}`;
        }
    });

    // Accessories Logic
    // Accessories Logic
    function updateAccessoriesState() {
        const unitOnly = document.querySelector('input[value="Unit Only"]');
        const others = document.querySelectorAll('input[name="accessories[]"]:not([value="Unit Only"])');
        
        if (unitOnly.checked) {
            others.forEach(cb => {
                cb.checked = false;
                cb.disabled = true; // Disable input
                cb.parentElement.classList.add('disabled'); // Add disabled style
            });
        } else {
            others.forEach(cb => {
                cb.disabled = false;
                cb.parentElement.classList.remove('disabled');
            });
        }
    }

    // Init on load
    updateAccessoriesState();

    document.querySelectorAll('input[name="accessories[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const unitOnly = document.querySelector('input[value="Unit Only"]');
            
            if (this.value === 'Unit Only') {
                // If Unit Only clicked
                updateAccessoriesState();
            } else {
                // If others clicked, ensure Unit Only is unchecked (though it should be already if logic works)
                if (this.checked) {
                    unitOnly.checked = false;
                    // We don't need to disable Unit Only, just uncheck it.
                    // But wait, if Unit Only is unchecked, updateAccessoriesState() will enable others (which is what we want).
                }
            }
        });
    });

    function validateForm() {
        if(!document.getElementById('finalDeviceInput').value) {
            alert('Mohon pilih Jenis Perangkat dan Merek terlebih dahulu.');
            return false;
        }
        return true;
    }

    function submitTrack() {
        var code = document.getElementById('ticketCodeInput').value;
        if(code) {
            window.location.href = '/ticket/' + code;
        } else {
            alert('Mohon masukkan kode tiket.');
        }
    }

    // Typewriter Effect
    document.addEventListener('DOMContentLoaded', function() {
        const textElement = document.querySelector('.typewriter-text');
        const texts = ["Terpercaya & Modern", "Cepat & Professional", "Bergaransi Resmi", "Transparan & Aman"];
        let textIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typeSpeed = 100;

        function type() {
            const currentText = texts[textIndex];
            
            if (isDeleting) {
                textElement.textContent = currentText.substring(0, charIndex - 1);
                charIndex--;
                typeSpeed = 50; // Faster deleting
            } else {
                textElement.textContent = currentText.substring(0, charIndex + 1);
                charIndex++;
                typeSpeed = 100; // Normal typing
            }

            if (!isDeleting && charIndex === currentText.length) {
                isDeleting = true;
                typeSpeed = 2000; // Wait before deleting
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
                typeSpeed = 500; // Wait before typing next
            }

            setTimeout(type, typeSpeed);
        }

        type();
    });
    function incrementQty() {
        let input = document.getElementById('qtyInput');
        input.value = parseInt(input.value) + 1;
    }

    function decrementQty() {
        let input = document.getElementById('qtyInput');
        if(parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endsection
