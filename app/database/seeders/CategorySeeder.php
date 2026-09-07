<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Hardware', 'description' => 'Issues with physical equipment like computers, monitors, printers'],
            ['name' => 'Software', 'description' => 'Software installation, updates, or compatibility issues'],
            ['name' => 'Network', 'description' => 'Connectivity, WiFi, VPN, and network access issues'],
            ['name' => 'Email', 'description' => 'Email client configuration, delivery issues, calendar problems'],
            ['name' => 'Account Access', 'description' => 'Password resets, account lockouts, permission requests'],
            ['name' => 'Security', 'description' => 'Security incidents, malware, suspicious activity reports'],
            ['name' => 'New Request', 'description' => 'Requests for new equipment, software, or access'],
            ['name' => 'Other', 'description' => 'General IT support requests not covered by other categories'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
