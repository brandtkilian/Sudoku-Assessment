<?php

namespace App\Http\Middleware;

use Closure;
use App\Sudoku;

class OwnSudoku
{
    /**
     * Handle an incoming request. Reject access if user try to deletes a sudoku he/she doesn't own
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $user = $request->user();
      $sudoku = Sudoku::findOrFail($request->id);
      if($sudoku->user_id == $user->id)
        return $next($request);
      return response()->json(["errors" => "You cannot delete other people sudokus"], 401);
    }
}
