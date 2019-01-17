<?php

namespace App\Http\Middleware;

use Closure;
use App\Solve;

class AlreadySolved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $id = $request->id; // the sudoku idea
      $user = $request->user(); // assume this middleware is chained with auth middleware --> we have a logged in user
      $solve = Solve::where('user_id', $user->id)->where('sudoku_id', $id)->first(); // try to find an existing solve for the current puzzle
      if($solve == null)
        return $next($request);
      return response()->json(["errors" => "You already solved this puzzle..."], 403);
    }
}
