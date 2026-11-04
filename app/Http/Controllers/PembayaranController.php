<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index($id)
    {
        $booking = Booking::with('ruangan')->findOrFail($id);

        // Cek status konfirmasi booking admin
        if ($booking->status === 'menunggu_konfirmasi_admin') {
            return redirect()->back()->with('error', 'Booking kamu masih menunggu konfirmasi dari admin.');
        }

        if (in_array($booking->status, ['dibayar', 'selesai', 'dibatalkan'])) {
            return redirect()->route('status.bayar', $booking->id)
                ->with('error', 'Booking ini sudah tidak dalam tahap pembayaran.');
        }

        return view('pembayaran', compact('booking'));
    }

    /**
     * Memproses/menyimpan pembayaran dari form pembayaran.blade.php
     */
    public function bayar(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status === 'menunggu_konfirmasi_admin') {
            return redirect()->route('status.bayar', $booking->id)
                ->with('error', 'Booking belum dikonfirmasi admin.');
        }

        $validated = $request->validate([
            'metode'           => 'required|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            // Menyimpan bukti pembayaran ke disk public agar sinkron dengan storage:link
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');
        }

        // Simpan atau update data pembayaran jika sudah pernah diunggah
        Pembayaran::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'metode' => $validated['metode'],
                'bukti'  => $buktiPath,
                'jumlah' => $booking->harga_total,
                'status' => 'menunggu_verifikasi',
            ]
        );

        $booking->update(['status' => 'menunggu_verifikasi']);

        return redirect()->route('status.bayar', $booking->id)
            ->with('success', 'Pembayaran berhasil dikirim, menunggu verifikasi admin.');
    }

    /**
     * Halaman status pembayaran
     */
    public function status($id)
    {
        $booking = Booking::with(['ruangan', 'pembayaran'])->findOrFail($id);

        return view('statusbayar', compact('booking'));
    }
}