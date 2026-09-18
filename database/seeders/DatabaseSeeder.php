<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password')],
        );

        $this->call([
            InstitutionSeeder::class,
            UsersSeeder::class,
            UsersComplementSeeder::class,
            CommissionsSeeder::class,
            WorkflowSeeder::class,
            UmaDocumentTemplatesSeeder::class,
            DossiersSeeder::class,
            ReunionsSeeder::class,
            ReclamationsSeeder::class,
        ]);
    }
}
