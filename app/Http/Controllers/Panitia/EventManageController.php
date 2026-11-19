<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventManageController extends Controller
{
    public function index(Request $request)
    {
        $events = $request->user()->events()
            ->withStats()
            ->orderByDesc('event_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return view('panitia.events.index', compact('events'));
    }

    public function create(Request $request)
    {
        $event = new Event([
            'organizer' => $request->user()->name,
            'quota'     => 100,
        ]);

        return view('panitia.events.create', [
            'event'      => $event,
            'categories' => array_keys(Event::CATEGORIES),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(isNew: true));

        $event = $request->user()->events()->create($data);

        return redirect()
            ->route('panitia.events.index')
            ->with('success', 'Acara "' . $event->title . '" berhasil dibuat.');
    }

    public function edit(Request $request, Event $event)
    {
        $this->authorizeOwner($request, $event);

        return view('panitia.events.edit', [
            'event'      => $event,
            'categories' => array_keys(Event::CATEGORIES),
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOwner($request, $event);

        $taken = $event->registrations()
            ->whereIn('status', Registration::ACTIVE_STATUSES)
            ->count();

        $data = $request->validate(
            $this->rules(isNew: false, minQuota: max(1, $taken)),
            ['quota.min' => "Kuota tidak boleh di bawah jumlah pendaftar aktif ($taken orang)."]
        );

        $event->update($data);

        return redirect()
            ->route('panitia.events.index')
            ->with('success', 'Perubahan acara "' . $event->title . '" disimpan.');
    }

    public function destroy(Request $request, Event $event)
    {
        $this->authorizeOwner($request, $event);

        $title = $event->title;
        $event->delete(); // pendaftaran ikut terhapus (cascade)

        return redirect()
            ->route('panitia.events.index')
            ->with('success', 'Acara "' . $title . '" dihapus beserta data pendaftarannya.');
    }

    private function rules(bool $isNew, int $minQuota = 1): array
    {
        return [
            'title'       => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:5000'],
            'category'    => ['required', Rule::in(array_keys(Event::CATEGORIES))],
            'location'    => ['required', 'string', 'max:150'],
            'organizer'   => ['required', 'string', 'max:120'],
            'event_date'  => $isNew
                ? ['required', 'date', 'after_or_equal:today']
                : ['required', 'date'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
            'quota'       => ['required', 'integer', 'min:' . $minQuota, 'max:100000'],
        ];
    }

    private function authorizeOwner(Request $request, Event $event): void
    {
        abort_unless($event->user_id === $request->user()->id, 403, 'Acara ini dikelola panitia lain.');
    }
}
