<?php

namespace Database\Seeders;

use App\Models\Concert;
use App\Models\User;
use Illuminate\Database\Seeder;

class ConcertSeeder extends Seeder
{
    public function run(): void
    {
        $eo = User::where('email', 'eo@eventra.com')->firstOrFail();

        $data = [
            ['Nada Senja Festival', 'nada-senja-festival', 'Pop', 'Surabaya', 'Hindia, Nadin Amizah, Sheila on 7', 'published', 30, [['Festival', 250000, 200, 5], ['VIP', 600000, 50, 4]]],
            ['Malam Akustik Kota', 'malam-akustik-kota', 'Indie', 'Malang', 'Fourtwnty, Banda Neira', 'published', 45, [['Reguler', 150000, 100, 5], ['VVIP', 450000, 20, 2]]],
            ['Rock the Harbour', 'rock-the-harbour', 'Rock', 'Jakarta', 'Burgerkill, Seringai, Jikustik', 'published', 60, [['Tribun', 200000, 300, 6], ['Festival', 350000, 150, 5]]],
            ['Dangdut Night Bangkalan', 'dangdut-night-bangkalan', 'Dangdut', 'Surabaya', 'Artis dangdut Jawa Timur', 'published', 20, [['Reguler', 100000, 150, 5]]],
            ['Konser Belum Tayang', 'konser-belum-tayang', 'Pop', 'Bandung', 'Line-up menyusul', 'draft', 90, [['Festival', 300000, 100, 5]]],
        ];

        foreach ($data as [$title, $slug, $genre, $city, $lineup, $status, $days, $types]) {
            // updateOrCreate: menjalankan seeder ulang akan mengisi genre pada konser yang sudah ada
            $concert = Concert::updateOrCreate(['slug' => $slug], [
                'user_id'     => $eo->id,
                'title'       => $title,
                'genre'       => $genre,
                'lineup'      => $lineup,
                'description' => "Nikmati {$title} dengan penampilan langsung dari para musisi terbaik. Pintu dibuka dua jam sebelum acara.",
                'venue'       => 'Gelora Eventra Arena',
                'city'        => $city,
                'event_date'  => now()->addDays($days)->setTime(19, 0),
                'status'      => $status,
            ]);

            if ($concert->ticketTypes()->doesntExist()) {
                foreach ($types as [$name, $price, $quota, $max]) {
                    $concert->ticketTypes()->create([
                        'name' => $name, 'price' => $price, 'quota' => $quota, 'max_per_order' => $max,
                    ]);
                }
            }
        }
    }
}
