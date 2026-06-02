<?php

/**
 * Template impor Excel panel kepala cabor (tanpa kolom kode cabor — otomatis cabor login).
 */
return [
    'atlet' => [
        'label' => 'Atlet',
        'route_index' => 'cabor.atlet.index',
        'headers' => [
            'name' => 'Nama Lengkap *',
            'birth_date' => 'Tanggal Lahir (YYYY-MM-DD)',
            'gender' => 'Jenis Kelamin (laki-laki/perempuan)',
            'phone' => 'Telepon',
            'email' => 'Email',
            'address' => 'Alamat',
            'bio' => 'Bio',
            'is_active' => 'Aktif (ya/tidak)',
        ],
        'example' => [
            'name' => 'Budi Santoso',
            'birth_date' => '2010-05-15',
            'gender' => 'laki-laki',
            'phone' => '08123456789',
            'email' => 'budi@email.com',
            'address' => 'Kec. Curup',
            'bio' => '',
            'is_active' => 'ya',
        ],
    ],
    'pelatih' => [
        'label' => 'Pelatih',
        'route_index' => 'cabor.pelatih.index',
        'headers' => [
            'name' => 'Nama Lengkap *',
            'license_number' => 'No. Lisensi',
            'level' => 'Level',
            'phone' => 'Telepon',
            'email' => 'Email',
            'bio' => 'Bio',
            'is_active' => 'Aktif (ya/tidak)',
        ],
        'example' => [
            'name' => 'Andi Pelatih',
            'license_number' => 'LIS-001',
            'level' => 'Nasional',
            'phone' => '08123456789',
            'email' => 'andi@email.com',
            'bio' => '',
            'is_active' => 'ya',
        ],
    ],
    'wasit' => [
        'label' => 'Wasit',
        'route_index' => 'cabor.wasit.index',
        'headers' => [
            'name' => 'Nama Lengkap *',
            'license_number' => 'No. Lisensi',
            'level' => 'Level',
            'phone' => 'Telepon',
            'is_active' => 'Aktif (ya/tidak)',
        ],
        'example' => [
            'name' => 'Citra Wasit',
            'license_number' => 'WST-01',
            'level' => 'Provinsi',
            'phone' => '08123456789',
            'is_active' => 'ya',
        ],
    ],
    'juri' => [
        'label' => 'Juri',
        'route_index' => 'cabor.juri.index',
        'headers' => [
            'name' => 'Nama Lengkap *',
            'license_number' => 'No. Lisensi',
            'level' => 'Level',
            'phone' => 'Telepon',
            'is_active' => 'Aktif (ya/tidak)',
        ],
        'example' => [
            'name' => 'Dewi Juri',
            'license_number' => 'JRI-01',
            'level' => 'Kabupaten',
            'phone' => '08123456789',
            'is_active' => 'ya',
        ],
    ],
];
