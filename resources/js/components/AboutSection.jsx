import React from 'react';
import { motion } from 'framer-motion';

const AboutSection = () => {
    const categories = [
        {
            name: '5K',
            description: 'Perfect untuk pemula dan developer yang ingin memulai journey lari',
            price: 'Rp 150.000',
            features: ['Jersey eksklusif', 'Medali finisher', 'Sertifikat digital', 'Goodie bag']
        },
        {
            name: '10K',
            description: 'Tantangan menengah untuk developer yang sudah terbiasa berlari',
            price: 'Rp 200.000',
            features: ['Jersey eksklusif', 'Medali finisher', 'Sertifikat digital', 'Goodie bag', 'Tumbler eksklusif']
        },
        {
            name: 'Half Marathon',
            description: 'Tantangan ultimate untuk developer dengan stamina tinggi',
            price: 'Rp 300.000',
            features: ['Jersey eksklusif', 'Medali finisher premium', 'Sertifikat digital', 'Goodie bag premium', 'Tumbler eksklusif', 'Tas ransel']
        }
    ];

    return (
        <section id="about" className="py-20 bg-white">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <motion.div
                    className="text-center mb-16"
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                    viewport={{ once: true }}
                >
                    <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        Tentang <span className="text-purple-600">RUN DEV</span>
                    </h2>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        RUN DEV adalah event lari pertama yang khusus dirancang untuk komunitas developer. 
                        Kami percaya bahwa kesehatan fisik dan mental adalah kunci produktivitas dalam coding.
                    </p>
                </motion.div>

                <div className="grid md:grid-cols-3 gap-8 mb-16">
                    {categories.map((category, index) => (
                        <motion.div
                            key={category.name}
                            className="bg-gradient-to-br from-purple-50 to-blue-50 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: index * 0.2 }}
                            viewport={{ once: true }}
                            whileHover={{ y: -5 }}
                        >
                            <div className="text-center mb-6">
                                <h3 className="text-3xl font-bold text-purple-700 mb-2">{category.name}</h3>
                                <p className="text-gray-600 mb-4">{category.description}</p>
                                <div className="text-2xl font-bold text-purple-600">{category.price}</div>
                            </div>
                            <ul className="space-y-3">
                                {category.features.map((feature, idx) => (
                                    <li key={idx} className="flex items-center text-gray-700">
                                        <svg className="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                        </svg>
                                        {feature}
                                    </li>
                                ))}
                            </ul>
                        </motion.div>
                    ))}
                </div>

                <motion.div
                    className="grid md:grid-cols-2 gap-12 items-center"
                    initial={{ opacity: 0 }}
                    whileInView={{ opacity: 1 }}
                    transition={{ duration: 0.8 }}
                    viewport={{ once: true }}
                >
                    <div>
                        <h3 className="text-3xl font-bold text-gray-900 mb-6">Mengapa RUN DEV?</h3>
                        <div className="space-y-4">
                            <div className="flex items-start">
                                <div className="bg-purple-100 rounded-full p-2 mr-4 mt-1">
                                    <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 className="text-xl font-semibold text-gray-900 mb-2">Boost Produktivitas</h4>
                                    <p className="text-gray-600">Olahraga teratur terbukti meningkatkan fokus dan kreativitas dalam coding.</p>
                                </div>
                            </div>
                            <div className="flex items-start">
                                <div className="bg-purple-100 rounded-full p-2 mr-4 mt-1">
                                    <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 className="text-xl font-semibold text-gray-900 mb-2">Networking</h4>
                                    <p className="text-gray-600">Bertemu dengan developer lain dan bangun koneksi yang berharga.</p>
                                </div>
                            </div>
                            <div className="flex items-start">
                                <div className="bg-purple-100 rounded-full p-2 mr-4 mt-1">
                                    <svg className="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 className="text-xl font-semibold text-gray-900 mb-2">Kesehatan Mental</h4>
                                    <p className="text-gray-600">Lari membantu mengurangi stress dan meningkatkan mood untuk coding yang lebih baik.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="relative">
                        <motion.div
                            className="bg-gradient-to-br from-purple-400 to-blue-500 rounded-2xl p-8 text-white"
                            whileHover={{ scale: 1.02 }}
                            transition={{ duration: 0.3 }}
                        >
                            <h4 className="text-2xl font-bold mb-4">Event Details</h4>
                            <div className="space-y-3">
                                <div className="flex items-center">
                                    <svg className="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clipRule="evenodd" />
                                    </svg>
                                    <span>15 Desember 2024</span>
                                </div>
                                <div className="flex items-center">
                                    <svg className="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L10 9.586V6z" clipRule="evenodd" />
                                    </svg>
                                    <span>06:00 WIB</span>
                                </div>
                                <div className="flex items-center">
                                    <svg className="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                                    </svg>
                                    <span>Monas, Jakarta Pusat</span>
                                </div>
                            </div>
                        </motion.div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
};

export default AboutSection;
