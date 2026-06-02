<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'app_name' => 'Sitenor Rejang Lebong',
                'tagline' => 'Sistem Informasi Tenaga Olahraga Kabupaten Rejang Lebong',
                'visi' => 'Menjadi sistem informasi tenaga olahraga terpadu yang akurat, transparan, dan mudah diakses bagi masyarakat Kabupaten Rejang Lebong.',
                'misi' => "Menyediakan data tenaga olahraga yang terstruktur per cabang olahraga.\nMendukung pembinaan atlet, pelatih, wasit, dan juri di tingkat kabupaten.\nMenyebarkan informasi prestasi dan pengumuman olahraga secara cepat dan terbuka.",
                'email' => 'dispora@rejanglebong.go.id',
                'phone' => null,
                'address' => 'Kabupaten Rejang Lebong, Bengkulu',
                'footer_text' => '© '.date('Y').' Sitenor Kabupaten Rejang Lebong — Dispora',
            ]
        );
    }
}
