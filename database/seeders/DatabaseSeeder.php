<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Zonasi;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Tambahkan data zona ke database
        DB::table('zones')->insert([
            'name' => 'Kantor Pusat',
            'latitude' => -7.57831,
            'longitude' => 112.44093,
            'radius' => 10
        ]);
        $this->command->info('Zona kerja berhasil ditambahkan!');
    }
}