<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin/test users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rentalin.com',
            'password' => Hash::make('password'),
            'role' => true, // Owner
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Test Owner',
            'email' => 'owner@test.com',
            'password' => Hash::make('password'),
            'role' => true, // Owner
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Test Tenant',
            'email' => 'tenant@test.com',
            'password' => Hash::make('password'),
            'role' => false, // Tenant
            'email_verified_at' => now(),
        ]);

        // Create sample owners
        $owners = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Dewi Sartika',
                'email' => 'dewi.sartika@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Rudi Hermawan',
                'email' => 'rudi.hermawan@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko.prasetyo@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Rina Kusuma',
                'email' => 'rina.kusuma@gmail.com',
                'password' => Hash::make('password'),
                'role' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($owners as $owner) {
            User::create($owner);
        }

        // Create sample tenants
        $tenants = [
            [
                'name' => 'Andi Pratama',
                'email' => 'andi.pratama@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Permata',
                'email' => 'lisa.permata@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Doni Setiawan',
                'email' => 'doni.setiawan@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fitri Handayani',
                'email' => 'fitri.handayani@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Agus Salim',
                'email' => 'agus.salim@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Indira Sari',
                'email' => 'indira.sari@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bayu Nugroho',
                'email' => 'bayu.nugroho@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra.dewi@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar.ramadhan@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Gita Savitri',
                'email' => 'gita.savitri@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ika Putri',
                'email' => 'ika.putri@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Joko Widodo',
                'email' => 'joko.widodo@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Kartika Sari',
                'email' => 'kartika.sari@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lukman Hakim',
                'email' => 'lukman.hakim@gmail.com',
                'password' => Hash::make('password'),
                'role' => false,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($tenants as $tenant) {
            User::create($tenant);
        }

        $this->command->info('Created ' . count($owners) . ' owner users and ' . count($tenants) . ' tenant users.');
        $this->command->info('All users have password: "password"');
    }
}
