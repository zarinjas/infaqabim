<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'key' => 'site_name',
            'value' => 'INFAQABIM',
        ]);

        Setting::create([
            'key' => 'site_description',
            'value' => 'Platform sumbangan dan infak online',
        ]);

        Setting::create([
            'key' => 'contact_email',
            'value' => 'admin@infaqabim.my',
        ]);
    }
}
