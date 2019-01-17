<?php

namespace App\Http\Controllers;

use App\Sudoku;
use Illuminate\Http\Request;
use App\Http\Requests\SudokuRequest;

class SudokuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return Sudoku::orderBy('created_at')->with('user')->get();
    }

    /**
     * Display a listing of the sudokus user hasn't solve yet.
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function indexNotSolved(Request $request)
    {
      $user_id = $request->user()->id;
      return Sudoku::orderBy('created_at')->with('user')->whereDoesntHave('solves', function($query) use($user_id) {
        $query->where("user_id", $user_id);
      })->get();
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
     * Store a newly created Sudoku in storage.
     *
     * @param  \Illuminate\Http\Requests\SudokuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SudokuRequest $request)
    {
        $data = $request->all();
        $sudoku = new Sudoku($data);

        $user = $request->user();
        $name = date("Ymd_His") . '_' . $user->nick_name;

        $errors = [];
        // no errors (duplicate numbers following sudoku rules) detected if check returns true
        if($sudoku->checkCurrentState($request->grid, $errors))
        {
          // is it solvable?
          //return $sudoku->grid;
          if($result = $sudoku->solve($request->grid))
          {
            $sudoku->name = $name;
            //$sudoku->grid = json_encode($sudoku->grid);
            $sudoku->user()->associate($user);
            $sudoku->save();
            return $sudoku;
          }
          else return response()->json(["errors" => ["grid" => ["The submited sudoku is not solvable, try another one..."]]], 422);
        }
        else return response()->json(["errors" => ["grid" => ["The submited sudoku is in an invalid state", "wrongcells" => $errors]]], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
      $sudoku = Sudoku::with('user')->find($id);
      return $sudoku;
    }

    /**
     * Solve the requested puzzle
     *
     * @param  \App\Sudoku  $sudoku
     * @return \Illuminate\Http\Response
     */
    public function solve(Request $request, $id)
    {
      $sudoku = Sudoku::with('user')->find($id);
      if($sudoku)
      {
        $grid = $sudoku->solve($sudoku->grid);
        $sudoku->grid = $grid;
        return $sudoku;
      }
      return response()->json(["errors" => ["grid" => ["The sudoku you are asking the solution for is not found"]]], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sudoku  $sudoku
     * @return \Illuminate\Http\Response
     */
    public function edit(Sudoku $sudoku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sudoku  $sudoku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sudoku $sudoku)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sudoku  $sudoku
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sudoku $sudoku)
    {
        //
    }
}
