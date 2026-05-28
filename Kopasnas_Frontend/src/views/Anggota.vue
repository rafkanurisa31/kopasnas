<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api/api'

const router = useRouter()
const anggota = ref([])
const showModal = ref(false)
const editMode = ref(false)
const role = localStorage.getItem('role')

const form = ref({
    id:null,
    nama:'',
    kelas:''
})

const getAnggota = async()=>{
  try{
    const response = await api.get('/anggota')
    anggota.value = response.data.data
  }catch(error){
    console.log(error.response)
  }
}

onMounted(()=>{
  getAnggota()
})

const resetForm = ()=>{
  form.value={
    id:null,
    nama:'',
    kelas:''
  }
  editMode.value=false
}

const tambahAnggota = async()=>{
  if(!form.value.nama || !form.value.kelas) return alert('Data tidak boleh kosong')
  try{
    await api.post('/anggota',{
      nama:form.value.nama,
      kelas:form.value.kelas
    })
    showModal.value=false
    resetForm()
    await getAnggota()
  }catch(error){
    console.log(error.response)
  }
}

const editAnggota=(item)=>{
  editMode.value=true
  showModal.value=true
  form.value={
    id:item.id,
    nama:item.nama,
    kelas:item.kelas
  }
}

const updateAnggota=async()=>{
  if(!form.value.nama || !form.value.kelas) return alert('Data tidak boleh kosong')
  try{
    await api.put(`/anggota/${form.value.id}`,{
      nama:form.value.nama,
      kelas:form.value.kelas
    })
    showModal.value=false
    resetForm()
    await getAnggota()
  }catch(error){
    console.log(error.response)
  }
}

const hapusAnggota=async(id)=>{
  const yakin=confirm('Yakin ingin menghapus anggota?')
  if(!yakin)return
  try{
    await api.delete(`/anggota/${id}`)
    await getAnggota()
  }catch(error){
    console.log(error.response)
  }
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans text-slate-800 py-10 px-6 relative">
    <div class="max-w-5xl mx-auto space-y-8">
      
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
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
          <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm border border-blue-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-bold text-slate-900">Data Anggota</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola direktori keanggotaan organisasi Kopasnas.</p>
          </div>
        </div>

        <button
          v-if="role === 'admin'"
          @click="showModal=true"
          class="flex items-center justify-center gap-2 px-6 py-3 bg-[#0f172a] text-white hover:bg-slate-800 rounded-xl font-medium shadow-sm hover:shadow-md transition-all duration-200"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Anggota
        </button>
      </div>

      <!-- TABLE -->
      <div class="bg-white rounded-3xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider text-xs font-bold border-b border-slate-100">
              <tr>
                <th scope="col" class="px-6 py-5 rounded-tl-3xl">No</th>
                <th scope="col" class="px-6 py-5">Nama Lengkap</th>
                <th scope="col" class="px-6 py-5">Kelas</th>
                <th v-if="role === 'admin'" scope="col" class="px-6 py-5 rounded-tr-3xl text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="!anggota.length">
                <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada data anggota.</td>
              </tr>
              <tr 
                v-for="(item,index) in anggota" 
                :key="item.id"
                class="hover:bg-slate-50/50 transition-colors duration-150"
              >
                <td class="px-6 py-4 font-medium text-slate-500">{{index+1}}</td>
                <td class="px-6 py-4 font-bold text-slate-800">{{item.nama}}</td>
                <td class="px-6 py-4">
                  <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-100">{{item.kelas}}</span>
                </td>
                <td v-if="role === 'admin'" class="px-6 py-4 flex items-center justify-end gap-2">
                  <button
                    class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all duration-200"
                    title="Edit"
                    @click="editAnggota(item)"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button
                    class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200"
                    title="Hapus"
                    @click="hapusAnggota(item.id)"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- MODAL -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm transition-opacity"
    >
      <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
          <h2 class="text-xl font-bold text-slate-800">
            {{editMode ? 'Edit Anggota' : 'Tambah Anggota'}}
          </h2>
          <button @click="showModal=false; resetForm()" class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="p-6 space-y-5">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
            <input
              v-model="form.nama"
              placeholder="Masukkan nama lengkap"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200"
            />
          </div>
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas</label>
            <input
              v-model="form.kelas"
              placeholder="Contoh: XII RPL 1"
              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all duration-200"
            />
          </div>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
          <button
            @click="showModal=false; resetForm()"
            class="px-5 py-2.5 text-sm text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 rounded-xl font-medium transition-colors duration-200"
          >
            Batal
          </button>
          <button
            @click="editMode ? updateAnggota() : tambahAnggota()"
            class="px-6 py-2.5 text-sm bg-blue-600 text-white hover:bg-blue-700 rounded-xl font-medium shadow-sm transition-colors duration-200"
          >
            {{editMode ? 'Simpan Perubahan' : 'Tambahkan'}}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>