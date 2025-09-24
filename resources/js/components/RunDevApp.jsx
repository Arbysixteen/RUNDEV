import React, { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import axios from 'axios';
import HeroSection from './HeroSection';
import AboutSection from './AboutSection';
import RegistrationForm from './RegistrationForm';
import PaymentModal from './PaymentModal';

const RunDevApp = () => {
    const [showPaymentModal, setShowPaymentModal] = useState(false);
    const [registrationData, setRegistrationData] = useState(null);
    const [firebaseStatus, setFirebaseStatus] = useState({
        tested: false,
        connected: false,
        message: 'Belum diuji'
    });
    
    useEffect(() => {
        // Test Firebase connection on component mount
        testFirebaseConnection();
    }, []);
    
    const testFirebaseConnection = async () => {
        try {
            setFirebaseStatus(prev => ({
                ...prev,
                message: 'Menguji koneksi...'
            }));
            
            const response = await axios.get('/api/test-firebase');
            // Log URL lengkap untuk debugging
            console.log('Firebase test URL:', window.location.origin + '/api/test-firebase');
            console.log('Firebase test response:', response.data);
            
            setFirebaseStatus({
                tested: true,
                connected: response.data.success,
                message: response.data.success ? 'Terhubung ke Firebase' : response.data.message
            });
        } catch (error) {
            console.error('Firebase test error:', error);
            setFirebaseStatus({
                tested: true,
                connected: false,
                message: `Error: ${error.response?.data?.message || error.message}`
            });
        }
    };

    const handleRegistration = async (formData) => {
        try {
            console.log('Submitting form data:', formData);
            
            // Dapatkan CSRF token terlebih dahulu
            let token = document.head.querySelector('meta[name="csrf-token"]');
            const csrfToken = token ? token.content : null;
            
            if (!csrfToken) {
                console.warn('CSRF token not found - trying to fetch it...');
                try {
                    const tokenResponse = await axios.get('/api/csrf-token');
                    if (tokenResponse.data && tokenResponse.data.token) {
                        console.log('Successfully fetched CSRF token');
                        axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenResponse.data.token;
                    }
                } catch (tokenError) {
                    console.error('Failed to fetch CSRF token:', tokenError);
                }
            } else {
                console.log('Using CSRF token from meta tag');
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
            }
            
            // Coba endpoint debug terlebih dahulu
            console.log('Sending test request to debug endpoint...');
            try {
                const debugResponse = await axios.post('/api/debug-request', { test: 'Debug test', ...formData });
                console.log('Debug endpoint response:', debugResponse.data);
            } catch (debugError) {
                console.error('Debug request failed:', debugError);
                // Lanjut meskipun debug gagal
            }
            
            // Kirim data ke endpoint API Laravel dengan absolute URL untuk menghindari masalah rute
            const apiUrl = `${window.location.origin}/api/register`;
            console.log('Sending registration data to API endpoint:', apiUrl);
            const response = await axios({
                method: 'post',
                url: apiUrl,
                data: formData,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('Registration response:', response.data);
            
            if (response.data.success) {
                // Tambahkan data Firebase ID dari response
                const participantData = {
                    ...formData,
                    idPeserta: response.data.data.participantId,
                    firebaseId: response.data.data.firebaseId,
                    registrationDate: new Date().toISOString(),
                    pembayaran: 'belum'
                };
                
                setRegistrationData(participantData);
                setShowPaymentModal(true);
            } else {
                throw new Error(response.data.message || 'Unknown error');
            }
        } catch (error) {
            console.error('Error registering participant:', error);
            
            // Ambil pesan error yang lebih detail
            const errorMessage = error.response?.data?.message || error.message || 'Terjadi kesalahan saat mendaftar';
            
            alert(`Terjadi kesalahan saat mendaftar: ${errorMessage}. Silakan coba lagi.`);
        }
    };

    const handlePaymentComplete = async () => {
        if (registrationData) {
            try {
                console.log('Updating payment for participant:', registrationData.firebaseId);
                
                // Kirim permintaan update pembayaran ke endpoint API
                const response = await axios.post('/api/update-payment', {
                    firebaseId: registrationData.firebaseId
                });
                
                console.log('Payment update response:', response.data);
                
                if (response.data.success) {
                    setShowPaymentModal(false);
                    alert('Pembayaran berhasil! Terima kasih telah mendaftar RUN DEV.');
                } else {
                    throw new Error(response.data.message || 'Unknown error');
                }
            } catch (error) {
                console.error('Error updating payment:', error);
                
                // Ambil pesan error yang lebih detail
                const errorMessage = error.response?.data?.message || error.message || 'Terjadi kesalahan saat memproses pembayaran';
                
                alert(`Terjadi kesalahan saat memproses pembayaran: ${errorMessage}. Silakan hubungi admin.`);
            }
        }
    };

    return (
        <div className="min-h-screen bg-gray-50">
            <HeroSection />
            <AboutSection />
            
            {/* Firebase Status Indicator */}
            <div className={`fixed top-4 right-4 px-4 py-2 rounded-md shadow-md z-50 ${firebaseStatus.connected ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>
                <div className="flex items-center">
                    <div className={`w-3 h-3 rounded-full mr-2 ${firebaseStatus.connected ? 'bg-green-500' : 'bg-red-500'}`}></div>
                    <span className="text-sm font-medium">
                        Firebase: {firebaseStatus.message}
                    </span>
                    {firebaseStatus.tested && !firebaseStatus.connected && (
                        <button 
                            onClick={testFirebaseConnection}
                            className="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded"
                        >
                            Coba lagi
                        </button>
                    )}
                </div>
            </div>
            
            <RegistrationForm onSubmit={handleRegistration} />
            
            {showPaymentModal && (
                <PaymentModal
                    isOpen={showPaymentModal}
                    onClose={() => setShowPaymentModal(false)}
                    onPaymentComplete={handlePaymentComplete}
                    participantData={registrationData}
                />
            )}
        </div>
    );
};

export default RunDevApp;
