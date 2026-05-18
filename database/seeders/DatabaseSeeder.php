<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat 4 user utama terlebih dahulu
        $user1 = User::factory()->create(['name' => 'Budi', 'email' => 'budi@gmail.com']);
        $user2 = User::factory()->create(['name' => 'Siti', 'email' => 'siti@gmail.com']);
        $user3 = User::factory()->create(['name' => 'Andi', 'email' => 'andi@gmail.com']);
        $user4 = User::factory()->create(['name' => 'Rose', 'email' => 'rose@gmail.com']);

        $userMasal = User::factory(50)->create();

        $allUsers = User::all();

        $groupRoom = Room::create(['name' => 'Grup Diskusi Tugas', 'type' => 'group']);
        
        $groupRoom->users()->attach($allUsers->pluck('id'));

        $userCount = $allUsers->count();
        for ($i = 0; $i < $userCount; $i++) {
            for ($j = $i + 1; $j < $userCount; $j++) {
                $privateRoom = Room::create(['type' => 'private']);
                $privateRoom->users()->attach([$allUsers[$i]->id, $allUsers[$j]->id]);
            }
        }
    }
}