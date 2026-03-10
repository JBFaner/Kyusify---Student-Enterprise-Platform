<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Product Discovery Settings
            ['key' => 'discover_items_per_page', 'value' => '12'],
            ['key' => 'discover_default_sort', 'value' => 'newest'],
            ['key' => 'discover_enable_pagination', 'value' => '1'],
            
            // Homepage Content
            ['key' => 'homepage_announcement', 'value' => 'Welcome to Kyusify! The student enterprise platform for QCU.'],
            ['key' => 'homepage_banner_image', 'value' => ''],
            ['key' => 'homepage_banner_cta_text', 'value' => 'Start Exploring'],
            ['key' => 'homepage_banner_cta_link', 'value' => '/discover'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
