<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login Peserta - RUN DEV</title>
        <meta name="description" content="Login untuk peserta RUN DEV - Event Lari untuk Developer">

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
            
            .login {
                padding: 40px 20px;
                width: 100%;
            }
            
            .form-container {
                max-width: 450px;
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
                margin-bottom: 15px;
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
            
            .form-button.secondary {
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            
            .login-tabs {
                display: flex;
                margin-bottom: 20px;
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid #e5e7eb;
            }
            
            .tab-button {
                flex: 1;
                padding: 12px;
                background: #f9fafb;
                border: none;
                cursor: pointer;
                font-weight: 500;
                transition: all 0.3s;
            }
            
            .tab-button.active {
                background: #4f46e5;
                color: white;
            }
            
            .tab-content {
                display: none;
            }
            
            .tab-content.active {
                display: block;
            }
        </style>
    </head>
    <body>
        <section class="login">
            <div class="form-container">
                <div class="header">
                    <h1>RUN DEV</h1>
                    <p>Login Peserta</p>
                </div>
                
                @if(session('error'))
                    <div class="message error">{{ session('error') }}</div>
                @endif
                
                @if(session('success'))
                    <div class="message success">{{ session('success') }}</div>
                @endif
                
                @if($errors->any())
                    <div class="message error">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div id="message" style="display: none;"></div>
                
                <!-- Login Tabs -->
                <div class="login-tabs">
                    <button class="tab-button active" onclick="switchTab('email')">Login dengan Email</button>
                    <button class="tab-button" onclick="switchTab('phone')">Login dengan No. HP</button>
                </div>
                
                <!-- Email Login Form -->
                <div id="email-tab" class="tab-content active">
                    <form action="/peserta/login" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Email *</label>
                            <input
                                type="email"
                                class="form-input"
                                name="email"
                                placeholder="nama@email.com"
                                value="{{ old('email') }}"
                                required
                            />
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input
                                type="password"
                                class="form-input"
                                name="password"
                                placeholder="Masukkan password"
                                required
                                minlength="6"
                            />
                        </div>
                        
                        <button type="submit" class="form-button">
                            Login dengan Email
                        </button>
                    </form>
                </div>
                
                <!-- Phone Login Form -->
                <div id="phone-tab" class="tab-content">
                    <form id="phoneLoginForm">
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
                            <label class="form-label">Password *</label>
                            <input
                                type="password"
                                class="form-input"
                                name="password"
                                placeholder="Masukkan password"
                                required
                                minlength="6"
                            />
                        </div>
                        
                        <button type="submit" class="form-button secondary" id="phoneSubmitBtn">
                            Login dengan No. HP
                        </button>
                    </form>
                </div>
                
                <a href="/pendaftaran" class="back-link">Belum punya akun? Daftar di sini →</a>
                <a href="/" class="back-link">← Kembali ke Halaman Utama</a>
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

            // Tab switching
            function switchTab(tabName) {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Remove active class from all tab buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Show selected tab content
                document.getElementById(tabName + '-tab').classList.add('active');
                
                // Add active class to selected tab button
                event.target.classList.add('active');
            }
            
            // Email login form handler
            document.getElementById('emailLoginForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                await handleLogin(this, '/api/participant/login', 'emailSubmitBtn');
            });
            
            // Phone login form handler
            document.getElementById('phoneLoginForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                await handleLogin(this, '/api/participant/login-phone', 'phoneSubmitBtn');
            });
            
            async function handleLogin(form, url, buttonId) {
                const submitBtn = document.getElementById(buttonId);
                const messageDiv = document.getElementById('message');
                
                // Get form data
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                
                // Basic validation
                if (url.includes('login-phone')) {
                    if (!data.nomorWA || !data.password) {
                        showMessage('Mohon lengkapi semua field', 'error');
                        return;
                    }
                    if (data.nomorWA.length < 10) {
                        showMessage('Nomor WhatsApp tidak valid', 'error');
                        return;
                    }
                } else {
                    if (!data.email || !data.password) {
                        showMessage('Mohon lengkapi semua field', 'error');
                        return;
                    }
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(data.email)) {
                        showMessage('Format email tidak valid', 'error');
                        return;
                    }
                }
                
                if (data.password.length < 6) {
                    showMessage('Password minimal 6 karakter', 'error');
                    return;
                }
                
                // Disable button and show loading
                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';
                
                try {
                    const response = await fetch(url, {
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
                    
                    if (result.success) {
                        showMessage('Login berhasil! Mengalihkan...', 'success');
                        // Force redirect with small delay and ensure session is saved
                        setTimeout(() => {
                            // Use window.location.href instead of replace to ensure session cookies
                            window.location.href = '/peserta/dashboard';
                        }, 1000);
                    } else {
                        showMessage(result.message || 'Login gagal', 'error');
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    showMessage('Terjadi kesalahan saat login. Silakan coba lagi.', 'error');
                } finally {
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = url.includes('login-phone') ? 'Login dengan No. HP' : 'Login dengan Email';
                }
            }
            
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
