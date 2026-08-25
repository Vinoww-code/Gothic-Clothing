<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    /**
     * Securely stream customer identity documents (KTP / Selfie)
     * Only accessible by authorized Admin or the customer who placed the order.
     */
    public function serveOrderDocument(Order $order, string $type)
    {
        $user = Auth::user();

        // 1. Authorization check
        if (!$user || ($user->role !== 'admin' && $user->id !== $order->user_id)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat dokumen ini.');
        }

        // 2. Determine file path
        if ($type === 'ktp') {
            $relativePath = $order->id_card_path;
        } elseif ($type === 'selfie') {
            $relativePath = $order->selfie_path;
        } else {
            abort(404, 'Tipe dokumen tidak valid.');
        }

        if (!$relativePath) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        // 3. Locate file across private (local) or public disk (backward compatibility)
        if (Storage::disk('local')->exists($relativePath)) {
            return Storage::disk('local')->response($relativePath);
        }

        if (Storage::disk('public')->exists($relativePath)) {
            return Storage::disk('public')->response($relativePath);
        }

        abort(404, 'Berkas fisik dokumen tidak ditemukan di server.');
    }
}
