<?php

namespace Database\Seeders;

use App\Models\GeneralSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $settings = [
            'site_name' => 'FIZA Drinking Water',
            'og_meta_title' => 'FIZA Drinking Water',
            'og_meta_description' => 'FIZA Drinking Water',
            'og_meta_image' => '/upload/generalSettings/1663958006logo.png',
        ];

        foreach ($settings as $key => $value) {
            GeneralSettings::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Cache::forget(CACHE_GENERAL_SETTINGS);
    }
}
