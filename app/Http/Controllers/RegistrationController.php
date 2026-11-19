<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /** Peserta mendaftar ke sebuah acara. */
    public function store(Request $request, Event $event)
    {
        $user = $request->user();

        if ($event->isFinished()) {
            return back()->with('error', 'Acara ini sudah selesai, pendaftaran ditutup.');
        }

        // Transaksi + kunci baris acara agar kuota tidak terlampaui saat pendaftaran bersamaan.
        [$type, $message] = DB::transaction(function () use ($event, $user) {
            $locked = Event::whereKey($event->id)->lockForUpdate()->first();

            $existing = Registration::where('event_id', $locked->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing && $existing->isActive()) {
                return ['error', 'Kamu sudah terdaftar di acara ini.'];
            }
            if ($existing && $existing->status === Registration::STATUS_REJECTED) {
                return ['error', 'Pendaftaranmu untuk acara ini ditolak panitia. Hubungi penyelenggara jika ada kekeliruan.'];
            }

            $taken = Registration::where('event_id', $locked->id)
                ->whereIn('status', Registration::ACTIVE_STATUSES)
                ->count();

            if ($taken >= $locked->quota) {
                return ['error', 'Kuota acara ini sudah penuh.'];
            }

            if ($existing) { // mendaftar lagi setelah membatalkan
                $existing->update(['status' => Registration::STATUS_PENDING, 'attended_at' => null]);
            } else {
                Registration::create([
                    'event_id' => $locked->id,
                    'user_id'  => $user->id,
                    'code'     => Registration::generateCode(),
                    'status'   => Registration::STATUS_PENDING,
                ]);
            }

            return ['success', 'Pendaftaran terkirim. Tunjukkan kode registrasimu saat presensi.'];
        });

        if ($type === 'success') {
            return redirect()->route('peserta.dashboard')->with('success', $message);
        }

        return back()->with('error', $message);
    }

    /** Peserta membatalkan pendaftarannya. */
    public function cancel(Request $request, Registration $registration)
    {
        abort_unless($registration->user_id === $request->user()->id, 403);

        if ($registration->hasAttended() || $registration->event->isFinished()) {
            return back()->with('error', 'Pendaftaran ini tidak bisa dibatalkan lagi.');
        }
        if (! $registration->isActive()) {
            return back()->with('error', 'Pendaftaran ini sudah tidak aktif.');
        }

        $registration->update(['status' => Registration::STATUS_CANCELLED]);

        return back()->with('success', 'Pendaftaran dibatalkan. Kuota dikembalikan ke acara.');
    }
}
