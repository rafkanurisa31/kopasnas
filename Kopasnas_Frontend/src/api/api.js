import axios from 'axios';

const api = axios.create({
    baseURL: 'https://kopasnas.onrender.com/api', // Pastikan URL ini benar sesuai backend kamu
});

// Menambahkan token ke header setiap request secara otomatis
const token = localStorage.getItem('token');
if (token) {
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

export default api;