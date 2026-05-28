<script setup>
import { ref, onMounted, computed } from 'vue'
import api from '../api/api'

const votings = ref([])

// CREATE
const judul = ref('')
const opsi = ref(['', ''])

// EDIT
const editMode = ref(null)
const editJudul = ref('')
const editOpsi = ref([])

const totalVoting = computed(() => votings.value.length)

// =====================
// GET VOTING
// =====================
const getVoting = async () => {
  try {
    const response = await api.get('/voting')
    votings.value = response.data.data || response.data
  } catch (error) {
    console.log(error.response)
  }
}

onMounted(getVoting)

// =====================
// CREATE
// =====================
const tambahOpsi = () => {
  if (opsi.value.length >= 3) return alert('Maksimal 3 pilihan')
  opsi.value.push('')
}

const hapusOpsi = (index) => {
  if (opsi.value.length <= 2) return alert('Minimal 2 pilihan')
  opsi.value.splice(index, 1)
}

const simpanVoting = async () => {
  if (!judul.value.trim()) {
    alert('Judul voting tidak boleh kosong!')
    return
  }
  const opsiTerisi = opsi.value.filter(o => o.trim() !== '')
  if (opsiTerisi.length < 2) {
    alert('Harus mengisi minimal 2 opsi!')
    return
  }
  try {
    await api.post('/voting', {
      judul: judul.value,
      opsi: opsiTerisi
    })

    judul.value = ''
    opsi.value = ['', '']

    getVoting()
  } catch (error) {
    console.log(error.response)
    alert(error.response?.data?.message || 'Gagal membuat voting!')
  }
}

// =====================
// EDIT
// =====================
const editVoting = (item) => {
  editMode.value = item.id
  editJudul.value = item.judul
  let tempOpsi = item.opsi ? item.opsi.map(o => o.nama_opsi) : []
  while (tempOpsi.length < 2) {
    tempOpsi.push('')
  }
  editOpsi.value = tempOpsi
}

const tambahEditOpsi = () => {
  if (editOpsi.value.length >= 3) return
  editOpsi.value.push('')
}

const hapusEditOpsi = (index) => {
  if (editOpsi.value.length <= 2) return
  editOpsi.value.splice(index, 1)
}

const updateVoting = async (id) => {
  if (!editJudul.value.trim()) {
    alert('Judul voting tidak boleh kosong!')
    return
  }
  const opsiTerisi = editOpsi.value.filter(o => o.trim() !== '')
  if (opsiTerisi.length < 2) {
    alert('Harus mengisi minimal 2 opsi!')
    return
  }
  try {
    await api.put(`/voting/${id}`, {
      judul: editJudul.value,
      opsi: opsiTerisi
    })

    editMode.value = null
    getVoting()
  } catch (error) {
    console.log(error.response)
    alert(error.response?.data?.message || 'Gagal mengubah voting!')
  }
}

// =====================
// DELETE
// =====================
const deleteVoting = async (id) => {
  try {
    await api.delete(`/voting/${id}`)
    getVoting()
  } catch (error) {
    console.log(error.response)
  }
}

