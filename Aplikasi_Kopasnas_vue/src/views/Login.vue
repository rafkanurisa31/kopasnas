<script setup>
import { ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import api from '../api/api'

const router = useRouter()

const tipeLogin = ref('admin')
const username = ref('')
const password = ref('')

const setTipeLogin = (tipe) => {
  tipeLogin.value = tipe
  username.value = ''
  password.value = ''
}

const login = async() => {
  try {
    if (tipeLogin.value === 'admin') {
      const response = await api.post('/login-admin', {
        username: username.value,
        password: password.value
      })

      localStorage.setItem('token', response.data.token)
      localStorage.setItem('role', 'admin')
      router.push('/dashboard-admin')
    } else {
      const response = await api.post('/login-anggota', {
        nama: username.value,
        kelas: password.value
      })

      console.log(response.data)
      localStorage.setItem('role', 'anggota')
      localStorage.setItem('anggota', JSON.stringify(response.data.data))
      localStorage.setItem('user_id', response.data.data?.id || username.value)
      localStorage.setItem('nama', response.data.data?.nama || username.value)
      router.push('/dashboard-anggota')
    }
  } catch (error) {
    console.log(error.response)
    alert(error.response?.data?.message || 'Login gagal')
  }
}
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a] font-sans flex flex-col justify-center items-center p-6 relative">
    <!-- Background blur spots for extra depth -->
    <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Back to Home Link -->
    <div class="absolute top-6 left-6">
      <RouterLink to="/" class="flex items-center gap-2 text-slate-400 hover:text-white transition-colors text-sm font-medium">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Beranda
      </RouterLink>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-md bg-white rounded-2xl p-8 shadow-2xl border border-slate-100 relative z-10 transition-all duration-300">
      
      <!-- Logo & Title -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 mb-4 shadow-inner">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">KOPASNAS</h1>
        <p class="text-slate-500 text-sm mt-1">Sistem Organisasi Digital Paskibra</p>
      </div>

      <!-- Custom Tab Selector instead of select dropdown -->
      <div class="flex p-1 bg-slate-100 rounded-xl mb-6">
        <button 
          @click="setTipeLogin('admin')"
          :class="[
            'flex-1 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200',
            tipeLogin === 'admin' 
              ? 'bg-white text-blue-600 shadow-sm' 
              : 'text-slate-600 hover:text-[#0f172a]'
          ]"
        >
          Admin
        </button>
        <button 
          @click="setTipeLogin('anggota')"
          :class="[
            'flex-1 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200',
            tipeLogin === 'anggota' 
              ? 'bg-white text-blue-600 shadow-sm' 
              : 'text-slate-600 hover:text-[#0f172a]'
          ]"
        >
          Anggota
        </button>
      </div>

      <!-- Form Inputs -->
      <div class="space-y-5 text-left">
        <!-- Username / Nama Input -->
        <div class="space-y-2">
          <label class="block text-sm font-semibold text-slate-700">
            {{ tipeLogin === 'admin' ? 'Username' : 'Nama Anggota' }}
          </label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </span>
            <input
              v-model="username"
              type="text"
              class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all"
              :placeholder="tipeLogin === 'admin' ? 'Masukkan username' : 'Masukkan nama anggota'"
            />
          </div>
        </div>

        <!-- Password / Kelas Input -->
        <div class="space-y-2">
          <label class="block text-sm font-semibold text-slate-700">
            {{ tipeLogin === 'admin' ? 'Password' : 'Kelas' }}
          </label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
              <svg v-if="tipeLogin === 'admin'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </span>
            <input
              v-model="password"
              :type="tipeLogin === 'admin' ? 'password' : 'text'"
              class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all"
              :placeholder="tipeLogin === 'admin' ? 'Masukkan password' : 'Masukkan kelas'"
            />
          </div>
        </div>

        <!-- Login Button -->
        <button 
          @click="login"
          class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 transition-all duration-200 mt-6"
        >
          Masuk
        </button>
      </div>

    </div>
  </div>
</template>

<style scoped>
</style>