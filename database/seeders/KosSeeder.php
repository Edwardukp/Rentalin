<?php

namespace Database\Seeders;

use App\Models\Kos;
use App\Models\User;
use Illuminate\Database\Seeder;

class KosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all owner users
        $owners = User::where('role', true)->get();

        if ($owners->isEmpty()) {
            $this->command->error('No owner users found. Please run UserSeeder first.');
            return;
        }

        $kosData = [
            [
                'nama_kos' => 'Kos Melati Indah',
                'alamat' => 'Jl. Melati No. 15, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12110',
                'harga' => 1500000.00,
                'fasilitas' => 'Kamar ber-AC, WiFi gratis, kamar mandi dalam, lemari pakaian, meja belajar, kursi, kasur spring bed, bantal guling, sprei. Fasilitas umum: dapur bersama, ruang tamu, area parkir motor, CCTV 24 jam, security, laundry.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.2297,106.7957',
            ],
            [
                'nama_kos' => 'Kos Mawar Residence',
                'alamat' => 'Jl. Mawar Raya No. 8, Depok, Jawa Barat 16424',
                'harga' => 1200000,
                'fasilitas' => 'Kamar ber-AC, WiFi unlimited, kamar mandi dalam, lemari 2 pintu, meja kerja, kursi ergonomis, kasur queen size. Fasilitas bersama: dapur lengkap, ruang santai, parkir motor dan mobil, keamanan 24 jam.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.4025,106.7942',
            ],
            [
                'nama_kos' => 'Kos Anggrek Modern',
                'alamat' => 'Jl. Anggrek No. 22, Tangerang Selatan, Banten 15418',
                'harga' => 1800000,
                'fasilitas' => 'Kamar full furnished dengan AC, WiFi fiber, kamar mandi dalam dengan water heater, lemari built-in, meja belajar, kursi kantor, kasur orthopedic. Fasilitas premium: gym, rooftop garden, co-working space, mini market.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.2615,106.6894',
            ],
            [
                'nama_kos' => 'Kos Dahlia Syariah',
                'alamat' => 'Jl. Dahlia No. 5, Bekasi Timur, Jawa Barat 17113',
                'harga' => 1000000,
                'fasilitas' => 'Kos khusus putri, kamar ber-AC, WiFi, kamar mandi dalam, lemari pakaian, meja belajar. Fasilitas: dapur bersama, ruang sholat, area jemuran, parkir motor, keamanan ketat, jam malam 22:00.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.2349,107.0095',
            ],
            [
                'nama_kos' => 'Kos Tulip Executive',
                'alamat' => 'Jl. Tulip Garden No. 12, Bandung, Jawa Barat 40115',
                'harga' => 2200000,
                'fasilitas' => 'Kamar executive dengan AC inverter, WiFi dedicated, kamar mandi marble, walk-in closet, work station, smart TV 43", mini fridge. Premium amenities: swimming pool, fitness center, business lounge, concierge service.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.9175,107.6191',
            ],
            [
                'nama_kos' => 'Kos Kenanga Budget',
                'alamat' => 'Jl. Kenanga No. 18, Yogyakarta, DIY 55281',
                'harga' => 800000,
                'fasilitas' => 'Kamar sederhana dengan kipas angin, WiFi, kamar mandi luar, lemari kecil, meja lipat. Fasilitas: dapur bersama, area parkir motor, keamanan siang malam.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-7.7956,110.3695',
            ],
            [
                'nama_kos' => 'Kos Seruni Deluxe',
                'alamat' => 'Jl. Seruni Indah No. 7, Surabaya, Jawa Timur 60119',
                'harga' => 1600000,
                'fasilitas' => 'Kamar deluxe ber-AC, WiFi high speed, kamar mandi dalam dengan bathtub, lemari sliding, meja kerja L-shape, kursi gaming, kasur memory foam. Fasilitas: cafe, laundry express, shuttle service.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-7.2504,112.7688',
            ],
            [
                'nama_kos' => 'Kos Flamboyan Student',
                'alamat' => 'Jl. Flamboyan No. 25, dekat Universitas Indonesia, Depok, Jawa Barat 16424',
                'harga' => 1300000,
                'fasilitas' => 'Kos khusus mahasiswa, kamar ber-AC, WiFi unlimited, kamar mandi dalam, lemari buku, meja belajar besar, kursi ergonomis. Fasilitas: perpustakaan mini, ruang diskusi, pantry, area study group.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.3613,106.8227',
            ],
            [
                'nama_kos' => 'Kos Bougenville Cozy',
                'alamat' => 'Jl. Bougenville No. 9, Malang, Jawa Timur 65145',
                'harga' => 1100000,
                'fasilitas' => 'Kamar cozy dengan AC, WiFi, kamar mandi dalam, lemari vintage, meja kayu solid, kursi santai, kasur empuk. Fasilitas: taman mini, gazebo, dapur outdoor, area BBQ, parkir luas.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-7.9666,112.6326',
            ],
            [
                'nama_kos' => 'Kos Cempaka Premium',
                'alamat' => 'Jl. Cempaka Putih No. 14, Jakarta Pusat, DKI Jakarta 10510',
                'harga' => 2500000,
                'fasilitas' => 'Kamar premium suite dengan AC central, WiFi fiber 100Mbps, kamar mandi luxury dengan jacuzzi, walk-in wardrobe, executive desk, ergonomic chair, king size bed, smart home system. Luxury amenities: spa, restaurant, valet parking.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.1701,106.8650',
            ],
            [
                'nama_kos' => 'Kos Sakura Minimalis',
                'alamat' => 'Jl. Sakura No. 11, Semarang, Jawa Tengah 50241',
                'harga' => 950000,
                'fasilitas' => 'Kamar minimalis modern dengan AC, WiFi, kamar mandi dalam, lemari minimalis, meja lipat, kursi plastik berkualitas, kasur single. Fasilitas: common area, mini kitchen, area parkir motor.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=-6.9667,110.4167',
            ],
            [
                'nama_kos' => 'Kos Teratai Eksklusif',
                'alamat' => 'Jl. Teratai Mas No. 3, Medan, Sumatera Utara 20112',
                'harga' => 1700000,
                'fasilitas' => 'Kamar eksklusif ber-AC inverter, WiFi dedicated, kamar mandi marble dengan rain shower, built-in wardrobe, executive workstation, massage chair, premium mattress. Exclusive facilities: private dining, butler service, airport transfer.',
                'foto' => null,
                'status' => true,
                'google_maps_url' => 'https://www.google.com/maps?q=3.5952,98.6722',
            ],
        ];

        foreach ($kosData as $index => $data) {
            // Assign to different owners in rotation
            $owner = $owners[$index % $owners->count()];
            
            Kos::create([
                'pemilik_id' => $owner->id,
                'nama_kos' => $data['nama_kos'],
                'alamat' => $data['alamat'],
                'harga' => $data['harga'],
                'fasilitas' => $data['fasilitas'],
                'foto' => $data['foto'],
                'status' => $data['status'],
                'google_maps_url' => $data['google_maps_url'],
            ]);
        }

        $this->command->info('Created ' . count($kosData) . ' kos properties.');
        $this->command->info('Properties are distributed among ' . $owners->count() . ' owners.');
    }
}
