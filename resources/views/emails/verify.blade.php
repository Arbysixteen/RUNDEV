<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - RUN DEV Event</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f7fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .header p {
            color: #6b7280;
            font-size: 1.1rem;
        }
        .content {
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 14px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: transform 0.3s;
        }
        .verify-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.9rem;
        }
        .info-box {
            background: #f0f5ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            color: #1e40af;
            font-size: 0.9rem;
        }
        .link-text {
            background: #f3f4f6;
            padding: 10px;
            border-radius: 5px;
            word-break: break-all;
            font-size: 0.85rem;
            color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>RUN DEV</h1>
            <p>Event Lari untuk Developer</p>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $name }}</strong>,</p>
            
            <p>Terima kasih telah mendaftar untuk RUN DEV Event! Untuk melanjutkan proses pendaftaran, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:</p>
            
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    Verifikasi Email Saya
                </a>
            </div>
            
            <p>Atau, jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</p>
            
            <div class="link-text">
                {{ $verificationUrl }}
            </div>
            
            <div class="info-box">
                <strong>Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Link verifikasi ini hanya berlaku untuk satu kali penggunaan</li>
                    <li>Setelah verifikasi, Anda dapat login ke halaman peserta</li>
                    <li>Jika Anda tidak mendaftar untuk RUN DEV Event, abaikan email ini</li>
                </ul>
            </div>
            
            <p style="margin-top: 20px;">Sampai jumpa di event!</p>
            
            <p>
                Salam olahraga,<br>
                <strong>Tim RUN DEV</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} RUN DEV Event. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
