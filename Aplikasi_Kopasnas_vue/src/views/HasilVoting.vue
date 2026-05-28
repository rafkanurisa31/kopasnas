<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api/api'

const router = useRouter()
const votings = ref([])
const selectedVoting = ref(null)
const hasil = ref([])

// =====================
// AMBIL SEMUA VOTING
// =====================
const getVoting = async () => {
  try {
    const response = await api.get('/voting')
    votings.value = response.data.data || response.data
  } catch (error) {
    console.log(error)
  }
}

// =====================
// LIHAT HASIL VOTING
// =====================
const lihatHasil = async (id) => {
  try {
    const response = await api.get(`/voting/${id}/result`)
    selectedVoting.value = response.data.data
    hasil.value = response.data.data.opsi
  } catch (error) {
    console.log(error)
  }
}

// =====================
// HAPUS VOTING
// =====================
const hapusVoting = async (id) => {
  const yakin = confirm('Yakin ingin menghapus voting?')
  if(!yakin) return

  try {
    await api.delete(`/voting/${id}`)
    // refresh list
    await getVoting()
    // reset hasil
    selectedVoting.value = null
    hasil.value = []
  } catch (error) {
    console.log(error)
  }
}

onMounted(() => {
  getVoting()
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800 py-10 px-6">
    <div class="max-w-4xl mx-auto space-y-8">
      
      <!-- BACK BUTTON -->
      <button 
        @click="router.push('/dashboard-admin')" 
        class="flex items-center gap-2 px-5 py-2.5 text-slate-700 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-200 border border-slate-200 w-fit"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Dashboard
      </button>

      <!-- HEADER -->
      <div class="flex items-center gap-4 mb-8">
        <div class="h-12 w-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center shadow-sm border border-purple-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-slate-900">Hasil Voting</h1>
          <p class="text-sm text-slate-500 mt-1">Pantau rekapitulasi data hasil voting secara detail dan transparan.</p>
        </div>
      </div>

      <!-- LIST VOTING -->
      <div class="bg-white p-8 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100">
        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
          </svg>
          Daftar Voting
        </h2>
        
        <div class="space-y-4">
          <div v-if="!votings.length" class="text-center py-8 text-slate-500 border border-dashed border-slate-300 rounded-2xl">
            Belum ada data voting yang tersedia.
          </div>
          
          <div v-for="item in votings" :key="item.id" class="flex flex-col md:flex-row md:items-center justify-between p-5 border border-slate-200 rounded-2xl hover:border-purple-300 hover:shadow-md transition-all duration-300 bg-slate-50/50">
            <div>
              <h3 class="text-lg font-bold text-slate-800 mb-2">{{ item.judul }}</h3>
              <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-md inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Status: {{ item.status }}
              </span>
            </div>

            <div class="flex items-center gap-3 mt-4 md:mt-0">
              <button 
                @click="lihatHasil(item.id)"
                class="flex-1 md:flex-none flex items-center justify-center gap-1.5 px-5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-200 text-sm"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Hasil
              </button>
              <button 
                @click="hapusVoting(item.id)"
                class="flex-1 md:flex-none flex items-center justify-center gap-1.5 px-4 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-medium transition-colors duration-200 text-sm"
                title="Hapus"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- HASIL DETAIL -->
      <div v-if="selectedVoting" class="bg-white p-8 rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-purple-100 relative overflow-hidden transition-all duration-500">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-purple-100/50 to-transparent rounded-bl-full -z-10"></div>
        
        <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
          </svg>
          Hasil: {{ selectedVoting.judul }}
        </h2>

        <div class="space-y-0 divide-y divide-slate-100 border border-slate-100 rounded-2xl overflow-hidden">
          <div v-for="item in hasil" :key="item.nama" class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/30 hover:bg-slate-50 transition-colors duration-200">
            <div>
              <span class="text-lg font-bold text-slate-800 block mb-1">
                {{ item.nama }}
              </span>
              <div class="text-sm font-medium text-slate-500 flex items-start gap-1.5 mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Pemilih: <span class="text-slate-700">{{ item.voters && item.voters.length ? item.voters.join(', ') : 'Belum ada' }}</span></span>
              </div>
            </div>
            
            <div class="shrink-0 px-4 py-2 bg-purple-50 text-purple-700 font-bold rounded-xl border border-purple-100 flex items-center gap-2">
              <span class="text-xl">{{ item.total_vote }}</span>
              <span class="text-sm font-medium opacity-80">suara</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>