<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeasonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $season = [
            'name' => '春',
        ];
        DB::table('seasons')->insert($season);
        $season = [
            'name' => '夏',
        ];
        DB::table('seasons')->insert($season);
        $season = [
            'name' => '秋',
        ];
        DB::table('seasons')->insert($season);
        $season = [
            'name' => '冬',
        ];
        DB::table('seasons')->insert($season);
    }
}
