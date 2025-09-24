import React from 'react';
import { motion } from 'framer-motion';

const HeroSection = () => {
    const scrollToRegistration = () => {
        document.getElementById('registration').scrollIntoView({ behavior: 'smooth' });
    };

    return (
        <section className="relative min-h-screen flex items-center justify-center gradient-bg overflow-hidden">
            {/* Animated background elements */}
            <div className="absolute inset-0">
                <motion.div
                    className="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full"
                    animate={{
                        y: [0, -20, 0],
                        scale: [1, 1.1, 1],
                    }}
                    transition={{
                        duration: 4,
                        repeat: Infinity,
                        ease: "easeInOut"
                    }}
                />
                <motion.div
                    className="absolute top-40 right-20 w-16 h-16 bg-white/10 rounded-full"
                    animate={{
                        y: [0, 20, 0],
                        scale: [1, 0.9, 1],
                    }}
                    transition={{
                        duration: 3,
                        repeat: Infinity,
                        ease: "easeInOut",
                        delay: 1
                    }}
                />
                <motion.div
                    className="absolute bottom-40 left-1/4 w-12 h-12 bg-white/10 rounded-full"
                    animate={{
                        y: [0, -15, 0],
                        x: [0, 10, 0],
                    }}
                    transition={{
                        duration: 5,
                        repeat: Infinity,
                        ease: "easeInOut",
                        delay: 2
                    }}
                />
            </div>

            <div className="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
                <motion.div
                    initial={{ opacity: 0, y: 50 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.8 }}
                >
                    <h1 className="text-6xl md:text-8xl font-bold mb-6 tracking-tight">
                        RUN <span className="text-yellow-300">DEV</span>
                    </h1>
                    <motion.p
                        className="text-xl md:text-2xl mb-8 font-light leading-relaxed"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ delay: 0.3, duration: 0.8 }}
                    >
                        Lari untuk Developer, Lari untuk Masa Depan
                    </motion.p>
                    <motion.p
                        className="text-lg mb-12 opacity-90 max-w-2xl mx-auto"
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ delay: 0.6, duration: 0.8 }}
                    >
                        Bergabunglah dengan komunitas developer dalam event lari yang menginspirasi. 
                        Pilih kategori yang sesuai dengan kemampuan Anda dan rasakan pengalaman lari yang tak terlupakan.
                    </motion.p>
                </motion.div>

                <motion.div
                    className="flex flex-col sm:flex-row gap-4 justify-center items-center"
                    initial={{ opacity: 0, y: 30 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.9, duration: 0.8 }}
                >
                    <motion.button
                        onClick={scrollToRegistration}
                        className="bg-white text-purple-700 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-all duration-300 shadow-lg"
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                    >
                        Daftar Sekarang
                    </motion.button>
                    <motion.button
                        onClick={() => document.getElementById('about').scrollIntoView({ behavior: 'smooth' })}
                        className="border-2 border-white text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white hover:text-purple-700 transition-all duration-300"
                        whileHover={{ scale: 1.05 }}
                        whileTap={{ scale: 0.95 }}
                    >
                        Pelajari Lebih Lanjut
                    </motion.button>
                </motion.div>

                {/* Scroll indicator */}
                <motion.div
                    className="absolute bottom-8 left-1/2 transform -translate-x-1/2"
                    animate={{ y: [0, 10, 0] }}
                    transition={{ duration: 2, repeat: Infinity }}
                >
                    <div className="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                        <div className="w-1 h-3 bg-white rounded-full mt-2"></div>
                    </div>
                </motion.div>
            </div>
        </section>
    );
};

export default HeroSection;
