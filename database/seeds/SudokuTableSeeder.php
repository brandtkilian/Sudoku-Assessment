<?php

use Illuminate\Database\Seeder;

class SudokuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('sudokus')->insert([
        [
          'user_id' => 1, //Kilian
          "name" => "Test sudoku",
          'grid' => "[[0,0,0,2,6,0,7,0,1],[6,8,0,0,7,0,0,9,0],[1,9,0,0,0,4,5,0,0],[8,2,0,1,0,0,0,4,0],[0,0,4,6,0,2,9,0,0],[0,5,0,0,0,3,0,2,8],[0,0,9,3,0,0,0,7,4],[0,4,0,0,5,0,0,3,6],[7,0,3,0,1,8,0,0,0]]",
          'created_at' => date("Y-m-d H:i:s")
        ],
        [
          'user_id' => 1, //Kilian
          "name" => "Test sudoku 2 ",
          'grid' => "[[1,0,0,4,8,9,0,0,6],[7,3,0,0,0,0,0,4,0],[0,0,0,0,0,1,2,9,5],[0,0,7,1,2,0,6,0,0],[5,0,0,7,0,3,0,0,8],[0,0,6,0,9,5,7,0,0],[9,1,4,6,0,0,0,0,0],[0,2,0,0,0,0,0,3,7],[8,0,0,5,1,2,0,0,4]]",
          'created_at' => date("Y-m-d H:i:s")
        ],
      ]);
    }
}
