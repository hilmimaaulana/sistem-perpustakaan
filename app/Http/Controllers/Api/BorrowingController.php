<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{
    /**
     * Check if student has unreturned books
     * GET /api/mahasiswa/{nim}/borrowing-status
     */
    public function getBorrowingStatus(string $nim): JsonResponse
    {
        // Cari peminjaman yang belum dikembalikan
        // Buku dianggap belum dikembalikan jika:
        // 1. Status NULL (belum diset) atau
        // 2. Status bukan 'dikembalikan'
        $unreturnedBooks = Peminjaman::where('nim_peminjam', $nim)
            ->where(function ($query) {
                $query->whereNull('status')
                      ->orWhere('status', '!=', 'dikembalikan');
            })
            ->with('buku:kode_buku,judul_buku,pengarang')
            ->get();

        $hasUnreturnedBooks = $unreturnedBooks->count() > 0;
        
        // Log untuk debugging
        Log::info("Borrowing check for NIM {$nim}: Found {$unreturnedBooks->count()} unreturned books");

        return response()->json([
            'success' => true,
            'data' => [
                'nim' => $nim,
                'has_unreturned_books' => $hasUnreturnedBooks,
                'unreturned_count' => $unreturnedBooks->count(),
                'memenuhi_syarat' => !$hasUnreturnedBooks,
                'books' => $unreturnedBooks->map(function ($peminjaman) {
                    return [
                        'kode_buku' => $peminjaman->kode_buku,
                        'judul_buku' => $peminjaman->buku->judul_buku ?? 'Unknown',
                        'pengarang' => $peminjaman->buku->pengarang ?? 'Unknown',
                        'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                        'tanggal_kembali' => $peminjaman->tanggal_kembali,
                        'terlambat' => $peminjaman->tanggal_kembali && now()->isAfter($peminjaman->tanggal_kembali),
                    ];
                }),
            ]
        ]);
    }

    /**
     * Get complete borrowing history for a student
     * GET /api/mahasiswa/{nim}/borrowing-history
     */
    public function getBorrowingHistory(string $nim): JsonResponse
    {
        $allBorrowings = Peminjaman::where('nim_peminjam', $nim)
            ->with('buku:kode_buku,judul_buku,pengarang')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get()
            ->map(function ($peminjaman) {
                return [
                    'kode_buku' => $peminjaman->kode_buku,
                    'judul_buku' => $peminjaman->buku->judul_buku ?? 'Unknown',
                    'pengarang' => $peminjaman->buku->pengarang ?? 'Unknown',
                    'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                    'tanggal_kembali' => $peminjaman->tanggal_kembali,
                    'status' => $peminjaman->status ?? 'dipinjam',
                    'dikembalikan' => $peminjaman->status === 'dikembalikan',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'nim' => $nim,
                'total_borrowings' => $allBorrowings->count(),
                'borrowings' => $allBorrowings,
            ]
        ]);
    }
}
