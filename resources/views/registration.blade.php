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
        
        <!-- React & React DOM -->
        <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
        <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
        
        <!-- Babel untuk JSX -->
        <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
        
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
        </style>
    </head>
    <body>
        <div id="registration-form"></div>
        
        <script type="text/babel">
            const RegistrationForm = () => {
                const [formData, setFormData] = React.useState({
                    namaLengkap: '',
                    email: '',
                    kategoriLomba: '',
                    nomorWA: '',
                    ukuranBaju: ''
                });
                
                const [isSubmitting, setIsSubmitting] = React.useState(false);
                const [message, setMessage] = React.useState(null);
                
                const handleChange = (e) => {
                    setFormData({
                        ...formData,
                        [e.target.name]: e.target.value
                    });
                };
                
                const handleSubmit = async (e) => {
                    e.preventDefault();
                    
                    // Validasi form
                    if (!formData.namaLengkap || !formData.email || !formData.kategoriLomba || !formData.nomorWA || !formData.ukuranBaju) {
                        setMessage({
                            type: 'error',
                            text: 'Mohon lengkapi semua field yang diperlukan'
                        });
                        return;
                    }
                    
                    // Email validation
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(formData.email)) {
                        setMessage({
                            type: 'error',
                            text: 'Format email tidak valid'
                        });
                        return;
                    }
                    
                    // Phone number validation (basic)
                    if (formData.nomorWA.length < 10) {
                        setMessage({
                            type: 'error',
                            text: 'Nomor WhatsApp tidak valid'
                        });
                        return;
                    }
                    
                    setIsSubmitting(true);
                    setMessage(null);
                    
                    try {
                        console.log('Submitting form data:', formData);
                        
                        // Kirim data ke API Laravel
                        const response = await fetch('/api/register', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(formData)
                        });
                        
                        const result = await response.json();
                        console.log('Registration response:', result);
                        
                        if (result.success) {
                            setMessage({
                                type: 'success',
                                text: `Pendaftaran berhasil! ID Peserta: ${result.data.participantId}`
                            });
                            // Reset form
                            setFormData({
                                namaLengkap: '',
                                email: '',
                                kategoriLomba: '',
                                nomorWA: '',
                                ukuranBaju: ''
                            });
                        } else {
                            setMessage({
                                type: 'error',
                                text: 'Pendaftaran gagal: ' + (result.message || 'Unknown error')
                            });
                        }
                    } catch (error) {
                        console.error('Registration error:', error);
                        setMessage({
                            type: 'error',
                            text: 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.'
                        });
                    } finally {
                        setIsSubmitting(false);
                    }
                };
                
                return (
                    <section className="registration">
                        <div className="form-container">
                            <div className="header">
                                <h1>RUN DEV</h1>
                                <p>Form Pendaftaran Event Lari untuk Developer</p>
                            </div>
                            
                            {message && (
                                <div className={'message ' + message.type} style={{
                                    padding: '10px',
                                    marginBottom: '20px',
                                    borderRadius: '5px',
                                    backgroundColor: message.type === 'error' ? '#fee2e2' : '#dcfce7',
                                    color: message.type === 'error' ? '#b91c1c' : '#15803d',
                                    border: '1px solid ' + (message.type === 'error' ? '#f87171' : '#86efac')
                                }}>
                                    {message.text}
                                </div>
                            )}
                            
                            <form onSubmit={handleSubmit}>
                                <div className="form-group">
                                    <label className="form-label">Nama Lengkap *</label>
                                    <input
                                        type="text"
                                        className="form-input"
                                        name="namaLengkap"
                                        value={formData.namaLengkap}
                                        onChange={handleChange}
                                        placeholder="Masukkan nama lengkap Anda"
                                        required
                                    />
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Email *</label>
                                    <input
                                        type="email"
                                        className="form-input"
                                        name="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        placeholder="nama@email.com"
                                        required
                                    />
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Kategori Lomba *</label>
                                    <select
                                        className="form-select"
                                        name="kategoriLomba"
                                        value={formData.kategoriLomba}
                                        onChange={handleChange}
                                        required
                                    >
                                        <option value="">Pilih kategori lomba</option>
                                        <option value="5K">5K - Rp 150.000</option>
                                        <option value="10K">10K - Rp 200.000</option>
                                        <option value="Half Marathon">Half Marathon - Rp 300.000</option>
                                    </select>
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Nomor WhatsApp *</label>
                                    <input
                                        type="tel"
                                        className="form-input"
                                        name="nomorWA"
                                        value={formData.nomorWA}
                                        onChange={handleChange}
                                        placeholder="08xxxxxxxxxx"
                                        required
                                    />
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Ukuran Baju *</label>
                                    <select
                                        className="form-select"
                                        name="ukuranBaju"
                                        value={formData.ukuranBaju}
                                        onChange={handleChange}
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
                                
                                <button
                                    type="submit"
                                    className="form-button"
                                    disabled={isSubmitting}
                                >
                                    {isSubmitting ? 'Memproses...' : 'Daftar Sekarang'}
                                </button>
                                
                                <div className="info-box">
                                    <h4>Informasi Penting:</h4>
                                    <ul>
                                        <li>Setelah mendaftar, Anda akan menerima konfirmasi via email</li>
                                        <li>Pembayaran dapat dilakukan melalui transfer bank atau e-wallet</li>
                                        <li>Konfirmasi pendaftaran akan dikirim via email dan WhatsApp</li>
                                    </ul>
                                </div>
                            </form>
                            
                            <a href="/" className="back-link">← Kembali ke Halaman Utama</a>
                        </div>
                    </section>
                );
            };
            
            // Render app
            const root = ReactDOM.createRoot(document.getElementById('registration-form'));
            root.render(<RegistrationForm />);
        </script>
    </body>
</html>
