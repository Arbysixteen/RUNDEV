import React, { useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';

const PaymentModal = ({ isOpen, onClose, onPaymentComplete, participantData }) => {
    const [paymentMethod, setPaymentMethod] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);

    const getPrice = (category) => {
        switch (category) {
            case '5K': return 150000;
            case '10K': return 200000;
            case 'Half Marathon': return 300000;
            default: return 0;
        }
    };

    const formatPrice = (price) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
    };

    const handlePayment = async () => {
        if (!paymentMethod) {
            alert('Silakan pilih metode pembayaran');
            return;
        }

        setIsProcessing(true);
        
        // Simulate payment processing
        setTimeout(() => {
            setIsProcessing(false);
            onPaymentComplete();
        }, 2000);
    };

    const paymentMethods = [
        {
            id: 'bca',
            name: 'Transfer BCA',
            icon: '🏦',
            account: '1234567890',
            accountName: 'RUN DEV EVENT'
        },
        {
            id: 'mandiri',
            name: 'Transfer Mandiri',
            icon: '🏦',
            account: '0987654321',
            accountName: 'RUN DEV EVENT'
        },
        {
            id: 'gopay',
            name: 'GoPay',
            icon: '💳',
            account: '081234567890',
            accountName: 'RUN DEV EVENT'
        },
        {
            id: 'ovo',
            name: 'OVO',
            icon: '💳',
            account: '081234567890',
            accountName: 'RUN DEV EVENT'
        }
    ];

    if (!participantData) return null;

    const price = getPrice(participantData.kategoriLomba);

    return (
        <AnimatePresence>
            {isOpen && (
                <motion.div
                    className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    onClick={onClose}
                >
                    <motion.div
                        className="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                        initial={{ scale: 0.9, opacity: 0 }}
                        animate={{ scale: 1, opacity: 1 }}
                        exit={{ scale: 0.9, opacity: 0 }}
                        onClick={(e) => e.stopPropagation()}
                    >
                        <div className="p-6 border-b border-gray-200">
                            <div className="flex items-center justify-between">
                                <h2 className="text-2xl font-bold text-gray-900">Pembayaran</h2>
                                <button
                                    onClick={onClose}
                                    className="text-gray-400 hover:text-gray-600 transition-colors"
                                >
                                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div className="p-6">
                            {/* Registration Summary */}
                            <div className="bg-gray-50 rounded-xl p-4 mb-6">
                                <h3 className="font-semibold text-gray-900 mb-3">Detail Pendaftaran</h3>
                                <div className="space-y-2 text-sm">
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">ID Peserta:</span>
                                        <span className="font-medium">{participantData.idPeserta}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Nama:</span>
                                        <span className="font-medium">{participantData.namaLengkap}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Kategori:</span>
                                        <span className="font-medium">{participantData.kategoriLomba}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-600">Ukuran Baju:</span>
                                        <span className="font-medium">{participantData.ukuranBaju}</span>
                                    </div>
                                    <div className="border-t pt-2 mt-2">
                                        <div className="flex justify-between text-lg font-bold">
                                            <span>Total:</span>
                                            <span className="text-purple-600">{formatPrice(price)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Payment Methods */}
                            <div className="mb-6">
                                <h3 className="font-semibold text-gray-900 mb-4">Pilih Metode Pembayaran</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    {paymentMethods.map((method) => (
                                        <motion.div
                                            key={method.id}
                                            className={`border-2 rounded-xl p-4 cursor-pointer transition-all duration-300 ${
                                                paymentMethod === method.id
                                                    ? 'border-purple-500 bg-purple-50'
                                                    : 'border-gray-200 hover:border-purple-300'
                                            }`}
                                            onClick={() => setPaymentMethod(method.id)}
                                            whileHover={{ scale: 1.02 }}
                                            whileTap={{ scale: 0.98 }}
                                        >
                                            <div className="flex items-center">
                                                <span className="text-2xl mr-3">{method.icon}</span>
                                                <div>
                                                    <div className="font-medium text-gray-900">{method.name}</div>
                                                    <div className="text-sm text-gray-600">{method.account}</div>
                                                </div>
                                            </div>
                                        </motion.div>
                                    ))}
                                </div>
                            </div>

                            {/* Payment Instructions */}
                            {paymentMethod && (
                                <motion.div
                                    className="bg-blue-50 rounded-xl p-4 mb-6"
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.3 }}
                                >
                                    <h4 className="font-semibold text-blue-900 mb-2">Instruksi Pembayaran</h4>
                                    <div className="text-sm text-blue-800 space-y-1">
                                        <p>1. Transfer ke rekening yang dipilih dengan nominal: <strong>{formatPrice(price)}</strong></p>
                                        <p>2. Gunakan kode unik: <strong>{participantData.idPeserta}</strong> sebagai berita transfer</p>
                                        <p>3. Simpan bukti transfer untuk konfirmasi</p>
                                        <p>4. Klik tombol "Konfirmasi Pembayaran" setelah transfer berhasil</p>
                                    </div>
                                </motion.div>
                            )}

                            {/* Payment Button */}
                            <motion.button
                                onClick={handlePayment}
                                disabled={!paymentMethod || isProcessing}
                                className={`w-full py-4 px-6 rounded-xl font-semibold text-lg transition-all duration-300 ${
                                    !paymentMethod || isProcessing
                                        ? 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                        : 'bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white shadow-lg hover:shadow-xl'
                                }`}
                                whileHover={paymentMethod && !isProcessing ? { scale: 1.02 } : {}}
                                whileTap={paymentMethod && !isProcessing ? { scale: 0.98 } : {}}
                            >
                                {isProcessing ? (
                                    <div className="flex items-center justify-center">
                                        <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses Pembayaran...
                                    </div>
                                ) : (
                                    'Konfirmasi Pembayaran'
                                )}
                            </motion.button>

                            <div className="mt-4 text-center text-sm text-gray-500">
                                <p>Dengan melanjutkan, Anda menyetujui syarat dan ketentuan RUN DEV</p>
                            </div>
                        </div>
                    </motion.div>
                </motion.div>
            )}
        </AnimatePresence>
    );
};

export default PaymentModal;
