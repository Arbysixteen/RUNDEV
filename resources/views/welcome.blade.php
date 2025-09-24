<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>RUN DEV - Event Lari untuk Developer</title>
        <meta name="description" content="Bergabunglah dengan komunitas developer dalam event lari yang menginspirasi. Pilih kategori 5K, 10K, atau Half Marathon.">

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
                background-color: #f8f9fa;
            }
            
            /* Hero Section */
            .hero {
                min-height: 100vh;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                color: white;
                position: relative;
                overflow: hidden;
            }
            
            .hero-content {
                max-width: 800px;
                padding: 20px;
                z-index: 2;
            }
            
            .hero h1 {
                font-size: 5rem;
                margin-bottom: 1rem;
                font-weight: 700;
            }
            
            .hero p {
                font-size: 1.5rem;
                margin-bottom: 2rem;
                opacity: 0.9;
            }
            
            .cta-button {
                background-color: white;
                color: #764ba2;
                padding: 1rem 2rem;
                font-size: 1.2rem;
                border-radius: 0.5rem;
                font-weight: 600;
                cursor: pointer;
                display: inline-block;
                border: none;
                transition: all 0.3s ease;
            }
            
            .cta-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            }
            
            .cta-button-secondary {
                background-color: transparent;
                color: white;
                padding: 1rem 2rem;
                border: 2px solid white;
                border-radius: 50px;
                font-weight: 600;
                font-size: 1.1rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .cta-button-secondary:hover {
                background-color: white;
                color: #764ba2;
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            }
            
            /* About Section */
            .about {
                padding: 80px 20px;
                max-width: 1200px;
                margin: 0 auto;
            }
            
            .about h2 {
                font-size: 3rem;
                margin-bottom: 2rem;
                text-align: center;
                color: #333;
            }
            
            .categories {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
            }
            
            .category {
                background: white;
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                flex: 1;
                min-width: 250px;
                max-width: 350px;
                transition: transform 0.3s ease;
            }
            
            .category:hover {
                transform: translateY(-5px);
            }
            
            .category h3 {
                font-size: 2rem;
                color: #764ba2;
                margin-bottom: 0.5rem;
            }
            
            .category .price {
                font-size: 1.5rem;
                font-weight: bold;
                margin: 1rem 0;
                color: #333;
            }
            
            .category ul {
                padding-left: 20px;
                margin-top: 15px;
            }
            
            .category li {
                margin-bottom: 5px;
            }
            
            /* Registration Form */
            .registration {
                background: #f0f2ff;
                padding: 80px 20px;
            }
            
            .form-container {
                max-width: 600px;
                margin: 0 auto;
                background: white;
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
            
            .form-group {
                margin-bottom: 20px;
            }
            
            .form-label {
                display: block;
                margin-bottom: 5px;
                font-weight: 500;
                color: #333;
            }
            
            .form-input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 1rem;
            }
            
            .form-select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                font-size: 1rem;
                background: white;
            }
            
            .form-button {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 5px;
                font-size: 1.1rem;
                cursor: pointer;
                width: 100%;
                margin-top: 10px;
            }
            
            .floating-bg {
                position: absolute;
                width: 60px;
                height: 60px;
                background: rgba(255,255,255,0.1);
                border-radius: 50%;
            }
            
            .bg1 { top: 20%; left: 10%; }
            .bg2 { top: 40%; right: 15%; }
            .bg3 { bottom: 30%; left: 20%; }
        </style>
    </head>
    <body>
        <!-- Admin Link -->
        <div style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
            <a href="/admin/login" style="background: rgba(0,0,0,0.7); color: white; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 12px;">
                🔐 Admin
            </a>
        </div>
        
        <div id="app"></div>
        
        <script type="text/babel">
            @verbatim
            // React components
            const App = () => {
                
                // No need for form handling in this page anymore
                
                return (
                    <React.Fragment>
                        {/* Hero Section */}
                        <section className="hero">
                            <div className="floating-bg bg1" style={{animation: 'float 6s ease-in-out infinite'}}></div>
                            <div className="floating-bg bg2" style={{animation: 'float 8s ease-in-out infinite 1s'}}></div>
                            <div className="floating-bg bg3" style={{animation: 'float 7s ease-in-out infinite 2s'}}></div>
                            
                            <div className="hero-content">
                                <h1>RUN DEV</h1>
                                <p>Lari untuk Developer, Lari untuk Masa Depan</p>
                                <div style={{display: 'flex', gap: '20px', justifyContent: 'center', flexWrap: 'wrap'}}>
                                    <a href="/pendaftaran" className="cta-button" style={{textDecoration: 'none', display: 'inline-block'}}>Daftar Sekarang</a>
                                    <a href="/peserta/login" className="cta-button-secondary" style={{textDecoration: 'none', display: 'inline-block'}}>Login Peserta</a>
                                </div>
                            </div>
                        </section>
                        
                        {/* About Section */}
                        <section className="about">
                            <h2>Tentang RUN DEV</h2>
                            <div className="categories">
                                <div className="category">
                                    <h3>5K</h3>
                                    <p>Perfect untuk pemula dan developer yang ingin memulai journey lari</p>
                                    <div className="price">Rp 150.000</div>
                                    <ul>
                                        <li>Jersey eksklusif</li>
                                        <li>Medali finisher</li>
                                        <li>Sertifikat digital</li>
                                    </ul>
                                </div>
                                
                                <div className="category">
                                    <h3>10K</h3>
                                    <p>Tantangan menengah untuk developer yang sudah terbiasa berlari</p>
                                    <div className="price">Rp 200.000</div>
                                    <ul>
                                        <li>Jersey eksklusif</li>
                                        <li>Medali finisher</li>
                                        <li>Sertifikat digital</li>
                                        <li>Tumbler eksklusif</li>
                                    </ul>
                                </div>
                                
                                <div className="category">
                                    <h3>Half Marathon</h3>
                                    <p>Tantangan ultimate untuk developer dengan stamina tinggi</p>
                                    <div className="price">Rp 300.000</div>
                                    <ul>
                                        <li>Jersey eksklusif</li>
                                        <li>Medali finisher premium</li>
                                        <li>Sertifikat digital</li>
                                        <li>Goodie bag premium</li>
                                    </ul>
                                </div>
                            </div>
                            <div style={{textAlign: 'center', marginTop: '40px'}}>
                                <a href="/pendaftaran" className="cta-button" style={{textDecoration: 'none', display: 'inline-block'}}>Daftar Sekarang</a>
                            </div>
                        </section>
                        
                        {/* No registration form here - redirecting to /pendaftaran page */}
                    </React.Fragment>
                );
            };
            
            // Render app
            const root = ReactDOM.createRoot(document.getElementById('app'));
            root.render(<App />);
            @endverbatim
        </script>
        
        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
        </style>
    </body>
</html>
