<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat satu Room Grup Utama bawaan sistem sebelum user dibuat
        Room::create(['name' => 'Grup Diskusi Tugas', 'type' => 'group']);

        // 2. Membuat 4 user utama untuk login pengujian
        User::factory()->create(['name' => 'Budi', 'email' => 'budi@gmail.com']);
        User::factory()->create(['name' => 'Siti', 'email' => 'siti@gmail.com']);
        User::factory()->create(['name' => 'Andi', 'email' => 'andi@gmail.com']);
        User::factory()->create(['name' => 'Rose', 'email' => 'rose@gmail.com']);

        // 3. Membuat 100 akun massal acak agar grup chat langsung ramai
        User::factory(100)->create();
    }
}