<?php

namespace Database\Seeders;

use App\Models\Line;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lines = [
            [
                'line_name' => 'Cyl.Block'                
            ],
            [
                'line_name' => 'Cyl.Head'           
            ],
            [
                'line_name' => 'Crank Shaft'           
            ],
            [
                'line_name' => 'Cam Shaft'           
            ],
            [
                'line_name' => 'Assembly'           
            ],
            [
                'line_name' => 'Common'           
            ],
        ];
        foreach ($lines as $line) {
            Line::create($line);
        }
    }
}
