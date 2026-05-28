<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api/api'

const router = useRouter()

const nama = localStorage.getItem('nama') || 'Anggota'

const totalAnggota = ref(0)
const votings = ref([])

// =====================
// GET ANGGOTA
// =====================
const getJumlahAnggota = async () => {
  try {
    const res = await api.get('/anggota')
    const data = res.data.data || res.data
    totalAnggota.value = data.length
  } catch (err) {
    console.log('ERROR:', err)
  }
}

// =====================
// GET VOTING
// =====================
const getVotingAktif = async () => {
  try {
    const res = await api.get('/voting')
    votings.value = res.data.data || res.data
  } catch (err) {
    console.log('ERROR:', err)
  }
}

// =====================
// NAVIGASI
// =====================
const goToAnggota = () => {
  router.push('/anggota')
}

const pilihVoting = (id) => {
  router.push(`/voting/${id}`)
}

// =====================
// LOGOUT
// =====================
const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  localStorage.removeItem('anggota')
  localStorage.removeItem('user_id')
  localStorage.removeItem('nama')
  router.push('/login')
}

onMounted(() => {
  getJumlahAnggota()
  getVotingAktif()
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800 flex flex-col text-left">
    <!-- NAVBAR -->
    <nav class="flex justify-between items-center px-8 py-5 bg-[#0f172a] text-white shadow-md">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <h2 class="text-xl font-bold tracking-wide">KOPASNAS <span class="text-xs font-semibold text-blue-400 bg-blue-500/10 px-2.5 py-1 rounded-full ml-1 border border-blue-500/20">Anggota</span></h2>
      </div>
      <button 
        @click="logout" 
        class="flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 transition-all duration-200 rounded-xl font-medium shadow-sm hover:shadow-md text-sm text-white"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Logout
      </button>
    </nav>

    <!-- CONTENT -->
    <main class="max-w-5xl mx-auto w-full px-6 py-10 flex-grow space-y-8">
      
      <!-- WELCOME CARD -->
      <div class="bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a] text-white rounded-2xl p-8 shadow-md relative overflow-hidden border border-slate-800">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.1),transparent_50%)]"></div>
        <div class="relative z-10 space-y-2">
          <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">Halo, {{ nama }} 👋</h1>
          <p class="text-slate-300 text-sm md:text-base font-light">Selamat datang kembali! Silakan pilih voting yang tersedia atau lihat daftar anggota lainnya.</p>
        </div>
      </div>

      <!-- MAIN ACTIONS GRID -->
      <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- DATA ANGGOTA CARD -->
        <div 
          @click="goToAnggota"
          class="md:col-span-3 bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between cursor-pointer group hover:border-blue-400 hover:shadow-md transition-all duration-350"
        >
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <h3 class="font-bold text-lg text-slate-800">Data Anggota</h3>
              <p class="text-slate-500 text-sm">{{ totalAnggota }} anggota terdaftar aktif</p>
            </div>
          </div>
          <div class="text-slate-400 group-hover:text-blue-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
          </div>
        </div>
      </section>

      <!-- VOTING AKTIF SECTION -->
      <section class="space-y-6">
        <div class="flex items-center gap-2.5 pb-2 border-b border-slate-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <h2 class="text-xl font-bold text-slate-800">Voting Aktif</h2>
        </div>

        <div v-if="votings.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div 
            v-for="item in votings" 
            :key="item.id"
            class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md hover:border-slate-200 transition-all duration-300"
          >
            <div class="space-y-4 text-left">
              <div class="flex justify-between items-start">
                <h3 class="font-bold text-lg text-[#0f172a] leading-snug">{{ item.judul }}</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                  Aktif
                </span>
              </div>
              <p class="text-slate-500 text-sm">Voting saat ini sedang berjalan. Berikan kontribusi suara Anda segera!</p>
            </div>
            
            <button 
              @click="pilihVoting(item.id)"
              class="w-full mt-6 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-sm shadow-blue-500/10 hover:shadow-md"
            >
              Lihat Voting
            </button>
          </div>
        </div>

        <!-- EMPTY STATE -->
        <div v-else class="bg-white rounded-2xl p-12 text-center border border-slate-100 space-y-3">
          <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m16 4h-2m-3 0h-5.5" />
            </svg>
          </div>
          <h3 class="font-bold text-slate-800 text-lg">Belum Ada Voting</h3>
          <p class="text-slate-500 text-sm max-w-sm mx-auto">Saat ini belum ada agenda pemilihan atau voting yang sedang berlangsung dari admin.</p>
        </div>
      </section>

    </main>
  </div>
</template>

<style scoped>
</style>