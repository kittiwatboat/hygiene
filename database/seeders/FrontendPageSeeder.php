<?php

namespace Database\Seeders;

use App\Models\FrontendPage;
use Illuminate\Database\Seeder;

class FrontendPageSeeder extends Seeder
{
    public function run(): void
    {
        FrontendPage::updateOrCreate(
            [
                'page_key' => 'first_page',
            ],
            [
                'name' => 'หน้าแรก',
                'title' => 'หน้าแรก',
                'subtitle' => 'จัดการรูปภาพและวิดีโอสำหรับหน้าแรก',
                'is_active' => true,
            ]
        );
    }
}
