<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $rooms = auth()->user()->rooms()->get();
        return view('chat', compact('rooms'));
    }

    public function show(Room $room)
    {
        abort_unless($room->users->contains(auth()->id()), 403);

        $rooms = auth()->user()->rooms()->get();
        $messages = $room->messages()->with('user')->oldest()->get();

        return view('chat', compact('room', 'rooms', 'messages'));
    }

    public function store(Request $request, Room $room)
    {
        $request->validate(['message' => 'required']);

        $message = $room->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        broadcast(new MessageSent($message))->toOthers();

        
        return response()->json($message->load('user'));
    }
}
