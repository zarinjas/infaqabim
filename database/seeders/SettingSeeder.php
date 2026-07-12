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
            'key' => 'site_email',
            'value' => 'admin@infaqabim.my',
        ]);

        Setting::create([
            'key' => 'nav_label_1',
            'value' => 'Home',
        ]);

        Setting::create([
            'key' => 'nav_label_2',
            'value' => 'Campaigns',
        ]);

        Setting::create([
            'key' => 'nav_label_3',
            'value' => 'Donate',
        ]);

        Setting::create([
            'key' => 'nav_label_4',
            'value' => 'About',
        ]);

        Setting::create([
            'key' => 'payment_method',
            'value' => 'manual',
        ]);
    }
}
