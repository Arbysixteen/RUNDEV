<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Dashboard Peserta - RUN DEV</title>
        <meta name="description" content="Dashboard peserta RUN DEV - Event Lari untuk Developer">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Styles -->
        <style>
            body, html {
                margin: 0;
                padding: 0;
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
                min-height: 100vh;
            }
            
            .dashboard {
                padding: 20px;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .header {
                background: white;
                padding: 30px;
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                margin-bottom: 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
            }
            
            .header-content h1 {
                font-size: 2.5rem;
                color: #4f46e5;
                margin: 0 0 10px 0;
            }
            
            .header-content p {
                color: #6b7280;
                font-size: 1.1rem;
                margin: 0;
            }
            
            .header-actions {
                display: flex;
                gap: 15px;
                align-items: center;
            }
            
            .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 8px;
                font-weight: 500;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                transition: all 0.3s;
                font-size: 0.9rem;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }
            
            .btn-secondary {
                background: #f3f4f6;
                color: #374151;
                border: 1px solid #d1d5db;
            }
            
            .btn-danger {
                background: #ef4444;
                color: white;
            }
            
            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            }
            
            .dashboard-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-bottom: 30px;
            }
            
            .card {
                background: white;
                padding: 30px;
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            
            .card h2 {
                font-size: 1.5rem;
                color: #1f2937;
                margin: 0 0 20px 0;
                padding-bottom: 15px;
                border-bottom: 2px solid #f3f4f6;
            }
            
            .biodata-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px 0;
                border-bottom: 1px solid #f3f4f6;
            }
            
            .biodata-item:last-child {
                border-bottom: none;
            }
            
            .biodata-label {
                font-weight: 500;
                color: #6b7280;
                flex: 1;
            }
            
            .biodata-value {
                font-weight: 600;
                color: #1f2937;
                flex: 2;
                text-align: right;
            }
            
            .status-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.875rem;
                font-weight: 500;
            }
            
            .status-pending {
                background: #fef3c7;
                color: #92400e;
            }
            
            .status-paid {
                background: #d1fae5;
                color: #065f46;
            }
            
            .participant-id {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px;
                border-radius: 8px;
                text-align: center;
                margin-bottom: 20px;
            }
            
            .participant-id h3 {
                margin: 0 0 5px 0;
                font-size: 1rem;
                opacity: 0.9;
            }
            
            .participant-id .id-number {
                font-size: 1.5rem;
                font-weight: 700;
                letter-spacing: 2px;
            }
            
            .race-info {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 20px;
                border-radius: 12px;
                margin-bottom: 20px;
            }
            
            .race-info h3 {
                margin: 0 0 10px 0;
                font-size: 1.2rem;
            }
            
            .race-info p {
                margin: 0;
                opacity: 0.9;
            }
            
            .race-price {
                font-size: 1.5rem;
                font-weight: 700;
                margin-top: 10px;
            }
            
            .info-box {
                background: #f0f5ff;
                padding: 20px;
                border-radius: 8px;
                color: #1e40af;
                font-size: 0.9rem;
                line-height: 1.6;
            }
            
            .info-box h4 {
                margin-top: 0;
                margin-bottom: 10px;
                color: #1e40af;
            }
            
            .info-box ul {
                padding-left: 20px;
                margin: 0;
            }
            
            @media (max-width: 768px) {
                .dashboard-grid {
                    grid-template-columns: 1fr;
                    gap: 20px;
                }
                
                .header {
                    flex-direction: column;
                    text-align: center;
                    gap: 20px;
                }
                
                .header-actions {
                    width: 100%;
                    justify-content: center;
                }
                
                .biodata-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 5px;
                }
                
                .biodata-value {
                    text-align: left;
                }
            }
        </style>
    </head>
    <body>
        <div class="dashboard">
            <!-- Header -->
            <div class="header">
                <div class="header-content">
                    <h1>Dashboard Peserta</h1>
                    <p>Selamat datang, {{ $participantData['namaLengkap'] }}!</p>
                </div>
                <div class="header-actions">
                    <a href="/" class="btn btn-secondary">Beranda</a>
                    <form action="/peserta/logout" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Biodata Peserta -->
                <div class="card">
                    <h2>📋 Biodata Peserta</h2>
                    
                    <div class="participant-id">
                        <h3>ID Peserta</h3>
                        <div class="id-number">{{ $participantData['idPeserta'] }}</div>
                    </div>
                    
                    <div class="biodata-item">
                        <div class="biodata-label">Nama Lengkap</div>
                        <div class="biodata-value">{{ $participantData['namaLengkap'] }}</div>
                    </div>
                    
                    <div class="biodata-item">
                        <div class="biodata-label">Email</div>
                        <div class="biodata-value">{{ $participantData['email'] }}</div>
                    </div>
                    
                    <div class="biodata-item">
                        <div class="biodata-label">Nomor WhatsApp</div>
                        <div class="biodata-value">{{ $participantData['nomorWA'] }}</div>
                    </div>
                    
                    <div class="biodata-item">
                        <div class="biodata-label">Ukuran Baju</div>
                        <div class="biodata-value">{{ $participantData['ukuranBaju'] }}</div>
                    </div>
                    
                    <div class="biodata-item">
                        <div class="biodata-label">Tanggal Daftar</div>
                        <div class="biodata-value">
                            {{ \Carbon\Carbon::parse($participantData['registrationDate'])->format('d F Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Informasi Lomba -->
                <div class="card">
                    <h2>🏃‍♂️ Informasi Lomba</h2>
                    
                    <div class="race-info">
                        <h3>{{ $participantData['kategoriLomba'] }}</h3>
                        <p>Kategori yang Anda pilih</p>
                        <div class="race-price">
                            @if($participantData['kategoriLomba'] == '5K')
                                Rp 150.000
                            @elseif($participantData['kategoriLomba'] == '10K')
                                Rp 200.000
                            @else
                                Rp 300.000
                            @endif
                        </div>
                    </div>
                    
                    <div class="biodata-item">
                        <div class="biodata-label">Status Pembayaran</div>
                        <div class="biodata-value">
                            @if($participantData['pembayaran'] == 'lunas')
                                <span class="status-badge status-paid">✅ Lunas</span>
                            @else
                                <span class="status-badge status-pending">⏳ Belum Bayar</span>
                            @endif
                        </div>
                    </div>
                    
                    @if(isset($participantData['paymentDate']))
                    <div class="biodata-item">
                        <div class="biodata-label">Tanggal Pembayaran</div>
                        <div class="biodata-value">
                            {{ \Carbon\Carbon::parse($participantData['paymentDate'])->format('d F Y, H:i') }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="info-box">
                        <h4>Informasi Penting:</h4>
                        <ul>
                            @if($participantData['pembayaran'] != 'lunas')
                                <li>Silakan lakukan pembayaran untuk mengkonfirmasi pendaftaran</li>
                                <li>Hubungi panitia untuk informasi pembayaran</li>
                            @else
                                <li>Pembayaran Anda sudah dikonfirmasi</li>
                                <li>Anda akan menerima race pack sebelum hari lomba</li>
                            @endif
                            <li>Simpan ID Peserta untuk keperluan administrasi</li>
                            <li>Pastikan datang 30 menit sebelum start lomba</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Event Information -->
            <div class="card">
                <h2>📅 Informasi Event RUN DEV</h2>
                <div class="dashboard-grid" style="margin-bottom: 0;">
                    <div>
                        <div class="biodata-item">
                            <div class="biodata-label">Nama Event</div>
                            <div class="biodata-value">RUN DEV - Event Lari untuk Developer</div>
                        </div>
                        <div class="biodata-item">
                            <div class="biodata-label">Tanggal Event</div>
                            <div class="biodata-value">Coming Soon</div>
                        </div>
                        <div class="biodata-item">
                            <div class="biodata-label">Lokasi</div>
                            <div class="biodata-value">Jakarta, Indonesia</div>
                        </div>
                    </div>
                    <div>
                        <div class="biodata-item">
                            <div class="biodata-label">Kategori Tersedia</div>
                            <div class="biodata-value">5K, 10K, Half Marathon</div>
                        </div>
                        <div class="biodata-item">
                            <div class="biodata-label">Target Peserta</div>
                            <div class="biodata-value">Developer & Tech Enthusiast</div>
                        </div>
                        <div class="biodata-item">
                            <div class="biodata-label">Kontak Panitia</div>
                            <div class="biodata-value">info@rundev.com</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Auto refresh status pembayaran setiap 30 detik
            setInterval(function() {
                // Optional: Add AJAX call to check payment status
                console.log('Checking payment status...');
            }, 30000);
            
            // Copy ID Peserta to clipboard
            document.querySelector('.id-number').addEventListener('click', function() {
                navigator.clipboard.writeText(this.textContent).then(function() {
                    alert('ID Peserta berhasil disalin!');
                });
            });
        </script>
    </body>
</html>
