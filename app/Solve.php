<?php

namespace App;
use App\User;
use App\Sudoku;

use Illuminate\Database\Eloquent\Model;

class Solve extends Model
{
  protected $fillable = [
      'user_id', 'sudoku_id'
  ];

  public function user()
  {
      return $this->belongsTo('App\User');
  }

  public function sudoku()
  {
      return $this->belongsTo('App\Sudoku');
  }
}
