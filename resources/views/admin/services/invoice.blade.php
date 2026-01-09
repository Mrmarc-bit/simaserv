<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $service->ticket_code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #f3f4f6;
            color: #1f2937;
            -webkit-print-color-adjust: exact;
        }
        .invoice-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 3rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }
        /* Aesthetic decorative circle */
        .invoice-container::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, #5b2eea 0%, #8b5cf6 100%);
            border-radius: 50%;
            opacity: 0.1;
            z-index: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
            z-index: 1;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .brand-icon {
            width: 48px;
            height: 48px;
            background: #5b2eea;
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .brand-text h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #111827;
        }
        .brand-text p {
            margin: 0;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        .invoice-number {
            font-size: 1.25rem;
            font-weight: 700;
            font-family: monospace;
            color: #111827;
        }

        .bill-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }
        .bill-to h3, .bill-info h3 {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        .bill-data {
            font-size: 1rem;
            font-weight: 500;
            color: #1f2937;
        }
        .bill-data div { margin-bottom: 0.25rem; }
        .bill-data i { width: 20px; color: #9ca3af; }

        .service-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .service-table th {
            text-align: left;
            padding: 1rem;
            background: #f9fafb;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }
        .service-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .price-col { text-align: right; font-family: monospace; font-weight: 600; }
        .total-row td {
            border-bottom: none;
            padding-top: 1.5rem;
        }
        .grand-total {
            font-size: 1.5rem;
            color: #5b2eea;
            font-weight: 700;
        }

        .footer {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px dashed #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .terms {
            font-size: 0.75rem;
            color: #9ca3af;
            max-width: 60%;
        }
        .sign {
            text-align: center;
        }
        .sign-line {
            width: 150px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 0.5rem;
            height: 40px;
        }
        .sign-text {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
        }

        .status-stamp {
            position: absolute;
            top: 40%;
            right: 10%;
            font-size: 4rem;
            font-weight: 900;
            color: {{ $service->payment_status == 'LUNAS' ? '#16a34a' : '#ef4444' }};
            opacity: 0.1;
            transform: rotate(-15deg);
            border: 5px solid currentColor;
            padding: 0.5rem 2rem;
            border-radius: 10px;
            pointer-events: none;
        }

        @media print {
            body { background: white; }
            .invoice-container { box-shadow: none; margin: 0; padding: 2rem; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="position: fixed; top: 1rem; right: 1rem; z-index: 100;">
        <button onclick="window.print()" style="background: #1f2937; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; cursor: pointer; font-family: inherit; font-weight: 600;">
            <i class="fas fa-print"></i> Print
        </button>
    </div>

    <div class="invoice-container">
        <!-- Status Stamp -->
        @if($service->payment_status == 'LUNAS')
            <div class="status-stamp">LUNAS</div>
        @else
            <div class="status-stamp" style="color: #ef4444; border-color: #ef4444;">BELUM LUNAS</div>
        @endif

        <div class="header">
            <div class="brand">
                <div class="brand-icon"><i class="fas fa-bolt"></i></div>
                <div class="brand-text">
                    <h1>Indiana Computer</h1>
                    <p>Professional Laptop Repair Service</p>
                </div>
            </div>
            <div class="invoice-details">
                <span class="invoice-badge">INVOICE</span>
                <div class="invoice-number">#{{ $service->ticket_code }}</div>
                <div style="font-size: 0.85rem; color: #6b7280; margin-top: 0.25rem;">{{ now()->format('d M Y') }}</div>
            </div>
        </div>

        <div class="bill-grid">
            <div class="bill-to">
                <h3>Bill To</h3>
                <div class="bill-data">
                    <div style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $service->customer_name }}</div>
                    <div><i class="fas fa-phone"></i> {{ $service->phone }}</div>
                    <div><i class="fas fa-laptop"></i> {{ $service->device }} <span style="font-size: 0.85em; background: #e5e7eb; padding: 1px 6px; border-radius: 4px;">x{{ $service->quantity }}</span></div>
                </div>
            </div>
            <div class="bill-info">
                <h3>Complaint & Note</h3>
                <div class="bill-data" style="font-size: 0.95rem; line-height: 1.5;">
                    <strong style="color: #5b2eea;">Issue:</strong><br>
                    "{{ $service->complaint }}"
                    @if($service->damage_note)
                    <br><br>
                    <strong style="color: #64748b;">Tech Note:</strong><br>
                    {{ $service->damage_note }}
                    @endif
                </div>
            </div>
        </div>

        <table class="service-table">
            <thead>
                <tr>
                    <th style="width: 60%;">Description</th>
                    <th style="text-align: right;">Cost</th>
                </tr>
            </thead>
            <tbody>
                <!-- Base Service -->
                <tr>
                    <td>
                        <div style="font-weight: 600;">Service Check & Diagnosis</div>
                    </td>
                    <td class="price-col">Included</td>
                </tr>
                
                <!-- Items -->
                @forelse($service->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td class="price-col">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center; color: #9ca3af; font-style: italic; padding: 2rem;">No additional parts/services added yet.</td>
                </tr>
                @endforelse

                <tr class="total-row">
                    <td style="text-align: right; font-weight: 600; padding-right: 2rem;">Total Amount</td>
                    <td class="price-col grand-total">Rp {{ number_format($service->total_price, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Payment Details (if needed) -->
        <div style="background: #f9fafb; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; font-size: 0.9rem; color: #4b5563;">
            <div style="display: flex; gap: 2rem;">
                <div>
                    <strong>Payment Method:</strong> {{ $service->payment_method ?? '-' }}
                </div>
                <div>
                    <strong>Payment Status:</strong> {{ $service->payment_status }}
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="terms">
                <strong>Terms & Conditions:</strong><br>
                Garansi 30 hari untuk kerusakan yang sama. Barang yang tidak diambil lebih dari 90 hari bukan menjadi tanggung jawab kami.
            </div>
            <div class="sign">
                <div class="sign-line"></div>
                <div class="sign-text">Authorized Signature</div>
            </div>
        </div>
    </div>
</body>
</html>
