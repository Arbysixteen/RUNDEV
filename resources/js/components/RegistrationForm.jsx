import React, { useState } from 'react';
import { motion } from 'framer-motion';

const RegistrationForm = ({ onSubmit }) => {
    const [formData, setFormData] = useState({
        namaLengkap: '',
        email: '',
        kategoriLomba: '',
        nomorWA: '',
        ukuranBaju: ''
    });

    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        
        console.log('Form submission initiated');
        console.log('Current form data:', formData);
        
        // Validation
        if (!formData.namaLengkap || !formData.email || !formData.kategoriLomba || !formData.nomorWA || !formData.ukuranBaju) {
            console.error('Form validation failed: Missing required fields');
            alert('Mohon lengkapi semua field yang diperlukan');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            alert('Format email tidak valid');
            return;
        }

        // Phone number validation (basic)
        if (formData.nomorWA.length < 10) {
            alert('Nomor WhatsApp tidak valid');
            return;
        }

        setIsSubmitting(true);
        try {
            console.log('Calling onSubmit with form data:', formData);
            await onSubmit(formData);
            console.log('onSubmit completed successfully');
        } catch (error) {
            console.error('Registration error:', error);
            // Show detailed error to user
            alert(`Registration error: ${error.message}\n${error.stack ? error.stack : ''}`);
        } finally {
            console.log('Form submission complete, setting isSubmitting to false');
            setIsSubmitting(false);
        }
    };

    return (
        <section id="registration" className="py-20 bg-gradient-to-br from-gray-50 to-blue-50">
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <motion.div
                    className="text-center mb-12"
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                    viewport={{ once: true }}
                >
                    <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Daftar <span className="text-purple-600">Sekarang</span>
                    </h2>
                    <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                        Bergabunglah dengan ribuan developer lainnya dalam event lari yang menginspirasi
                    </p>
                </motion.div>

                <motion.div
                    className="bg-white rounded-3xl shadow-2xl p-8 md:p-12"
                    initial={{ opacity: 0, y: 50 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8, delay: 0.2 }}
                    viewport={{ once: true }}
                >
                    <form onSubmit={handleSubmit} className="space-y-6">
                        <div className="grid md:grid-cols-2 gap-6">
                            <motion.div
                                initial={{ opacity: 0, x: -20 }}
                                whileInView={{ opacity: 1, x: 0 }}
                                transition={{ duration: 0.6, delay: 0.3 }}
                                viewport={{ once: true }}
                            >
                                <label className="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap *
                                </label>
                                <input
                                    type="text"
                                    name="namaLengkap"
                                    value={formData.namaLengkap}
                                    onChange={handleChange}
                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="Masukkan nama lengkap Anda"
                                    required
                                />
                            </motion.div>

                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                whileInView={{ opacity: 1, x: 0 }}
                                transition={{ duration: 0.6, delay: 0.4 }}
                                viewport={{ once: true }}
                            >
                                <label className="block text-sm font-semibold text-gray-700 mb-2">
                                    Email *
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    value={formData.email}
                                    onChange={handleChange}
                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="nama@email.com"
                                    required
                                />
                            </motion.div>
                        </div>

                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.5 }}
                            viewport={{ once: true }}
                        >
                            <label className="block text-sm font-semibold text-gray-700 mb-2">
                                Kategori Lomba *
                            </label>
                            <select
                                name="kategoriLomba"
                                value={formData.kategoriLomba}
                                onChange={handleChange}
                                className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                required
                            >
                                <option value="">Pilih kategori lomba</option>
                                <option value="5K">5K - Rp 150.000</option>
                                <option value="10K">10K - Rp 200.000</option>
                                <option value="Half Marathon">Half Marathon - Rp 300.000</option>
                            </select>
                        </motion.div>

                        <div className="grid md:grid-cols-2 gap-6">
                            <motion.div
                                initial={{ opacity: 0, x: -20 }}
                                whileInView={{ opacity: 1, x: 0 }}
                                transition={{ duration: 0.6, delay: 0.6 }}
                                viewport={{ once: true }}
                            >
                                <label className="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor WhatsApp *
                                </label>
                                <input
                                    type="tel"
                                    name="nomorWA"
                                    value={formData.nomorWA}
                                    onChange={handleChange}
                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="08xxxxxxxxxx"
                                    required
                                />
                            </motion.div>

                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                whileInView={{ opacity: 1, x: 0 }}
                                transition={{ duration: 0.6, delay: 0.7 }}
                                viewport={{ once: true }}
                            >
                                <label className="block text-sm font-semibold text-gray-700 mb-2">
                                    Ukuran Baju *
                                </label>
                                <select
                                    name="ukuranBaju"
                                    value={formData.ukuranBaju}
                                    onChange={handleChange}
                                    className="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    required
                                >
                                    <option value="">Pilih ukuran baju</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                            </motion.div>
                        </div>

                        <motion.div
                            className="pt-6"
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.8 }}
                            viewport={{ once: true }}
                        >
                            <motion.button
                                type="submit"
                                disabled={isSubmitting}
                                className={`w-full py-4 px-8 rounded-xl font-semibold text-lg transition-all duration-300 ${
                                    isSubmitting
                                        ? 'bg-gray-400 cursor-not-allowed'
                                        : 'bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white shadow-lg hover:shadow-xl'
                                }`}
                                whileHover={!isSubmitting ? { scale: 1.02 } : {}}
                                whileTap={!isSubmitting ? { scale: 0.98 } : {}}
                            >
                                {isSubmitting ? (
                                    <div className="flex items-center justify-center">
                                        <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses...
                                    </div>
                                ) : (
                                    'Daftar & Lanjut ke Pembayaran'
                                )}
                            </motion.button>
                        </motion.div>
                    </form>

                    <motion.div
                        className="mt-8 p-4 bg-blue-50 rounded-xl"
                        initial={{ opacity: 0 }}
                        whileInView={{ opacity: 1 }}
                        transition={{ duration: 0.6, delay: 0.9 }}
                        viewport={{ once: true }}
                    >
                        <div className="flex items-start">
                            <svg className="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
                            </svg>
                            <div className="text-sm text-blue-700">
                                <p className="font-semibold mb-1">Informasi Penting:</p>
                                <ul className="space-y-1 text-blue-600">
                                    <li>• Setelah mendaftar, Anda akan diarahkan ke halaman pembayaran</li>
                                    <li>• Pembayaran dapat dilakukan melalui transfer bank atau e-wallet</li>
                                    <li>• Konfirmasi pendaftaran akan dikirim via email dan WhatsApp</li>
                                </ul>
                            </div>
                        </div>
                    </motion.div>
                </motion.div>
            </div>
        </section>
    );
};

export default RegistrationForm;
