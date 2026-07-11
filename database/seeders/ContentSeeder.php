<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Campaign;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        Banner::create([
            'title' => 'Bantu Mereka Yang Memerlukan',
            'subtitle' => 'Setiap sumbangan anda membawa sinar harapan kepada mereka yang kurang bernasib baik. Sertai kami dalam misi kemanusiaan ini.',
            'image' => 'banners/hero-1.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Banner::create([
            'title' => 'Sedekah Jariah Untuk Semua',
            'subtitle' => 'Berinfaqlah dengan ikhlas, kerana setiap kebaikan akan dikembalikan dengan berlipat ganda.',
            'image' => 'banners/hero-2.jpg',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Campaign::create([
            'title' => 'Bantuan Pendidikan Anak Yatim',
            'description' => 'Membantu pendidikan anak-anak yatim yang memerlukan sokongan kewangan untuk masa depan yang lebih cerah.',
            'target_amount' => 50000,
            'collected_amount' => 32500,
            'type' => 'progress',
            'is_active' => true,
        ]);

        Campaign::create([
            'title' => 'Keperluan Asas Golongan Asnaf',
            'description' => 'Menyediakan keperluan asas seperti makanan, pakaian, dan tempat tinggal untuk golongan asnaf di seluruh negara.',
            'target_amount' => 80000,
            'collected_amount' => 45000,
            'type' => 'progress',
            'is_active' => true,
        ]);

        Campaign::create([
            'title' => 'Bantuan Perubatan & Kesihatan',
            'description' => 'Membiayai rawatan perubatan dan pembedahan untuk pesakit yang kurang mampu mendapatkan rawatan di hospital swasta.',
            'target_amount' => 100000,
            'collected_amount' => 28000,
            'type' => 'progress',
            'is_active' => true,
        ]);

        Campaign::create([
            'title' => 'Program Iftar Sepanjang Ramadan',
            'description' => 'Menyediakan juadah berbuka puasa untuk golongan miskin dan asnaf sepanjang bulan Ramadan di masjid-masjid terpilih.',
            'target_amount' => 30000,
            'collected_amount' => 30000,
            'type' => 'progress',
            'is_active' => true,
        ]);
    }
}
