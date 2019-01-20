<?php

namespace App\Http\Controllers;

use App\Solve;
use App\Sudoku;
use Illuminate\Http\Request;
use App\Http\Requests\SudokuRequest;


class SolveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    // returns the number of solves by user ordered descendant (biggest first)
    public function indexCount()
    {
      return Solve::with('user')->groupBy('user_id')->selectRaw('user_id, count(*) as total')->orderBy('total', 'DESC')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Make verifications if the proposed grid is correct
     * If correct, save the solve to increase the user's ranking
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SudokuRequest $request)
    {
      $id = $request->id; // frontend supposed to send the sudoku_id
      $source_sudoku = Sudoku::find($id);

      //Source sudoku exists
      if($source_sudoku)
      {
        $user = $request->user(); // route protected by auth middleware ensure a user is in request
        $sudoku = new Sudoku($request->except('id')); // create a sudoku in order to use solving methods
        //Ensure that user is solving an existing sudoku
        $errors = [];
        if($source_sudoku->compareSubmission($sudoku->grid, $errors))
        {
          // check if any violation of the rules in the submission
          if($sudoku->checkCurrentState($request->grid, $errors))
          {
            //check if complete
            if($sudoku->isFull())
            {
              // the grid is full with no faults, insert solve
              $solve = new Solve;
              $solve->user()->associate($user);
              $solve->sudoku()->associate($source_sudoku);
              $solve->save();
              return $solve;
            }
            return response()->json(["errors" => ["grid" => ["Please fill all the blank cells before submission"]]], 422);
          }
        }
        return response()->json(["errors" => ["grid" => ["Your grid is in an invalid state", "wrongcells" => $errors]]], 422);
      }
      return response()->json(["errors" => ["grid" => ["Cannot find the sudoku you tried to solve"]]], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Solve  $solve
     * @return \Illuminate\Http\Response
     */
    public function show(Solve $solve)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Solve  $solve
     * @return \Illuminate\Http\Response
     */
    public function edit(Solve $solve)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Solve  $solve
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Solve $solve)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Solve  $solve
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solve $solve)
    {
        //
    }
}
