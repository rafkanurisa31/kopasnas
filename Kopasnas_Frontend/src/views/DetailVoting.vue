<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../api/api'

const route = useRoute()
const router = useRouter()

const voting = ref({
  opsi: []
})

const pilihan = ref(null)
const sudahVote = ref(false)
const userId = localStorage.getItem('user_id')
const role = localStorage.getItem('role')
const voteKey = `sudah_vote_${route.params.id}_${userId}`

// =====================
// GET DETAIL VOTING
// =====================
const getDetailVoting = async () => {
  try {
    const response = await api.get(`/voting/${route.params.id}`)
    console.log('DETAIL VOTING:', response.data)
    voting.value = response.data.data || response.data
  } catch (error) {
    console.log('GET ERROR:', error.response)
  }
}

// =====================
// SUBMIT VOTE
// =====================
const submitVote = async () => {
  try {
    console.log('CLICK VOTE')

    if (!userId) {
      alert('User belum login')
      return
    }

    if (!pilihan.value) {
      alert('Pilih salah satu opsi')
      return
    }

    console.log('PAYLOAD:', {
      anggota_id: userId,
      voting_id: voting.value.id,
      opsi_id: pilihan.value
    })

    await api.post('/vote', {
      anggota_id: userId,
      voting_id: voting.value.id,
      opsi_id: pilihan.value
    })

    alert('Voting berhasil')

    sudahVote.value = true
    localStorage.setItem(voteKey, 'true')

  } catch (error) {
    console.log('VOTE ERROR:', error.response)
    alert(error.response?.data?.message || 'Vote gagal')
  }
}

// =====================
// GO BACK
// =====================
const goBack = () => {
  if (role === 'admin') {
    router.push('/dashboard-admin')
  } else {
    router.push('/dashboard-anggota')
  }
}

// =====================
// INIT
// =====================
onMounted(() => {
  getDetailVoting()

  const cekVote = localStorage.getItem(voteKey)
  if (cekVote === 'true') {
    sudahVote.value = true
  }

  console.log('USER:', userId)
  console.log('VOTE KEY:', voteKey)
  console.log('SUDAH VOTE:', sudahVote.value)
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800 py-10 px-6 flex flex-col items-center">
    
    <div class="w-full max-w-lg mb-6">
      <!-- BACK BUTTON -->
      <button 
        @click="goBack" 
        class="flex items-center gap-2 px-5 py-2.5 text-slate-700 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-200 border border-slate-200 w-fit"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Dashboard
      </button>
    </div>

    <!-- MAIN CARD -->
    <div class="w-full max-w-lg bg-white p-8 rounded-3xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 relative overflow-hidden">
      <!-- Decorative background -->
      <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-blue-50/80 to-transparent -z-10"></div>
      
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center h-16 w-16 bg-blue-100 text-blue-600 rounded-2xl mb-4 shadow-inner">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ voting.judul }}</h1>
        <p class="text-slate-500 mt-2 font-medium text-sm">Silakan pilih salah satu opsi di bawah ini untuk memberikan suara Anda.</p>
      </div>

      <div class="space-y-4">
        <div
          v-for="item in voting.opsi"
          :key="item.id"
          class="relative"
        >
          <label 
            :class="[
              'flex items-center gap-4 p-5 border-2 rounded-2xl cursor-pointer transition-all duration-200',
              pilihan === item.id 
                ? 'border-blue-500 bg-blue-50/50 shadow-[0_4px_15px_-3px_rgba(59,130,246,0.15)]' 
                : 'border-slate-100 hover:border-blue-200 hover:bg-slate-50 bg-white',
              sudahVote ? 'opacity-70 cursor-not-allowed' : ''
            ]"
          >
            <div class="relative flex items-center justify-center">
              <input
                type="radio"
                :value="item.id"
                v-model="pilihan"
                :disabled="sudahVote"
                class="sr-only"
              >
              <div 
                :class="[
                  'w-6 h-6 rounded-full border-2 flex items-center justify-center transition-all duration-200',
                  pilihan === item.id ? 'border-blue-500 bg-blue-500' : 'border-slate-300'
                ]"
              >
                <div v-if="pilihan === item.id" class="w-2 h-2 rounded-full bg-white"></div>
              </div>
            </div>
            <span 
              :class="[
                'text-lg font-semibold transition-colors duration-200',
                pilihan === item.id ? 'text-blue-900' : 'text-slate-700'
              ]"
            >
              {{ item.nama_opsi }}
            </span>
          </label>
        </div>
      </div>

      <div class="mt-10">
        <button
          v-if="!sudahVote"
          type="button"
          @click="submitVote"
          class="w-full flex items-center justify-center gap-2 py-4 px-6 bg-blue-600 text-white rounded-2xl font-bold text-lg shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:shadow-blue-700/40 transform hover:-translate-y-0.5 transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Kirim Suara Anda
        </button>

        <div 
          v-else 
          class="w-full flex items-center justify-center gap-3 py-4 px-6 bg-emerald-50 text-emerald-700 rounded-2xl font-bold text-lg border border-emerald-100"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Anda sudah memberikan suara
        </div>
      </div>

    </div>
  </div>
</template>