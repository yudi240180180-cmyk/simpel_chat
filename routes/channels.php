<?php

use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
    
    $isMember = Room::where('id', $roomId)->whereHas('users', function($query) use ($user) {
        $query->where('user_id', $user->id);
    })->exists();

    
    return $isMember ? ['id' => $user->id, 'name' => $user->name] : false;
});
