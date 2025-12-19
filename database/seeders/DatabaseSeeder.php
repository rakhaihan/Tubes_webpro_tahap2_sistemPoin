<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Default users for testing
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin One',
                'username' => 'admin',
                'role' => 'admin',
                'password' => bcrypt('admin123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'guru@example.com'],
            [
                'name' => 'Guru One',
                'username' => 'guru',
                'role' => 'guru',
                'password' => bcrypt('guru123'),
            ]
        );

        // Default siswa user
        User::updateOrCreate(
            ['email' => 'siswa@example.com'],
            [
                'name' => 'Siswa One',
                'username' => 'siswa',
                'role' => 'siswa',
                'password' => bcrypt('siswa123'),
                'nis' => '001',
                'kelas' => 'X A',
                'status' => 'aktif',
            ]
        );
    }
}
