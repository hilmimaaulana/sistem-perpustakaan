<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan - Premium Tailwind UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2=family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen">

<div class="container mx-auto max-w-6xl px-4 py-6 sm:py-12">
    <div class="text-center mb-8 sm:mb-12">
        <span class="inline-flex items-center justify-center p-3 bg-blue-50 text-blue-600 rounded-2xl mb-3 text-3xl shadow-sm border border-blue-100/50">
            📚
        </span>
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
            Sistem Perpustakaan Kampus
        </h2>
        <p class="text-sm text-slate-500 mt-1">Kelola sirkulasi peminjaman buku terintegrasi API</p>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-start sm:items-center justify-between p-4 text-sm text-emerald-800 rounded-xl bg-emerald-50/80 border border-emerald-200 backdrop-blur-sm shadow-sm" id="alert-box">
            <div class="flex items-center gap-2.5 font-medium">
                <span class="flex-shrink-0 bg-emerald-100 text-emerald-700 w-5 h-5 rounded-full flex items-center justify-center text-xs">✔</span>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="document.getElementById('alert-box').remove()" class="text-emerald-500 hover:text-emerald-700 font-semibold text-xl leading-none ml-3">&times;</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
        
        <div class="lg:col-span-5 w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="bg-blue-600 px-5 py-4 sm:px-6">
                    <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
                        <span>📝</span> Input Peminjaman Baru
                    </h3>
                </div>
                <div class="p-5 sm:p-6">
                    <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label for="nim_peminjam" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">NIM Peminjam</label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <input type="text" id="nim_peminjam" name="nim_peminjam" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all placeholder-slate-400" placeholder="Masukkan NIM Mahasiswa..." required>
                                <button type="button" id="btn-cek-nim" class="w-full sm:w-auto bg-slate-900 text-white px-5 py-2.5 rounded-xl font-medium text-sm hover:bg-slate-800 active:scale-[0.98] transition-all whitespace-nowrap shadow-sm">
                                    Cek NIM
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="nama_mahasiswa" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Nama Mahasiswa (Otomatis API)</label>
                            <input type="text" id="nama_mahasiswa" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 cursor-not-allowed font-medium" placeholder="Akan terisi otomatis via API..." readonly>
                            <div id="status-api" class="text-xs mt-1.5 font-medium transition-all duration-300"></div>
                        </div>

                        <div>
                            <label for="kode_buku" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Pilih Buku yang Dipinjam</label>
                            <select id="kode_buku" name="kode_buku" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all bg-white cursor-pointer" required>
                                <option value="" selected disabled>-- Pilih Koleksi Buku --</option>
                                @foreach($books as $buku)
                                    <option value="{{ $buku->kode_buku }}">{{ $buku->kode_buku }} - {{ $buku->judul_buku }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="tanggal_pinjam" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1.5">Tanggal Pinjam</label>
                            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition-all cursor-pointer" required>
                        </div>

                        <button type="submit" id="btn-submit" class="w-full bg-emerald-600 text-white py-3 rounded-xl font-semibold text-sm hover:bg-emerald-700 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed transition-all shadow-sm active:scale-[0.99]" disabled>
                            Catat Transaksi Peminjaman
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 w-full">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
                <div class="bg-slate-900 px-5 py-4 sm:px-6">
                    <h3 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
                        <span>📋</span> Buku Sedang Dipinjam
                    </h3>
                </div>
                
                <div class="overflow-x-auto min-w-full inline-block align-middle">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/60 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-5 py-3.5 sm:px-6">NIM</th>
                                <th class="px-5 py-3.5 sm:px-6">Kode Buku</th>
                                <th class="px-5 py-3.5 sm:px-6">Tanggal Pinjam</th>
                                <th class="px-5 py-3.5 sm:px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                            @forelse($loans as $loan)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="px-5 py-4 sm:px-6 font-semibold text-slate-900 tracking-wide">{{ $loan->nim_peminjam }}</td>
                                    <td class="px-5 py-4 sm:px-6">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200/50">
                                            {{ $loan->kode_buku }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 sm:px-6 text-slate-500 font-medium">{{ date('d M Y', strtotime($loan->tanggal_pinjam)) }}</td>
                                    <td class="px-5 py-4 sm:px-6 text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/60 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> Dipinjam
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic bg-slate-50/30">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <span class="text-2xl">📭</span>
                                            <p class="text-sm">Belum ada transaksi peminjaman aktif.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('btn-cek-nim').addEventListener('click', function() {
        const nim = document.getElementById('nim_peminjam').value;
        const namaInput = document.getElementById('nama_mahasiswa');
        const statusApi = document.getElementById('status-api');
        const btnSubmit = document.getElementById('btn-submit');

        if (!nim) {
            alert('Silakan isi NIM terlebih dahulu!');
            return;
        }

        statusApi.className = "text-xs mt-1.5 font-medium text-amber-500 flex items-center gap-1";
        statusApi.innerHTML = '⏳ Menghubungkan ke Pusat Data Server...';
        namaInput.value = '';
        btnSubmit.disabled = true;

        fetch(`/check-nim/${nim}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    namaInput.value = data.nama; 
                    statusApi.className = "text-xs mt-1.5 font-medium text-emerald-600 flex items-center gap-1";
                    statusApi.innerHTML = '✔ Data Mahasiswa Terverifikasi (API Sukses)';
                    btnSubmit.disabled = false; 
                } else {
                    statusApi.className = "text-xs mt-1.5 font-medium text-rose-600 flex items-center gap-1";
                    statusApi.innerHTML = `❌ ${data.message}`;
                    btnSubmit.disabled = true;
                }
            })
            .catch(error => {
                statusApi.className = "text-xs mt-1.5 font-medium text-rose-600 flex items-center gap-1";
                statusApi.innerHTML = '⚠️ Gagal tersambung ke API. Pastikan server Pusat Data Angga aktif!';
                btnSubmit.disabled = true;
            });
    });
</script>
</body>
</html>