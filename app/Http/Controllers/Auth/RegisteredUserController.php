<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // ===============================================================
        // LOGIKA OTOMATIS GABUNG GRUP & BUAT PRIVATE CHAT (USER REGISTER)
        // ===============================================================
        
        // 1. Otomatis masukkan user baru ini ke Grup Diskusi Tugas (Room ID: 1)
        $groupRoom = \App\Models\Room::where('type', 'group')->first();
        if ($groupRoom) {
            $groupRoom->users()->attach($user->id);
        }

        // 2. Otomatis buatkan Room Privat dengan SEMUA user lain yang sudah terdaftar
        $allUsers = User::where('id', '!=', $user->id)->get();
        foreach ($allUsers as $otherUser) {
            // Buat room privat baru
            $privateRoom = \App\Models\Room::create(['type' => 'private']);
            // Hubungkan user baru dengan user lama tersebut
            $privateRoom->users()->attach([$user->id, $otherUser->id]);
        }
        // ===============================================================

        Auth::login($user);

        // UBAH DI SINI: Langsung lempar ke halaman index chat setelah sukses daftar
        return redirect(route('chat.index', absolute: false));
    }
}