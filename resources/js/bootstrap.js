import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Fix 1: Pastikan base URL benar dengan path yang eksplisit
window.axios.defaults.baseURL = window.location.origin;

// Fix 2: Tetapkan header CSRF untuk setiap request (penting untuk Laravel)
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

// Fix 3: Tambahkan /api/ secara eksplisit ke semua URL API untuk memastikan konsistensi
window.axios.interceptors.request.use(request => {
    // Jika URL tidak dimulai dengan http/https dan tidak memiliki /api/ di awal
    if (!request.url.match(/^(http|https):\/\//)) {
        if (!request.url.startsWith('/api/') && !request.url.startsWith('api/')) {
            request.url = `/api/${request.url}`;
        } else if (request.url.startsWith('api/')) {
            request.url = `/${request.url}`;
        }
    }
    console.log('Final Axios Request URL:', request.url);
    console.log('Request Data:', request.data);
    return request;
});

// Debug untuk melihat semua request dan response Axios
window.axios.interceptors.response.use(
    response => {
        console.log('Axios Response:', response);
        return response;
    },
    error => {
        console.error('Axios Error:', error.response || error);
        return Promise.reject(error);
    }
);
