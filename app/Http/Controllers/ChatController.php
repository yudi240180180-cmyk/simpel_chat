<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $rooms = auth()->user()->rooms;
        $allUsers = User::where('id', '!=', auth()->id())->get();
        return view('chat', compact('rooms', 'allUsers'));
    }

    public function show($id)
    {
        $rooms = auth()->user()->rooms;
        $room = Room::with('users')->findOrFail($id);
        
        if (!$room->users->contains(auth()->id())) {
            abort(403);
        }

        $messages = Message::with('user')->where('room_id', $id)->get();
        $allUsers = User::where('id', '!=', auth()->id())->get();

        return view('chat', compact('rooms', 'room', 'messages', 'allUsers'));
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'user_id' => auth()->id(),
            'room_id' => $id,
            'message' => $request->message
        ]);

        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function createPrivateChat(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $myId = auth()->id();
        $targetId = $request->user_id;

        $existingRoom = Room::where('type', 'private')
            ->whereHas('users', function($q) use ($myId) { $q->where('user_id', $myId); })
            ->whereHas('users', function($q) use ($targetId) { $q->where('user_id', $targetId); })
            ->first();

        if ($existingRoom) {
            return redirect()->route('chat.show', $existingRoom->id);
        }

        $room = Room::create(['type' => 'private']);
        $room->users()->attach([$myId, $targetId]);

        return redirect()->route('chat.show', $room->id);
    }

    public function createGroupChat(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'members' => 'required|array|min:1'
        ]);

        $room = Room::create([
            'name' => $request->group_name,
            'type' => 'group'
        ]);

        $members = $request->members;
        $members[] = auth()->id();

        $room->users()->attach($members);

        return redirect()->route('chat.show', $room->id);
    }
}