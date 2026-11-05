<?php

namespace Database\Seeders;

use App\Models\{Album, Genre, Song, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- AKUN (password semuanya: "password") ---
        User::create(['name' => 'Admin',  'email' => 'admin@store.test', 'password' => Hash::make('password'), 'role' => 'admin']);
        User::create(['name' => 'User A', 'email' => 'usera@store.test', 'password' => Hash::make('password'), 'role' => 'user']);
        User::create(['name' => 'User B', 'email' => 'userb@store.test', 'password' => Hash::make('password'), 'role' => 'user']);

        // --- GENRE --- (GANTI di sini kalau mau genre lain)
        $genres = collect(['Melancholic', 'Sad', 'Heartbreaking', 'Bittersweet', 'Hopeful', 'Despair', 'Lonely', 'Anxious', 'Euphoric', 'Peaceful', 'Angsty', 'Frustration'])->map(
            fn ($name) => Genre::create(['name' => $name, 'slug' => Str::slug($name)])
        );

        // --- ALBUM --- (GANTI di sini judul & tahun album)
        $albums = collect([
            ['title' => 'It\'s Raining After All',   'release_year' => 2020],
            ['title' => 'I\'ll Put You in Misery', 'release_year' => 2021],
            ['title' => 'Under Mentality',  'release_year' => 2023],
        ])->map(fn ($a) => Album::create($a));

        // --- 20 LAGU CONTOH --- (GANTI di sini judul lagu)
        $titles = [
            'It\'s Raining After All', 'Under The Summer Breeze', 'When the Morning Glory Falls', 'I\'m Getting on the Bus to the Other World, See Ya!', 'Perhaps I\'ll Be Able to Become the Sun',
            'Compare Child', 'Goodbye to Rock You', 'Even Tears Withered', 'Trapped in The Past', 'What If This Isn\'t a Slave?',
            'Loser Girl', 'To Bask in The Rain', 'Faithful dog "Hachi"', 'Hide and Seek Alone', 'Being low as dirt, taking what\'s important from me',
            'If There Was An Endpoint', 'Under Kids', 'Under Heroin', 'I Hope You Can Be an Adult Someday', 'What Sort of Ending You Wishing For?',
        ];
        $prices = [272, 249, 257, 220]; // GANTI di sini: variasi harga (Rupiah)

        foreach ($titles as $i => $title) {
            Song::create([
                'album_id'         => $albums[$i % 3]->id,'genre_id' => $genres[$i % $genres->count()]->id,
                'title'            => $title,
                'price'            => $prices[$i % 4],
                'duration_seconds' => rand(150, 300),
                'track_number'     => intdiv($i, 3) + 1,
                'is_active'        => true,
            ]);
        }
    }
}