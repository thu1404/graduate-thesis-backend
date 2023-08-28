<?php

namespace Database\Seeders;

use App\Models\HiringProcess;
use App\Models\HiringProcessRound;
use Illuminate\Database\Seeder;


class HiringProcessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HiringProcess::truncate();
        HiringProcessRound::truncate();
        $hiringProcess = HiringProcess::create([
            'name' => 'Tuyển dụng sơ cấp',
            'user_id' => 1
        ]);
        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Nhận hồ sơ',
            'process_id' => $hiringProcess->id
        ]);
        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Test',
            'process_id' => $hiringProcess->id
        ]);
        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Phỏng vấn',
            'process_id' => $hiringProcess->id
        ]);
        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Đã ứng tuyển',
            'process_id' => $hiringProcess->id
        ]);

        $hiringProcess = HiringProcess::create([
            'name' => 'Tuyển dụng cao cấp',
            'user_id' => 1
        ]);

        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Nhận hồ sơ',
            'process_id' => $hiringProcess->id
        ]);

        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Test',
            'process_id' => $hiringProcess->id
        ]);

        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Phỏng vấn',
            'process_id' => $hiringProcess->id
        ]);

        $hiringProcessRound = HiringProcessRound::create([
            'name' => 'Đã ứng tuyển',
            'process_id' => $hiringProcess->id
        ]);
    }
}
