<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white">⚙️ Konfigurasi Sistem (API)</div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <label>URL Pusat Data</label>
                                <input type="text" name="api_url" class="form-control" value="{{ $apiUrl }}">
                            </div>
                            <div class="col-md-5">
                                <label>API Token (Bearer)</label>
                                <input type="text" name="api_token" class="form-control" value="{{ $apiToken }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button class="btn btn-primary w-100">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">➕ Tambah Buku Baru</div>
                <div class="card-body">
                    <form action="{{ route('admin.buku.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label>Judul Buku</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Kode Buku</label>
                            <input type="text" name="kode_buku" class="form-control" placeholder="Contoh: BK004" required>
                        </div>
                        <div class="mb-3">
                            <label>Pengarang Buku</label>
                            <input type="text" name="pengarang" class="form-control" placeholder="Contoh: Tere Liye" required>
                        </div>
                        <button class="btn btn-success w-100">Simpan Buku</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">📚 Daftar Koleksi Buku</div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Judul Buku</th>
                                <th>Pengarang</th> 
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bukus as $buku)
                            <tr>
                                <td>{{ $buku->kode_buku }}</td>
                                <td>{{ $buku->judul_buku ?? $buku->judul }}</td>
                                <td>{{ $buku->pengarang }}</td> 
                                <td>
                                    <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4 mb-5">
            <div class="card shadow border-0">
                <div class="card-header bg-dark text-white">📋 Riwayat & Status Peminjaman Buku (Mahasiswa / Dosen)</div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>NIM / NIP Peminjam</th>
                                <th>Kode Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                                <th>Aksi Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamans as $pinjam)
                            <tr>
                                <td>{{ $pinjam->nim_peminjam }}</td>
                                <td>{{ $pinjam->kode_buku }}</td>
                                <td>{{ $pinjam->tanggal_pinjam }}</td>
                                <td>
                                    @if(($pinjam->status ?? 'Dipinjam') == 'Dipinjam')
                                        <span class="badge bg-warning text-dark">Dipinjam</span>
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.peminjaman.status', $pinjam->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-secondary">Ubah Status</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>