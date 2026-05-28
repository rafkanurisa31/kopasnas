<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="min-h-screen bg-slate-50 font-sans flex flex-col justify-between text-left">
    <!-- Navbar -->
    <nav class="flex justify-between items-center px-8 py-5 bg-[#0f172a] text-white shadow-md">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.475 3.475 0 011.669 4.037 3.47 3.47 0 00.515 2.066 3.42 3.42 0 010 4.438 3.47 3.47 0 00-.515 2.066 3.475 3.475 0 01-1.669 4.037 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.475 3.475 0 01-1.669-4.037 3.47 3.47 0 00-.515-2.066 3.42 3.42 0 010-4.438 3.47 3.47 0 00.515-2.066 3.475 3.475 0 011.669-4.037z" />
        </svg>
        <span class="text-xl font-bold tracking-wider text-white">KOPASNAS</span>
      </div>

      <div class="flex items-center gap-6">
        <RouterLink to="/" class="text-slate-300 hover:text-white transition-colors font-medium text-sm">Beranda</RouterLink>
        <RouterLink to="/login">
          <button class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-medium rounded-xl transition-all duration-200 text-sm shadow-sm hover:shadow">
            Login
          </button>
        </RouterLink>
      </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative overflow-hidden bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#0f172a] text-white py-20 px-8 text-center">
      <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.1),transparent_50%)]"></div>
      <div class="relative max-w-3xl mx-auto z-10">
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/20 mb-6 uppercase tracking-wider">
          Paskibra SMK Tunas Harapan Pati
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6 leading-tight text-white">
          Selamat Datang di <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">KOPASNAS</span>
        </h1>
        <p class="text-slate-300 text-base md:text-lg mb-8 leading-relaxed font-light">
          Platform organisasi digital terpadu untuk efisiensi administrasi, pengelolaan anggota, dan sistem voting yang transparan.
        </p>
        <div class="flex justify-center gap-4">
          <RouterLink to="/login">
            <button class="px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/25 hover:shadow-blue-500/45 transition-all duration-300 transform hover:-translate-y-0.5">
              Mulai Sekarang
            </button>
          </RouterLink>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow max-w-5xl mx-auto w-full px-6 py-12 space-y-12">
      
      <!-- Profil KOPASNAS -->
      <section class="bg-white rounded-2xl p-8 md:p-10 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
          <div class="md:col-span-1 flex justify-center">
            <div class="w-32 h-32 rounded-3xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-inner">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
          </div>
          <div class="md:col-span-2 space-y-4">
            <h2 class="text-2xl font-bold text-[#0f172a]">Tentang KOPASNAS</h2>
            <p class="text-slate-600 leading-relaxed text-sm md:text-base">
              KOPASNAS adalah organisasi paskibra SMK Tunas Harapan Pati yang berfokus pada pembentukan karakter, kedisiplinan, kepemimpinan, dan jiwa nasionalisme. Melalui kegiatan baris-berbaris dan pembinaan organisasi, KOPASNAS menjadi wadah bagi siswa untuk berkembang menjadi pribadi yang tangguh, bertanggung jawab, kompak, dan berprestasi.
            </p>
          </div>
        </div>
      </section>

      <!-- Visi & Misi Grid -->
      <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Visi Card -->
        <div class="bg-gradient-to-br from-[#0f172a] to-[#1e293b] text-white rounded-2xl p-8 shadow-sm flex flex-col justify-between border border-slate-800 transition-all duration-300 hover:shadow-md">
          <div class="space-y-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-white tracking-wide">Visi</h2>
            <p class="text-slate-300 text-base md:text-lg leading-relaxed italic font-light">
              "Menjadikan KOPASNAS sebagai organisasi paskibra yang disiplin, berkarakter, nasionalis, dan berprestasi."
            </p>
          </div>
          <div class="mt-8 pt-4 border-t border-slate-850 text-xs text-slate-400 tracking-wider uppercase font-semibold">
            Visi Utama KOPASNAS
          </div>
        </div>

        <!-- Misi Card -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md flex flex-col justify-between">
          <div class="space-y-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-50 text-blue-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
            </div>
            <h2 class="text-2xl font-bold text-[#0f172a]">Misi</h2>
            
            <ul class="space-y-3.5 text-left">
              <li class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-slate-600 text-sm md:text-base">Menanamkan sikap disiplin, tanggung jawab, dan kepemimpinan</span>
              </li>
              <li class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-slate-600 text-sm md:text-base">Meningkatkan semangat nasionalisme dan cinta tanah air</span>
              </li>
              <li class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-slate-600 text-sm md:text-base">Membentuk anggota yang mandiri, kompak, dan solid</span>
              </li>
              <li class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-slate-600 text-sm md:text-base">Mengembangkan kemampuan paskibra dan organisasi</span>
              </li>
              <li class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-slate-600 text-sm md:text-base">Mendukung lingkungan sekolah yang tertib dan berprestasi</span>
              </li>
            </ul>
          </div>
        </div>

      </section>

    </main>

    <!-- Footer -->
    <footer class="bg-[#0f172a] text-slate-400 py-6 px-8 border-t border-slate-800 text-center text-xs">
      <p>&copy; 2026 KOPASNAS - Paskibra SMK Tunas Harapan Pati. All rights reserved.</p>
    </footer>

  </div>
</template>

<style scoped>
</style>