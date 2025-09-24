<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran RUN DEV Berhasil</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .welcome-message {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .participant-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .info-value {
            color: #6c757d;
        }
        .highlight {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .highlight h3 {
            margin: 0 0 5px 0;
            font-size: 20px;
        }
        .highlight p {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .next-steps {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 20px 0;
        }
        .next-steps h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏃‍♂️ RUN DEV</h1>
            <p>Event Lari untuk Developer</p>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                <strong>Selamat {{ $participantData['namaLengkap'] }}!</strong><br>
                Pendaftaran Anda untuk event RUN DEV telah berhasil diproses.
            </div>
            
            <div class="highlight">
                <h3>ID Peserta Anda</h3>
                <p>{{ $participantData['idPeserta'] }}</p>
            </div>
            
            <div class="participant-info">
                <h3 style="margin-top: 0; color: #2c3e50;">Detail Pendaftaran</h3>
                
                <div class="info-row">
                    <span class="info-label">Nama Lengkap:</span>
                    <span class="info-value">{{ $participantData['namaLengkap'] }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $participantData['email'] }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Kategori Lomba:</span>
                    <span class="info-value">{{ $participantData['kategoriLomba'] }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Nomor WhatsApp:</span>
                    <span class="info-value">{{ $participantData['nomorWA'] }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Ukuran Baju:</span>
                    <span class="info-value">{{ $participantData['ukuranBaju'] }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Tanggal Daftar:</span>
                    <span class="info-value">{{ date('d F Y, H:i', strtotime($participantData['registrationDate'])) }} WIB</span>
                </div>
            </div>
            
            <div class="next-steps">
                <h3>📋 Langkah Selanjutnya</h3>
                <ul>
                    <li>Simpan ID Peserta Anda: <strong>{{ $participantData['idPeserta'] }}</strong></li>
                    <li>Kami akan mengirimkan informasi pembayaran melalui WhatsApp dalam 1x24 jam</li>
                    <li>Setelah pembayaran dikonfirmasi, Anda akan menerima race pack information</li>
                    <li>Pantau terus email dan WhatsApp Anda untuk update terbaru</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <p style="font-size: 16px; color: #2c3e50;">
                    Terima kasih telah bergabung dengan <strong>RUN DEV</strong>!<br>
                    Mari bersiap untuk pengalaman lari yang tak terlupakan! 🎽
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>RUN DEV Team</strong></p>
            <p>Event Lari untuk Developer</p>
            <p>Email: info@rundev.com | WhatsApp: +62 812-3456-7890</p>
            
            <div class="social-links">
                <a href="#">Instagram</a> |
                <a href="#">Twitter</a> |
                <a href="#">Facebook</a>
            </div>
            
            <p style="font-size: 12px; margin-top: 15px;">
                Email ini dikirim otomatis, mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
