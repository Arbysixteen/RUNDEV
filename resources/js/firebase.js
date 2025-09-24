import { initializeApp } from 'firebase/app';
import { getDatabase, connectDatabaseEmulator } from 'firebase/database';

// Konfigurasi Firebase client
const firebaseConfig = {
    apiKey: "AIzaSyBIyxGNsw7w0YjwhTQq3O6Hjw_0d2jIWAQ", // Tidak masalah jika API Key client terekspos
    authDomain: "run-event-cbb9c.firebaseapp.com",
    databaseURL: "https://run-event-cbb9c-default-rtdb.firebaseio.com/",
    projectId: "run-event-cbb9c",
    storageBucket: "run-event-cbb9c.appspot.com",
    messagingSenderId: "779259991666",
    appId: "1:779259991666:web:af0cce1eb30fa4712b0ab5"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

// Initialize Realtime Database dan dapatkan referensi layanan
const database = getDatabase(app);

// Gunakan emulator saat dalam pengembangan lokal (opsional)
if (window.location.hostname === "localhost") {
    // Uncomment baris di bawah ini jika Anda menggunakan Firebase emulator
    // connectDatabaseEmulator(database, "127.0.0.1", 9000);
    console.log("Connected to local Firebase emulator");
} else {
    console.log("Connected to production Firebase");
}

// Export database instance
export { database };
