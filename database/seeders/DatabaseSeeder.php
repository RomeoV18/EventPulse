<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hindari membuat akun dan acara demo berulang setiap container restart.
        if (User::query()->exists()) {
            return;
        }

        $password = Hash::make('password'); // semua akun demo: password

        /* ---------- Panitia ---------- */
        $hima = User::create([
            'name' => 'HIMA Teknologi Informasi', 'email' => 'panitia@eventpulse.test',
            'password' => $password, 'role' => User::ROLE_PANITIA, 'phone' => '081200000001',
        ]);
        $bem = User::create([
            'name' => 'BEM Fakultas Teknik', 'email' => 'panitia2@eventpulse.test',
            'password' => $password, 'role' => User::ROLE_PANITIA, 'phone' => '081200000002',
        ]);

        /* ---------- Peserta ---------- */
        $demo = User::create([
            'name' => 'Peserta Demo', 'email' => 'peserta@eventpulse.test',
            'password' => $password, 'role' => User::ROLE_PESERTA, 'phone' => '081300000001',
        ]);

        $names = [
            'Aditya Pratama', 'Bunga Lestari', 'Cahyo Nugroho', 'Dewi Anggraini', 'Eko Prasetyo', 'Fitri Handayani',
            'Galih Saputra', 'Hana Permata', 'Irfan Maulana', 'Jihan Safitri', 'Kevin Wijaya', 'Laras Ayu',
            'Muhammad Rizky', 'Nadia Putri', 'Oka Wiguna', 'Putri Amelia', 'Qori Rahman', 'Raka Firmansyah',
            'Salsa Nabila', 'Tegar Ramadhan', 'Utami Sari', 'Vino Hermawan', 'Wulan Cahyani', 'Yoga Aditama',
        ];
        $pool = collect($names)->map(fn ($name, $i) => User::create([
            'name'     => $name,
            'email'    => 'peserta' . ($i + 2) . '@eventpulse.test',
            'password' => $password,
            'role'     => User::ROLE_PESERTA,
            'phone'    => '0813' . str_pad((string) ($i + 2), 8, '0', STR_PAD_LEFT),
        ]));

        /* ---------- Acara ----------
         * day  : selisih hari dari hari ini (negatif = sudah lewat)
         * fill : jumlah pendaftar acak dari peserta dummy
         * demo : status pendaftaran akun peserta demo (null = tidak mendaftar)
         */
        $specs = [
            [
                'owner' => $hima, 'title' => 'Seminar Karier: Jalur Menjadi Data Engineer', 'category' => 'Seminar',
                'description' => "Praktisi data dari tiga perusahaan teknologi membahas peta karier Data Engineer, Cloud Data Engineer, dan Analytics Engineer.\n\nMateri mencakup keterampilan yang dicari rekruter, contoh portofolio yang kuat, serta sesi tanya jawab terbuka.",
                'location' => 'Aula Gedung Rektorat', 'day' => -21, 'start' => '09:00', 'end' => '12:00', 'quota' => 120, 'fill' => 20, 'demo' => 'attended',
            ],
            [
                'owner' => $hima, 'title' => 'Webinar: Pengantar Data Warehouse di Cloud', 'category' => 'Webinar',
                'description' => "Mengenal konsep data warehouse modern, perbedaan ETL dan ELT, serta demo membangun pipeline sederhana di cloud.\n\nTautan Zoom dikirim melalui email setelah pendaftaran dikonfirmasi.",
                'location' => 'Online (Zoom Meeting)', 'day' => -9, 'start' => '19:00', 'end' => '21:00', 'quota' => 200, 'fill' => 22, 'demo' => 'absent',
            ],
            [
                'owner' => $bem, 'title' => 'Lomba Esai Ilmiah Mahasiswa Teknik', 'category' => 'Kompetisi',
                'description' => 'Kompetisi esai ilmiah tingkat universitas bertema inovasi teknologi untuk masyarakat. Babak penyisihan, final presentasi, dan penyerahan hadiah dilakukan di hari yang sama.',
                'location' => 'Ruang Seminar B, Fakultas Teknik', 'day' => -4, 'start' => '08:00', 'end' => '15:00', 'quota' => 40, 'fill' => 18, 'demo' => null,
            ],
            [
                'owner' => $hima, 'title' => 'Workshop SQL untuk Analisis Data', 'category' => 'Workshop',
                'description' => "Praktik langsung menulis query SQL: join, agregasi, window function, hingga membuat view untuk laporan.\n\nBawa laptop dengan MySQL atau MariaDB terpasang (XAMPP sudah cukup).",
                'location' => 'Lab Komputer 3', 'day' => 0, 'start' => '13:00', 'end' => '16:30', 'quota' => 30, 'fill' => 16, 'demo' => 'confirmed',
            ],
            [
                'owner' => $hima, 'title' => 'Workshop Dashboard Interaktif dengan Power BI', 'category' => 'Workshop',
                'description' => 'Belajar memodelkan data, membuat ukuran (measure) DAX dasar, dan menyusun dashboard yang bisa dibagikan. Cocok untuk pemula yang ingin portofolio analitik.',
                'location' => 'Lab Komputer 1', 'day' => 5, 'start' => '09:00', 'end' => '15:00', 'quota' => 25, 'fill' => 22, 'demo' => 'confirmed',
            ],
            [
                'owner' => $hima, 'title' => 'Seminar Nasional Keamanan Data & Privasi', 'category' => 'Seminar',
                'description' => 'Membahas perlindungan data pribadi, praktik keamanan basis data, dan peran mahasiswa TI dalam menjaga privasi pengguna. Menghadirkan pembicara dari industri dan regulator.',
                'location' => 'Auditorium Fakultas Teknik', 'day' => 9, 'start' => '08:30', 'end' => '12:30', 'quota' => 150, 'fill' => 14, 'demo' => 'pending',
            ],
            [
                'owner' => $bem, 'title' => 'Webinar: Membangun Portofolio Data Engineer', 'category' => 'Webinar',
                'description' => 'Strategi memilih proyek, mendokumentasikan pipeline di GitHub, dan menceritakan dampak proyek kepada rekruter. Sesi review portofolio singkat untuk 5 peserta terpilih.',
                'location' => 'Online (Google Meet)', 'day' => 12, 'start' => '19:30', 'end' => '21:00', 'quota' => 250, 'fill' => 12, 'demo' => null,
            ],
            [
                'owner' => $bem, 'title' => 'Kompetisi Hackathon Kampus: Data untuk Kota', 'category' => 'Kompetisi',
                'description' => "Tim 3–4 orang menyelesaikan tantangan analitik dengan data terbuka kota selama 24 jam. Juri menilai kedalaman analisis, kualitas visualisasi, dan kelayakan solusi.\n\nHadiah total Rp15.000.000.",
                'location' => 'Co-Working Space Kampus', 'day' => 18, 'start' => '08:00', 'end' => '17:00', 'quota' => 10, 'fill' => 10, 'demo' => null,
            ],
            [
                'owner' => $bem, 'title' => 'Open Recruitment Panitia Dies Natalis', 'category' => 'Organisasi',
                'description' => 'Sesi pengenalan divisi, wawancara singkat, dan pembagian tugas awal untuk kepanitiaan Dies Natalis fakultas. Terbuka untuk semua angkatan.',
                'location' => 'Sekretariat BEM', 'day' => 23, 'start' => '15:30', 'end' => '17:30', 'quota' => 35, 'fill' => 11, 'demo' => null,
            ],
            [
                'owner' => $hima, 'title' => 'Workshop Laravel: Membangun Aplikasi Web Pertamamu', 'category' => 'Workshop',
                'description' => 'Dari instalasi Composer hingga CRUD lengkap dengan autentikasi dan validasi. Peserta pulang dengan aplikasi kecil yang berjalan di XAMPP.',
                'location' => 'Lab Komputer 2', 'day' => 30, 'start' => '09:00', 'end' => '16:00', 'quota' => 30, 'fill' => 6, 'demo' => null,
            ],
        ];

        foreach ($specs as $spec) {
            $date = today()->addDays($spec['day']);

            $event = Event::create([
                'user_id'     => $spec['owner']->id,
                'title'       => $spec['title'],
                'description' => $spec['description'],
                'category'    => $spec['category'],
                'location'    => $spec['location'],
                'organizer'   => $spec['owner']->name,
                'event_date'  => $date,
                'start_time'  => $spec['start'],
                'end_time'    => $spec['end'],
                'quota'       => $spec['quota'],
            ]);

            $finished = $spec['day'] < 0;
            $today = $spec['day'] === 0;

            // Pendaftar acak
            foreach ($pool->shuffle()->take($spec['fill']) as $i => $user) {
                $roll = random_int(1, 100);

                if ($finished) {
                    $status = $roll <= 88 ? Registration::STATUS_CONFIRMED
                        : ($roll <= 94 ? Registration::STATUS_CANCELLED : Registration::STATUS_REJECTED);
                    $attended = $status === Registration::STATUS_CONFIRMED && random_int(1, 100) <= 78;
                } elseif ($today) {
                    $status = Registration::STATUS_CONFIRMED;
                    $attended = $i < 5; // sebagian sudah check-in
                } else {
                    $status = $roll <= 60 ? Registration::STATUS_CONFIRMED : Registration::STATUS_PENDING;
                    $attended = false;
                }

                Registration::create([
                    'event_id'    => $event->id,
                    'user_id'     => $user->id,
                    'code'        => Registration::generateCode(),
                    'status'      => $status,
                    'attended_at' => $attended ? $date->copy()->setTimeFromTimeString($spec['start'])->addMinutes(random_int(-10, 25)) : null,
                    'created_at'  => $date->copy()->subDays(random_int(2, 14)),
                    'updated_at'  => $date->copy()->subDays(random_int(0, 2)),
                ]);
            }

            // Akun peserta demo
            if ($spec['demo']) {
                $confirmed = in_array($spec['demo'], ['confirmed', 'attended', 'absent'], true);

                Registration::create([
                    'event_id'    => $event->id,
                    'user_id'     => $demo->id,
                    'code'        => Registration::generateCode(),
                    'status'      => $confirmed ? Registration::STATUS_CONFIRMED : Registration::STATUS_PENDING,
                    'attended_at' => $spec['demo'] === 'attended'
                        ? $date->copy()->setTimeFromTimeString($spec['start'])->addMinutes(5)
                        : null,
                    'created_at'  => $date->copy()->subDays(5),
                    'updated_at'  => $date->copy()->subDays(4),
                ]);
            }
        }
    }
}
