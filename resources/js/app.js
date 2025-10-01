import './bootstrap';
import axios from 'axios';

// Initialize Firebase connection test
document.addEventListener('DOMContentLoaded', function() {
    testFirebaseConnection();
});

async function testFirebaseConnection() {
    const statusElement = document.getElementById('firebase-status');
    if (!statusElement) return;
    
    try {
        statusElement.innerHTML = `
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-2 bg-yellow-500"></div>
                <span class="text-sm font-medium">Firebase: Menguji koneksi...</span>
            </div>
        `;
        
        const response = await axios.get('/api/test-firebase');
        console.log('Firebase test response:', response.data);
        
        const isConnected = response.data.success;
        statusElement.innerHTML = `
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-2 ${isConnected ? 'bg-green-500' : 'bg-red-500'}"></div>
                <span class="text-sm font-medium">
                    Firebase: ${isConnected ? 'Terhubung' : response.data.message}
                </span>
                ${!isConnected ? '<button onclick="testFirebaseConnection()" class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Coba lagi</button>' : ''}
            </div>
        `;
        statusElement.className = `fixed top-4 right-4 px-4 py-2 rounded-md shadow-md z-50 ${isConnected ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
        
    } catch (error) {
        console.error('Firebase test error:', error);
        statusElement.innerHTML = `
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-2 bg-red-500"></div>
                <span class="text-sm font-medium">Firebase: Error - ${error.message}</span>
                <button onclick="testFirebaseConnection()" class="ml-2 text-xs bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded">Coba lagi</button>
            </div>
        `;
        statusElement.className = 'fixed top-4 right-4 px-4 py-2 rounded-md shadow-md z-50 bg-red-100 text-red-800';
    }
}

// Make function globally available
window.testFirebaseConnection = testFirebaseConnection;
