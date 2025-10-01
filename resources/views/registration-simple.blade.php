<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Pendaftaran RUN DEV - Event Lari untuk Developer</title>
        <meta name="description" content="Form pendaftaran untuk event lari RUN DEV. Pilih kategori 5K, 10K, atau Half Marathon.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Firebase SDK -->
        <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>
        
        <!-- Styles -->
        <style>
            body, html {
                margin: 0;
                padding: 0;
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .registration {
                padding: 40px 20px;
                width: 100%;
            }
            
            .form-container {
                max-width: 600px;
                margin: 0 auto;
                background: white;
                padding: 40px;
                border-radius: 16px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            }
            
            .header {
                margin-bottom: 30px;
                text-align: center;
            }
            
            .header h1 {
                font-size: 2.5rem;
                color: #4f46e5;
                margin-bottom: 10px;
            }
            
            .header p {
                color: #6b7280;
                font-size: 1.1rem;
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-label {
                display: block;
                margin-bottom: 5px;
                font-weight: 500;
                color: #374151;
            }
            
            .form-input {
                width: 100%;
                padding: 12px;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                font-size: 1rem;
                transition: border-color 0.3s;
                box-sizing: border-box;
            }
            
            .form-input:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            }
            
            .form-select {
                width: 100%;
                padding: 12px;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                font-size: 1rem;
                background: white;
                transition: border-color 0.3s;
                box-sizing: border-box;
            }
            
            .form-select:focus {
                outline: none;
                border-color: #4f46e5;
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            }
            
            .form-button {
                width: 100%;
                padding: 14px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                transition: transform 0.3s, box-shadow 0.3s;
            }
            
            .form-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            }
            
            .form-button:disabled {
                opacity: 0.6;
                cursor: not-allowed;
                transform: none;
            }
            
            .info-box {
                background: #f0f5ff;
                padding: 15px;
                border-radius: 8px;
                margin-top: 20px;
                color: #1e40af;
                font-size: 0.9rem;
            }
            
            .info-box h4 {
                margin-top: 0;
                margin-bottom: 8px;
            }
            
            .info-box ul {
                padding-left: 20px;
                margin: 0;
            }
            
            .back-link {
                display: block;
                text-align: center;
                margin-top: 20px;
                color: #4f46e5;
                text-decoration: none;
                font-weight: 500;
                transition: color 0.3s;
            }
            
            .back-link:hover {
                color: #4338ca;
                text-decoration: underline;
            }
            
            .message {
                padding: 10px;
                margin-bottom: 20px;
                border-radius: 5px;
                border: 1px solid;
            }
            
            .message.success {
                background-color: #dcfce7;
                color: #15803d;
                border-color: #86efac;
            }
            
            .message.error {
                background-color: #fee2e2;
                color: #b91c1c;
                border-color: #f87171;
            }
        </style>
    </head>
    <body>
        <section class="registration">
            <div class="form-container">
                <div class="header">
                    <h1>RUN DEV</h1>
                    <p>Form Pendaftaran Event Lari untuk Developer</p>
                </div>
                
                <div id="message" style="display: none;"></div>
                
                <form id="registrationForm">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input
                            type="text"
                            class="form-input"
                            name="namaLengkap"
                            placeholder="Masukkan nama lengkap Anda"
                            required
                        />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input
                            type="email"
                            class="form-input"
                            name="email"
                            placeholder="nama@email.com"
                            required
                        />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Kategori Lomba *</label>
                        <select
                            class="form-select"
                            name="kategoriLomba"
                            required
                        >
                            <option value="">Pilih kategori lomba</option>
                            <option value="5K">5K - Rp 150.000</option>
                            <option value="10K">10K - Rp 200.000</option>
                            <option value="Half Marathon">Half Marathon - Rp 300.000</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp *</label>
                        <input
                            type="tel"
                            class="form-input"
                            name="nomorWA"
                            placeholder="08xxxxxxxxxx"
                            required
                        />
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Ukuran Baju *</label>
                        <select
                            class="form-select"
                            name="ukuranBaju"
                            required
                        >
                            <option value="">Pilih ukuran baju</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password untuk Login *</label>
                        <input
                            type="password"
                            class="form-input"
                            name="password"
                            placeholder="Masukkan password untuk login"
                            required
                            minlength="6"
                        />
                        <small style="color: #6b7280; font-size: 0.875rem;">Password minimal 6 karakter untuk login ke halaman peserta</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password *</label>
                        <input
                            type="password"
                            class="form-input"
                            name="password_confirmation"
                            placeholder="Ulangi password"
                            required
                            minlength="6"
                        />
                    </div>
                    
                    <button type="submit" class="form-button" id="submitBtn">
                        Daftar Sekarang
                    </button>
                    
                    <div class="info-box">
                        <h4>Informasi Penting:</h4>
                        <ul>
                            <li>Setelah mendaftar, Anda akan menerima konfirmasi via email</li>
                            <li>Pembayaran dapat dilakukan melalui transfer bank atau e-wallet</li>
                            <li>Konfirmasi pendaftaran akan dikirim via email dan WhatsApp</li>
                            <li>Gunakan email dan password untuk login ke halaman peserta</li>
                        </ul>
                    </div>
                </form>
                
                <a href="/" class="back-link">← Kembali ke Halaman Utama</a>
                <a href="/peserta/login" class="back-link" style="margin-top: 10px;">Sudah terdaftar? Login Peserta →</a>
            </div>
        </section>

        <script>
            // Firebase Configuration
            const firebaseConfig = {
                apiKey: "{{ env('MIX_FIREBASE_API_KEY') }}",
                authDomain: "{{ env('MIX_FIREBASE_AUTH_DOMAIN') }}",
                databaseURL: "{{ env('MIX_FIREBASE_DATABASE_URL') }}",
                projectId: "{{ env('MIX_FIREBASE_PROJECT_ID') }}",
                storageBucket: "{{ env('MIX_FIREBASE_STORAGE_BUCKET') }}",
                messagingSenderId: "{{ env('MIX_FIREBASE_MESSAGING_SENDER_ID') }}",
                appId: "{{ env('MIX_FIREBASE_APP_ID') }}"
            };

            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);
            const auth = firebase.auth();

            document.getElementById('registrationForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submitBtn');
                const messageDiv = document.getElementById('message');
                
                // Get form data
                const formData = new FormData(this);
                const data = Object.fromEntries(formData);
                
                // Validation
                if (!data.namaLengkap || !data.email || !data.kategoriLomba || !data.nomorWA || !data.ukuranBaju || !data.password || !data.password_confirmation) {
                    showMessage('Mohon lengkapi semua field yang diperlukan', 'error');
                    return;
                }
                
                // Password validation
                if (data.password.length < 6) {
                    showMessage('Password minimal 6 karakter', 'error');
                    return;
                }
                
                if (data.password !== data.password_confirmation) {
                    showMessage('Konfirmasi password tidak cocok', 'error');
                    return;
                }
                
                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(data.email)) {
                    showMessage('Format email tidak valid', 'error');
                    return;
                }
                
                // Phone number validation
                if (data.nomorWA.length < 10) {
                    showMessage('Nomor WhatsApp tidak valid', 'error');
                    return;
                }
                
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';
                
                try {
                    console.log('Submitting form data:', data);
                    
                    // Send data to API
                    const response = await fetch('/api/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin', // Include cookies
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    console.log('Registration response:', result);
                    
                    if (result.success) {
                        // Sign in with the newly created account to send verification email
                        try {
                            const userCredential = await auth.signInWithEmailAndPassword(data.email, data.password);
                            const user = userCredential.user;
                            
                            // Send verification email using Firebase client SDK
                            await user.sendEmailVerification({
                                url: window.location.origin + '/peserta/login?verified=true',
                                handleCodeInApp: false
                            });
                            
                            // Sign out immediately after sending email
                            await auth.signOut();
                            
                            showMessage('Pendaftaran berhasil! Email verifikasi telah dikirim ke ' + data.email + '. Silakan cek inbox email Anda (termasuk folder spam) untuk verifikasi akun.', 'success');
                            console.log('Verification email sent successfully');
                        } catch (emailError) {
                            console.error('Error sending verification email:', emailError);
                            showMessage('Pendaftaran berhasil, tetapi gagal mengirim email verifikasi. Silakan gunakan fitur "Kirim Ulang Email Verifikasi" di halaman login.', 'success');
                        }
                        
                        // Reset form
                        this.reset();
                        // Redirect to login page after 5 seconds
                        setTimeout(() => {
                            window.location.href = '/peserta/login';
                        }, 5000);
                    } else {
                        showMessage('Pendaftaran gagal: ' + (result.message || 'Unknown error'), 'error');
                    }
                } catch (error) {
                    console.error('Registration error:', error);
                    showMessage('Terjadi kesalahan saat mendaftar. Silakan coba lagi.', 'error');
                } finally {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Daftar Sekarang';
                }
            });
            
            function showMessage(text, type) {
                const messageDiv = document.getElementById('message');
                messageDiv.textContent = text;
                messageDiv.className = 'message ' + type;
                messageDiv.style.display = 'block';
                
                // Scroll to message
                messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        </script>
    </body>
</html>
