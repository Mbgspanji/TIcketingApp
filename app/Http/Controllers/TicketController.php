<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(): View
    {
        $tickets = Ticket::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('tickets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high',
        ]);

        $validated['user_id'] = auth()->id() ?? 1;

        Ticket::create($validated);

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil dibuat!');
    }

    public function show(Ticket $ticket): View
    {
        $ticket->load('user');

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket): View
    {
        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket->update($validated);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil diupdate!');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus!');
    }
}
