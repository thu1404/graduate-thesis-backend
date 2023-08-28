<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreferredPosition;

class PreferredPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PreferredPosition::truncate();
        PreferredPosition::create(
            [
                'id'   => '1',
                'name' => 'Nhận hồ sơ'
            ]
        );
        PreferredPosition::create(
            [
                'id'   => '2',
                'name' => 'Test'
            ]
        );
        PreferredPosition::create(
            [
                'id'   => '3',
                'name' => 'Phỏng vấn'
            ]
        );
        PreferredPosition::create(
            [
                'id'   => '4',
                'name' => 'Đã ứng tuyển'
            ]
        );
        PreferredPosition::create(
            [
                'id'   => '5',
                'name' => 'Bị loại'
            ]
        );
    }
}
