<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TelegramConfig;
use App\Models\TelegramRecipient;
use App\Models\SecurityAuditLog;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class TelegramController extends Controller
{
    /**
     * Tampilkan Halaman Konfigurasi Telegram
     */
    public function index()
    {
        try {
            $config = TelegramConfig::first();
            $recipients = TelegramRecipient::orderBy('id', 'asc')->get();
        } catch (\Throwable $e) {
            $config = null;
            $recipients = collect([]);
        }

        return view('konfigurasi-telegram', compact('config', 'recipients'));
    }

    /**
     * Simpan Kredensial & Ambang Batas Bot Telegram (POST /telegram/config)
     */
    public function saveConfig(Request $request)
    {
        $validated = $request->validate([
            'bot_token'             => 'nullable|string',
            'bot_username'          => 'nullable|string',
            'alert_threshold_g'     => 'nullable|numeric|min:0.1|max:16.0',
            'status_monitoring'     => 'nullable|boolean',
            'notify_on_scoring'     => 'nullable|boolean',
            'notify_on_damaged'     => 'nullable|boolean',
            'notify_on_disconnect'  => 'nullable|boolean',
            'notify_critical'       => 'nullable|boolean',
            'notify_warning'        => 'nullable|boolean',
            'notify_resolved'       => 'nullable|boolean',
        ]);

        $config = TelegramConfig::firstOrCreate(['id' => 1]);

        if (array_key_exists('bot_token', $validated) && $validated['bot_token'] !== null) {
            $config->bot_token = trim($validated['bot_token']);
        }
        if (array_key_exists('bot_username', $validated) && $validated['bot_username'] !== null) {
            $config->bot_username = trim($validated['bot_username']);
        }
        if (array_key_exists('alert_threshold_g', $validated) && $validated['alert_threshold_g'] !== null) {
            $config->alert_threshold_g = (float) $validated['alert_threshold_g'];
        }

        if ($request->has('status_monitoring')) {
            $config->status_monitoring = $request->boolean('status_monitoring');
        }
        if ($request->has('notify_on_scoring') || $request->has('notify_warning')) {
            $config->notify_on_scoring = $request->has('notify_on_scoring') ? $request->boolean('notify_on_scoring') : $request->boolean('notify_warning');
        }
        if ($request->has('notify_on_damaged') || $request->has('notify_critical')) {
            $config->notify_on_damaged = $request->has('notify_on_damaged') ? $request->boolean('notify_on_damaged') : $request->boolean('notify_critical');
        }
        if ($request->has('notify_on_disconnect')) {
            $config->notify_on_disconnect = $request->boolean('notify_on_disconnect');
        }

        $config->save();

        // Audit Trail
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'CONFIG_TELEGRAM',
                'severity'    => 'WARNING',
                'description' => 'Pembaruan konfigurasi Bot Telegram & ambang batas getaran: ' . $config->alert_threshold_g . ' g.',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi Telegram & parameter alert berhasil disimpan ke database.',
                'data'    => $config,
            ]);
        }

        return redirect()->route('konfigurasi-telegram')->with('success', 'Konfigurasi Telegram berhasil disimpan.');
    }

    /**
     * Tambah Penerima Notifikasi (POST /telegram/recipients)
     */
    public function addRecipient(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'nullable|string|max:100',
            'name'           => 'nullable|string|max:100',
            'chat_id'        => 'required|string|max:50',
            'type'           => 'nullable|string|in:PERSONAL,GROUP,CHANNEL',
            'role'           => 'nullable|string|max:50',
        ]);

        $name = $validated['recipient_name'] ?? $validated['name'] ?? 'Penerima Alert';
        $type = $validated['type'] ?? 'PERSONAL';

        $recipient = TelegramRecipient::create([
            'recipient_name' => $name,
            'chat_id'        => trim($validated['chat_id']),
            'type'           => $type,
            'is_active'      => true,
        ]);

        // Audit Trail
        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'TELEGRAM_RECIPIENT_ADD',
                'severity'    => 'INFO',
                'description' => 'Penambahan penerima alert Telegram: ' . $recipient->recipient_name . ' (Chat ID: ' . $recipient->chat_id . ').',
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'message' => 'Penerima notifikasi ' . $recipient->recipient_name . ' berhasil didaftarkan.',
            'data'    => $recipient,
        ], 201);
    }

    /**
     * Bisukan / Aktifkan Notifikasi Penerima (POST /telegram/recipients/{id}/toggle-mute)
     */
    public function toggleMute(Request $request, $id)
    {
        $recipient = TelegramRecipient::findOrFail($id);
        $recipient->is_active = !$recipient->is_active;
        $recipient->save();

        $statusStr = $recipient->is_active ? 'diaktifkan kembali.' : 'dibisukan (muted).';

        try {
            SecurityAuditLog::create([
                'user_id'     => session('gearsense_user_id'),
                'username'    => session('gearsense_username', 'admin'),
                'event_type'  => 'TELEGRAM_RECIPIENT_TOGGLE',
                'severity'    => 'INFO',
                'description' => 'Status penerima alert ' . $recipient->recipient_name . ' ' . $statusStr,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {}

        return response()->json([
            'success'    => true,
            'is_active'  => $recipient->is_active,
            'new_status' => $recipient->is_active ? 'AKTIF' : 'MUTED',
            'message'    => 'Status penerima ' . $recipient->recipient_name . ' berhasil ' . $statusStr,
        ]);
    }

    /**
     * Hapus Penerima Notifikasi (DELETE /telegram/recipients/{id})
     */
    public function deleteRecipient(Request $request, $id)
    {
        $recipient = TelegramRecipient::findOrFail($id);
        $name = $recipient->recipient_name;
        $recipient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penerima notifikasi ' . $name . ' berhasil dihapus dari sistem.',
        ]);
    }

    /**
     * Uji Kirim Pesan Alert Telegram (POST /telegram/test-dispatch)
     */
    public function testDispatch(Request $request)
    {
        $config = TelegramConfig::first();

        $token = $request->input('bot_token') ?: ($config ? $config->bot_token : null);
        $chatId = $request->input('chat_id');

        $targetRecipient = null;
        if (!$chatId) {
            $targetRecipient = TelegramRecipient::where('is_active', true)->first();
            $chatId = $targetRecipient ? $targetRecipient->chat_id : null;
        } else {
            $targetRecipient = TelegramRecipient::where('chat_id', $chatId)->first();
        }

        $targetName = $targetRecipient ? $targetRecipient->recipient_name : "Chat ID: {$chatId}";

        if (empty($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Bot Token Telegram belum diisi. Masukkan Bot Token API terlebih dahulu.',
            ], 422);
        }

        if (empty($chatId)) {
            return response()->json([
                'success' => false,
                'message' => 'Chat ID penerima belum ditentukan. Daftarkan kontak terlebih dahulu.',
            ], 422);
        }

        $threshold = $config ? $config->alert_threshold_g : 0.85;

        $testText = "🔔 *UJI KONEKSI SISTEM NOTIFIKASI GEARSENSE*\n\n"
                  . "✅ Integrasi Bot Telegram berhasil terhubung ke server Gearsense!\n"
                  . "• *Target Penerima:* {$targetName}\n"
                  . "• *Waktu Pengujian:* " . Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s') . " WIB\n"
                  . "• *Status Bot:* Aktif & Siap Menerima Notifikasi Getaran\n"
                  . "• *Ambang Batas Getaran:* `{$threshold} g`\n\n"
                  . "_Pesan ini dikirimkan secara otomatis oleh Gearsense Monitoring Server._";

        try {
            $response = Http::timeout(6)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $testText,
                'parse_mode' => 'Markdown',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => "Pesan uji coba berhasil terkirim ke {$targetName} ({$chatId})!",
                    'telegram_response' => $response->json(),
                ]);
            } else {
                $errDesc = $response->json()['description'] ?? $response->body();
                if (stripos($errDesc, 'chat not found') !== false) {
                    $errDesc = "Akun {$targetName} belum menekan tombol START di bot Telegram ini.";
                }
                return response()->json([
                    'success' => false,
                    'message' => "Gagal kirim ke {$targetName}: " . $errDesc,
                ], 400);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi Telegram API Gateway: ' . $e->getMessage(),
            ], 500);
        }
    }
}
