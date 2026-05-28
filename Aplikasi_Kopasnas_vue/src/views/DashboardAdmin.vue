<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api/api'

const router = useRouter()

const totalAnggota = ref(0)
const totalVotingAktif = ref(0)
const totalVote = ref(0)
const nama = ref('')
const kelas = ref('')

// =====================
// JUMLAH ANGGOTA
// =====================
const getJumlahAnggota = async () => {
  try {
    const response = await api.get('/anggota')
    totalAnggota.value = response.data.data.length
  } catch (error) {
    console.log(error)
  }
}

// =====================
// VOTING AKTIF
// =====================
const getVotingAktif = async () => {
  try {
    const response = await api.get('/voting')
    totalVotingAktif.value = response.data.data.length
    totalVote.value = response.data.data.reduce((sum, voting) => sum + (voting.votes_count || 0), 0)
  } catch (error) {
    console.log(error)
  }
}

// =====================
// LOGOUT
// =====================
const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  localStorage.removeItem('anggota')

  router.push('/login')
}

const tambahAnggota = async () => {
  try {
    await api.post('/anggota', {
      nama: nama.value,
      kelas: kelas.value
    })

    alert('Anggota berhasil ditambah')

    nama.value = ''
    kelas.value = ''

    getJumlahAnggota() // biar dashboard langsung update
  } catch (error) {
    console.log(error.response)
  }
}

// =====================
// ON MOUNTED
// =====================
onMounted(() => {
  getJumlahAnggota()
  getVotingAktif()
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800">
    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-5 bg-[#0f172a] text-white shadow-md">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <h2 class="text-xl font-bold tracking-wide">KOPASNAS Admin</h2>
      </div>
      <button 
        @click="logout" 
        class="flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 transition-colors rounded-xl font-medium shadow-sm hover:shadow-md text-sm"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Logout
      </button>
    </nav>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-8 py-10">
      
      <!-- Welcome -->
      <div class="mb-10">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Selamat Datang Admin 👋</h1>
        <p class="text-slate-500 text-lg">Kelola anggota dan sistem voting organisasi Kopasnas secara terpusat.</p>
      </div>

      <!-- Statistik -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex items-center gap-5 hover:-translate-y-1 transition-transform duration-300">
          <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div>
            <h2 class="text-3xl font-bold text-slate-800 mb-1">{{ totalAnggota }}</h2>
            <p class="text-slate-500 font-medium text-sm">Total Anggota</p>
          </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex items-center gap-5 hover:-translate-y-1 transition-transform duration-300">
          <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
          <div>
            <h2 class="text-3xl font-bold text-slate-800 mb-1">{{ totalVotingAktif }}</h2>
            <p class="text-slate-500 font-medium text-sm">Voting Aktif</p>
          </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex items-center gap-5 hover:-translate-y-1 transition-transform duration-300">
          <div class="p-4 bg-purple-50 text-purple-600 rounded-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
          </div>
          <div>
            <h2 class="text-3xl font-bold text-slate-800 mb-1">{{ totalVote }}</h2>
            <p class="text-slate-500 font-medium text-sm">Total Vote</p>
          </div>
        </div>
      </div>

      <!-- Menu Utama -->
      <h3 class="text-xl font-bold text-slate-800 mb-6">Akses Cepat</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div @click="router.push('/anggota')" class="group cursor-pointer bg-white p-8 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col items-center text-center">
          <div class="h-20 w-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-5 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Anggota</h3>
          <p class="text-slate-500 text-sm leading-relaxed">Kelola data, tambah, atau hapus anggota organisasi secara lengkap.</p>
        </div>

        <div @click="router.push('/voting')" class="group cursor-pointer bg-white p-8 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col items-center text-center">
          <div class="h-20 w-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-5 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Voting</h3>
          <p class="text-slate-500 text-sm leading-relaxed">Buat voting baru, pantau status, dan atur periode pemilihan.</p>
        </div>

        <div @click="router.push('/hasil-voting')" class="group cursor-pointer bg-white p-8 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] hover:-translate-y-1.5 transition-all duration-300 flex flex-col items-center text-center">
          <div class="h-20 w-20 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-5 group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-2">Hasil</h3>
          <p class="text-slate-500 text-sm leading-relaxed">Lihat rekapitulasi data hasil voting beserta rincian pemilihnya.</p>
        </div>

      </div>
    </main>
  </div>
</template>