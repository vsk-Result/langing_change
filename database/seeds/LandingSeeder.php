<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $landings = [
            [
                'title' => 'landing_white',
                'preview_url' => 'landings/landing_white.png',
                'storage_url' => 'landings/landing_white.zip',
            ],
            [
                'title' => 'landing_green',
                'preview_url' => 'landings/landing_green.png',
                'storage_url' => 'landings/landing_green.zip',
            ],
            [
                'title' => 'landing_purple',
                'preview_url' => 'landings/landing_purple.png',
                'storage_url' => 'landings/landing_purple.zip',
            ],
            [
                'title' => 'landing_blue',
                'preview_url' => 'landings/landing_blue.png',
                'storage_url' => 'landings/landing_blue.zip',
            ],
            [
                'title' => 'landing_red',
                'preview_url' => 'landings/landing_red.png',
                'storage_url' => 'landings/landing_red.zip',
            ]
        ];
        DB::table('landings')->insert($landings);
    }
}
