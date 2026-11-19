<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /** Daftar pendaftar sebuah acara + pencatatan kehadiran. */
    public function index(Request $request, Event $event)
    {
        $this->authorizeOwner($request, $event);
        $event = Event::withStats()->findOrFail($event->id);

        $status = $request->query('status');
        $keyword = trim((string) $request->query('q', ''));

        $registrations = $event->registrations()
            ->with('user')
            ->when(
                in_array($status, array_keys(Registration::STATUS_LABELS), true),
                fn ($q) => $q->where('status', $status)
            )
            ->when($keyword !== '', function ($q) use ($keyword) {
                $q->where(function ($w) use ($keyword) {
                    $w->where('code', 'like', "%{$keyword}%")
                      ->orWhereHas('user', fn ($u) => $u
                          ->where('name', 'like', "%{$keyword}%")
                          ->orWhere('email', 'like', "%{$keyword}%"));
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $statusCounts = $event->registrations()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('panitia.attendees.index', compact('event', 'registrations', 'statusCounts', 'status', 'keyword'));
    }

    /** Ubah status pendaftaran: konfirmasi atau tolak. */
    public function updateStatus(Request $request, Registration $registration)
    {
        $this->authorizeOwner($request, $registration->event);

        $data = $request->validate([
            'status' => ['required', 'in:confirmed,rejected'],
        ]);

        $registration->status = $data['status'];
        if ($data['status'] === Registration::STATUS_REJECTED) {
            $registration->attended_at = null;
        }
        $registration->save();

        $verb = $data['status'] === Registration::STATUS_CONFIRMED ? 'dikonfirmasi' : 'ditolak';

        return back()->with('success', 'Pendaftaran ' . $registration->user->name . ' ' . $verb . '.');
    }

    public function confirmAll(Request $request, Event $event)
    {
        $this->authorizeOwner($request, $event);

        $count = $event->registrations()
            ->where('status', Registration::STATUS_PENDING)
            ->update(['status' => Registration::STATUS_CONFIRMED]);

        return back()->with(
            $count ? 'success' : 'error',
            $count ? "$count pendaftaran dikonfirmasi." : 'Tidak ada pendaftaran yang menunggu konfirmasi.'
        );
    }

    /** Catat kehadiran dari tombol di tabel. */
    public function checkIn(Request $request, Registration $registration)
    {
        $this->authorizeOwner($request, $registration->event);

        if ($error = $this->checkInError($registration)) {
            return back()->with('error', $error);
        }

        $registration->update(['attended_at' => now()]);

        return back()->with('success', $registration->user->name . ' tercatat hadir.');
    }

    public function undoCheckIn(Request $request, Registration $registration)
    {
        $this->authorizeOwner($request, $registration->event);

        $registration->update(['attended_at' => null]);

        return back()->with('success', 'Kehadiran ' . $registration->user->name . ' dibatalkan.');
    }

    /** Catat kehadiran dengan mengetik / memindai kode registrasi. */
    public function checkInByCode(Request $request, Event $event)
    {
        $this->authorizeOwner($request, $event);

        $data = $request->validate(['code' => ['required', 'string', 'max:20']]);
        $code = strtoupper(trim($data['code']));

        $registration = $event->registrations()->with('user')->where('code', $code)->first();

        if (! $registration) {
            return back()->withInput()->with('error', "Kode $code tidak ditemukan di acara ini.");
        }
        if ($error = $this->checkInError($registration)) {
            return back()->withInput()->with('error', $error);
        }

        $registration->update(['attended_at' => now()]);

        return back()->with('success', $registration->user->name . ' tercatat hadir (' . $code . ').');
    }

    public function export(Request $request, Event $event)
    {
        $this->authorizeOwner($request, $event);

        $rows = $event->registrations()->with('user')->orderBy('created_at')->get();
        $filename = 'peserta-' . str($event->title)->slug() . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8
            fputcsv($out, ['No', 'Nama', 'Email', 'Telepon', 'Kode', 'Status', 'Waktu daftar', 'Hadir pada']);
            foreach ($rows as $i => $r) {
                fputcsv($out, [
                    $i + 1,
                    $r->user->name,
                    $r->user->email,
                    $r->user->phone,
                    $r->code,
                    $r->status_label,
                    $r->created_at->format('Y-m-d H:i'),
                    $r->attended_at?->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Alasan kehadiran belum bisa dicatat, atau null jika boleh. */
    private function checkInError(Registration $registration): ?string
    {
        $name = $registration->user->name;

        if ($registration->status !== Registration::STATUS_CONFIRMED) {
            return "Pendaftaran $name belum dikonfirmasi, jadi kehadiran belum bisa dicatat.";
        }
        if ($registration->hasAttended()) {
            return "$name sudah tercatat hadir pukul " . $registration->attended_at->format('H:i') . '.';
        }
        if ($registration->event->event_date->gt(today())) {
            return 'Acara baru berlangsung ' . $registration->event->event_date->translatedFormat('d F Y') . '. Presensi dibuka pada hari acara.';
        }

        return null;
    }

    private function authorizeOwner(Request $request, Event $event): void
    {
        abort_unless($event->user_id === $request->user()->id, 403, 'Acara ini dikelola panitia lain.');
    }
}
