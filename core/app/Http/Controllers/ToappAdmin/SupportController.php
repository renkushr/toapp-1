<?php

namespace App\Http\Controllers\ToappAdmin;

use App\Constants\Status;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $tickets = SupportTicket::with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('ticket', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->integer('status')))
            ->latest('last_reply')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('toapp_admin.support.index', [
            'pageTitle' => __('Support Desk'),
            'tickets' => $tickets,
        ]);
    }

    public function show(SupportTicket $ticket)
    {
        $ticket->load('user');
        $messages = SupportMessage::with('admin')
            ->where('support_ticket_id', $ticket->id)
            ->oldest()
            ->get();

        return view('toapp_admin.support.show', [
            'pageTitle' => __('Ticket #:number', ['number' => $ticket->ticket]),
            'ticket' => $ticket,
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        abort_if($ticket->status == Status::TICKET_CLOSE, 422, 'This ticket is already closed.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->admin_id = auth('admin')->id() ?? 0;
        $message->message = $validated['message'];
        $message->save();

        $ticket->status = Status::TICKET_ANSWER;
        $ticket->last_reply = now();
        $ticket->save();

        return back()->with('status', 'Reply sent and ticket marked as answered.');
    }

    public function close(SupportTicket $ticket)
    {
        $ticket->status = Status::TICKET_CLOSE;
        $ticket->save();

        return back()->with('status', 'Ticket closed.');
    }
}
