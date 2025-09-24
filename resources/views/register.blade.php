<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Pendaftaran RUN DEV - Event Lari untuk Developer</title>
        <meta name="description" content="Form pendaftaran event lari RUN DEV. Pilih kategori 5K, 10K, atau Half Marathon.">

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
                background-color: #f0f2ff;
                min-height: 100vh;
            }
            
            .header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 20px;
                color: white;
                text-align: center;
            }
            
            .header h1 {
                font-size: 2.5rem;
                margin: 0;
                padding: 10px 0;
            }
            
            .registration {
                padding: 50px 20px;
            }
            
            .form-container {
                max-width: 600px;
                margin: 0 auto;
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                position: relative;
                overflow: hidden;
            }
            
            .form-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 5px;
                background: linear-gradient(90deg, #667eea, #764ba2, #f06595);
            }
            
            .form-group {
                margin-bottom: 25px;
            }
            
            .form-label {
                display: block;
                margin-bottom: 8px;
                font-weight: 500;
                color: #333;
            }
            
            .form-input {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 1rem;
                transition: border-color 0.3s;
            }
            
            .form-input:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
            }
            
            .form-select {
                width: 100%;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 1rem;
                background: white;
                transition: border-color 0.3s;
            }
            
            .form-select:focus {
                outline: none;
                border-color: #667eea;
                box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
            }
            
            .form-button {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px 20px;
                border: none;
                border-radius: 5px;
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                width: 100%;
                margin-top: 10px;
                transition: transform 0.3s, box-shadow 0.3s;
            }
            
            .form-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .category-cards {
                display: flex;
                gap: 15px;
                margin-bottom: 25px;
                flex-wrap: wrap;
            }
            
            .category-card {
                flex: 1;
                min-width: 120px;
                padding: 15px;
                border: 2px solid #ddd;
                border-radius: 8px;
                text-align: center;
                cursor: pointer;
                transition: all 0.3s;
            }
            
            .category-card:hover {
                border-color: #667eea;
                background-color: #f8f9ff;
            }
            
            .category-card.selected {
                border-color: #667eea;
                background-color: #f0f4ff;
                box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
            }
            
            .category-card h3 {
                margin: 0 0 5px 0;
                color: #333;
            }
            
            .category-card .price {
                font-weight: 700;
                color: #764ba2;
            }
            
            .form-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 30px;
            }
            
            .back-link {
                color: #667eea;
                text-decoration: none;
                font-weight: 500;
                display: flex;
                align-items: center;
                transition: color 0.3s;
            }
            
            .back-link:hover {
                color: #764ba2;
            }
            
            .back-link svg {
                margin-right: 5px;
            }
            
            .success-message {
                text-align: center;
                padding: 30px;
            }
            
            .success-message h2 {
                color: #38a169;
                font-size: 2rem;
                margin-bottom: 15px;
            }
            
            .success-message p {
                font-size: 1.2rem;
                margin-bottom: 25px;
            }
            
            .success-button {
                background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
                color: white;
                padding: 12px 25px;
                border: none;
                border-radius: 5px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: transform 0.3s, box-shadow 0.3s;
            }
            
            .success-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <header class="header">
            <h1>RUN DEV - Pendaftaran</h1>
            <p>Lengkapi data di bawah ini untuk bergabung dengan event lari developer</p>
        </header>
        
        <div id="app"></div>
        
        <script type="text/babel">
            @verbatim
            // React components
            const RegistrationForm = () => {
                const [formData, setFormData] = React.useState({
                    idPeserta: `RD${Date.now()}`,
                    namaLengkap: '',
                    email: '',
                    kategoriLomba: '',
                    nomorWA: '',
                    ukuranBaju: '',
                    pembayaran: 'belum',
                    registrationDate: new Date().toISOString()
                });
                
                const [submitted, setSubmitted] = React.useState(false);
                
                const handleCategorySelect = (category) => {
                    setFormData({
                        ...formData,
                        kategoriLomba: category
                    });
                };
                
                const handleChange = (e) => {
                    setFormData({
                        ...formData,
                        [e.target.name]: e.target.value
                    });
                };
                
                const handleSubmit = (e) => {
                    e.preventDefault();
                    console.log('Form submitted:', formData);
                    
                    // Simulasi pengiriman data ke server
                    setTimeout(() => {
                        setSubmitted(true);
                    }, 1000);
                    
                    // Di sini bisa ditambahkan kode untuk mengirim data ke Firebase
                };
                
                if (submitted) {
                    return (
                        <div className="registration">
                            <div className="form-container success-message">
                                <h2>Pendaftaran Berhasil!</h2>
                                <p>Terima kasih telah mendaftar event RUN DEV.</p>
                                <p>ID Peserta Anda: <strong>{formData.idPeserta}</strong></p>
                                <p>Detail pendaftaran telah dikirim ke email {formData.email}</p>
                                <p>Silahkan lakukan pembayaran untuk menyelesaikan proses pendaftaran</p>
                                <a href="/" className="back-link">
                                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    );
                }
                
                return (
                    <div className="registration">
                        <div className="form-container">
                            <form onSubmit={handleSubmit}>
                                <div className="form-group">
                                    <label className="form-label">Pilih Kategori Lomba</label>
                                    <div className="category-cards">
                                        <div 
                                            className={`category-card ${formData.kategoriLomba === "5K" ? "selected" : ""}`}
                                            onClick={() => handleCategorySelect("5K")}
                                        >
                                            <h3>5K</h3>
                                            <div className="price">Rp 150.000</div>
                                        </div>
                                        <div 
                                            className={`category-card ${formData.kategoriLomba === "10K" ? "selected" : ""}`}
                                            onClick={() => handleCategorySelect("10K")}
                                        >
                                            <h3>10K</h3>
                                            <div className="price">Rp 200.000</div>
                                        </div>
                                        <div 
                                            className={`category-card ${formData.kategoriLomba === "Half Marathon" ? "selected" : ""}`}
                                            onClick={() => handleCategorySelect("Half Marathon")}
                                        >
                                            <h3>Half Marathon</h3>
                                            <div className="price">Rp 300.000</div>
                                        </div>
                                    </div>
                                    {formData.kategoriLomba === "" && 
                                        <p style={{color: '#e53e3e', fontSize: '0.9rem', marginTop: '5px'}}>Silahkan pilih kategori lomba</p>
                                    }
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Nama Lengkap</label>
                                    <input 
                                        type="text" 
                                        className="form-input" 
                                        name="namaLengkap" 
                                        value={formData.namaLengkap} 
                                        onChange={handleChange} 
                                        required 
                                        placeholder="Masukkan nama lengkap"
                                    />
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Email</label>
                                    <input 
                                        type="email" 
                                        className="form-input" 
                                        name="email" 
                                        value={formData.email} 
                                        onChange={handleChange} 
                                        required 
                                        placeholder="Masukkan alamat email"
                                    />
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Nomor WhatsApp</label>
                                    <input 
                                        type="tel" 
                                        className="form-input" 
                                        name="nomorWA" 
                                        value={formData.nomorWA} 
                                        onChange={handleChange} 
                                        required 
                                        placeholder="contoh: 081234567890"
                                    />
                                </div>
                                
                                <div className="form-group">
                                    <label className="form-label">Ukuran Baju</label>
                                    <select 
                                        className="form-select" 
                                        name="ukuranBaju" 
                                        value={formData.ukuranBaju} 
                                        onChange={handleChange} 
                                        required
                                    >
                                        <option value="">Pilih ukuran</option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>
                                </div>
                                
                                <div className="form-footer">
                                    <a href="/" className="back-link">
                                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        Kembali
                                    </a>
                                    <button 
                                        type="submit" 
                                        className="form-button" 
                                        disabled={formData.kategoriLomba === ""}
                                        style={{opacity: formData.kategoriLomba === "" ? 0.7 : 1}}
                                    >
                                        Daftar Sekarang
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                );
            };
            
            // Render app
            const root = ReactDOM.createRoot(document.getElementById('app'));
            root.render(<RegistrationForm />);
            @endverbatim
        </script>
    </body>
</html>