</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800 py-10 px-6">
    <div class="max-w-4xl mx-auto space-y-8">
      
      <!-- BACK BUTTON -->
      <button 
        @click="$router.push('/dashboard-admin')" 
        class="flex items-center gap-2 px-5 py-2.5 text-slate-700 bg-white hover:bg-slate-100 hover:text-slate-900 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-200 border border-slate-200 w-fit"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Dashboard
      </button>

      <!-- CREATE -->
      <div class="bg-white p-8 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 transition-all duration-300 hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)]">
        <div class="flex items-center gap-4 mb-8">
          <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold text-slate-900">Buat Voting</h1>
            <p class="text-sm text-slate-500 mt-1">Tambahkan agenda voting baru untuk organisasi Kopasnas.</p>
          </div>
        </div>

        <div class="space-y-6">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Voting</label>
            <input 
              v-model="judul" 
              placeholder="Contoh: Pemilihan Ketua 2026" 
              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200"
            />
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilihan Opsi</label>
            <div class="space-y-3">
              <div v-for="(item, index) in opsi" :key="index" class="flex gap-3">
                <input 
                  v-model="opsi[index]" 
                  :placeholder="`Pilihan ${index + 1}`" 
                  class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200"
                />
                <button 
                  @click="hapusOpsi(index)" 
                  class="px-4 text-red-500 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-xl transition-colors duration-200 flex items-center justify-center"
                  title="Hapus opsi"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <div class="pt-6 flex flex-col sm:flex-row gap-3 border-t border-slate-100 mt-8">
            <button 
              @click="tambahOpsi"
              class="flex items-center justify-center gap-2 px-5 py-3 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl font-medium transition-colors duration-200"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Tambah Opsi
            </button>
            <button 
              @click="simpanVoting"
              class="flex items-center justify-center gap-2 px-8 py-3 bg-[#0f172a] text-white hover:bg-slate-800 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-200 sm:ml-auto"
            >
              Simpan Voting
            </button>
          </div>
        </div>
      </div>

      <!-- LIST -->
      <div class="bg-white p-8 rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 transition-all duration-300 hover:shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)]">
        
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-4">
            <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-slate-900">Voting Aktif</h1>
              <p class="text-sm text-slate-500 mt-1">Daftar agenda voting yang sedang berjalan.</p>
            </div>
          </div>
          <span class="px-4 py-2 bg-emerald-50 text-emerald-700 text-sm font-bold rounded-xl border border-emerald-100">
            {{ totalVoting }} Aktif
          </span>
        </div>

        <div class="space-y-5">
          <div v-for="item in votings" :key="item.id" class="p-6 border border-slate-100 rounded-2xl hover:border-blue-200 hover:shadow-md transition-all duration-300 bg-white">
            
            <!-- EDIT MODE -->
            <div v-if="editMode === item.id" class="space-y-5">
              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Edit Judul</label>
                <input 
                  v-model="editJudul" 
                  class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Edit Opsi</label>
                <div class="space-y-3">
                  <div v-for="(opsiItem, index) in editOpsi" :key="index" class="flex gap-3">
                    <input 
                      v-model="editOpsi[index]" 
                      class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200"
                    />
                    <button 
                      @click="hapusEditOpsi(index)" 
                      class="px-4 text-red-500 hover:bg-red-50 border border-transparent hover:border-red-100 rounded-xl transition-colors duration-200 flex items-center justify-center"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <div class="pt-4 flex flex-wrap gap-3 border-t border-slate-100">
                <button 
                  @click="tambahEditOpsi" 
                  class="px-4 py-2.5 text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl font-medium transition-colors duration-200"
                >
                  + Tambah Opsi
                </button>
                <button 
                  @click="updateVoting(item.id)" 
                  class="px-6 py-2.5 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-xl font-medium shadow-sm transition-colors duration-200 ml-auto"
                >
                  Simpan Perubahan
                </button>
                <button 
                  @click="editMode = null" 
                  class="px-6 py-2.5 text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl font-medium transition-colors duration-200"
                >
                  Batal
                </button>
              </div>
            </div>

            <!-- NORMAL MODE -->
            <div v-else class="flex flex-col sm:flex-row sm:items-start justify-between gap-5">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                  <h3 class="text-xl font-bold text-slate-800">{{ item.judul }}</h3>
                  <span class="px-2.5 py-1 bg-green-100/80 text-green-700 text-xs font-bold rounded-lg flex items-center gap-1.5 border border-green-200/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Aktif
                  </span>
                </div>
                
                <div v-if="item.opsi && item.opsi.length" class="mt-4">
                  <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Pilihan</p>
                  <ul class="space-y-2">
                    <li v-for="opsi in item.opsi" :key="opsi.id" class="flex items-center gap-2.5 text-sm font-medium text-slate-600 bg-slate-50/50 px-3 py-2 rounded-lg border border-slate-100">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      {{ opsi.nama_opsi }}
                    </li>
                  </ul>
                </div>
                <div v-else class="mt-4 text-sm text-amber-700 bg-amber-50 p-4 rounded-xl border border-amber-200/60 flex items-start gap-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
                  <span>Belum ada opsi yang ditambahkan. Silakan klik <strong>Edit</strong> untuk menambahkan daftar pilihan opsi.</span>
                </div>
              </div>

              <div class="flex items-center gap-2 mt-2 sm:mt-0 pt-4 sm:pt-0 border-t sm:border-0 border-slate-100">
                <button 
                  @click="editVoting(item)" 
                  class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 rounded-xl font-medium transition-colors duration-200 text-sm"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit
                </button>
                <button 
                  @click="deleteVoting(item.id)" 
                  class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-medium transition-colors duration-200 text-sm"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                  Hapus
                </button>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>