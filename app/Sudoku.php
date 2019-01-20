<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Sudoku extends Model
{
    const GRID_SIZE = 9; // side size of the grids
    protected $cols = [];
    protected $subgrids = [];
    protected $hash = "";

    protected $table = 'sudokus';

    protected $fillable = [
        'name', 'grid', 'user_id', 'hash'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['cols', 'subgrids'];

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    protected $casts = [ 'grid' => 'array' ];

    public function __construct(array $attributes = array())
    {
      parent::__construct($attributes);
      if(count($attributes) > 0)
      {
        $this->subgrids = $this->getSubgrids($this->grid);
        $this->cols = $this->getColumns($this->grid);
        //$this->diagonals = $this->getDiagonals($this->grid);
      }
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function solves()
    {
      return $this->hasMany('App\Solve');
    }


    // SUDOKU SOLVING RELATED METHODS

    // PUBLIC METHODS

    /**
   * Check if a grid violates any of the sudoku rules
   *
   * @param  array  $grid
   * @return bool isConsistent
   */
    public function checkCurrentState($grid, &$errors)
    {
      $success = false;
      // variable to store the number of occurences of a number in the grids
      //with respects to the rules it's in each rows, columns and subgrids
      $counting_subgrids = [];
      $counting_rows = [];
      $counting_cols = [];

      // count in rows and columns
      foreach ($grid as $row_num => $row) {
        $counting_rows[] =  array_count_values($row);
      }
      foreach ($this->cols as $col_num => $col) {
        $counting_cols[] =  array_count_values($col);
      }

      // foreach 3x3 grids
      foreach ($this->subgrids as $row_num => $row) {
        foreach ($row as $col_num => $subgrid) {
          // get the count of individuals values
          $counting_subgrids[] = array_count_values($subgrid);
        }
      }
      // get reports from countings that gives cell coordinates of numbers in faulty place
      $errors_rows = $this->getErrorReport($counting_rows);
      $errors_cols = $this->getErrorReport($counting_cols);
      $errors_subgrids = $this->getErrorReport($counting_subgrids);
      //$errors_diags = $this->getErrorReport($counting_diags);

      // put the report in the reference variable as output
      $errors = ["rows" => $errors_rows, "cols" => $errors_cols, "subgrids" => $errors_subgrids];

      // if no error returns true otherwise false
      return count($errors_rows) + count($errors_cols) + count($errors_subgrids) == 0;
    }


    /**
   * Solves a sudoku using the BackTracking algorithm with recursive calls
   * Partialy inspired by https://github.com/kirilkirkov/Sudoku-Solver
   * @param  array  $grid
   * @return array solved grid or false if not solvable
   */
    public function solve($grid)
    {
      while(true)
      {
        //create a temporary sudoku to use methods from the given grid
        $tmpSudoku = new Sudoku(["grid" => $grid]);

        $operations = [];
        //iterate each row and each column
        foreach ($grid as $row_num => $row) {
          foreach ($row as $col_num => $value) {
            // for blank values in grid
            if($value == 0) // it means in the grid it's an empty space
            {
              // generate all possible possibilities for current cell
              $possible_values = $tmpSudoku->getPossibilities($row_num, $col_num);
              // append those possibilities to the operation array
              $operations[] = ["row_num" => $row_num, "col_num" => $col_num, "possible" => $possible_values];
            }
          }
        }
        // if there are no possibilities to fill any number then return the current grid
        if (empty($operations)) {
          return $grid;
        }

        // Sorts all possible fills to try first the ones with the less possible numbers (more confident it would be the correct answer)
        usort($operations, [$this, 'sortOperations']);
        $row = $operations[0]['row_num'];
        $col = $operations[0]['col_num'];

        // if there is only one possible number for the blank cell that has the less possibilities
        // this means that actually the number is the correct answer no need to go deeper in exploration
        if(count($operations[0]["possible"]) == 1)
        {
          // retrieve row and col indices
          $grid[$row][$col] = $operations[0]['possible'][0]; // fill in this number and continue
          continue;
        }
        // otherwise recursively try others branch of possibilities (always start filling blanks with minimum number of possible numbers)
        foreach ($operations[0]['possible'] as $value) {
          $tmpGrid = $grid; // copy table
          $tmpGrid[$row][$col] = $value;
          $tmpSol = null;
          $result = $this->solve($tmpGrid); // recursive call with the each possible number
          $solution = $tmpGrid;
          if($result)
          {
            return $this->solve($tmpGrid);
          }
        }
        return false;
      }
    }

    /**
   * Compare current object grid with another one to validate it's the same
   *
   * @param  array  $grid
   * @return bool isIdentical
   */
    public function compareSubmission($grid)
    {
      //we want to know if the submission matches with the sudoku
      // checks all the non zero values
      $result = true;
      foreach ($grid as $row_num => $row) {
        foreach ($row as $col_num => $col) {
          if($this->grid[$row_num][$col_num] != 0) // check only non zero
            $result &= $this->grid[$row_num][$col_num] == $col; // if values are not equals the and will set the flag to false;
        }
      }
      return $result;
    }

    /**
   * Tells if a grid is fully filled or not
   * Containing the 9th sub grids
   * @return bool isFull
   */
    public function isFull()
    {
      // to check weither there remains empty cells or not in the sudoku
      $sum = 0;
      foreach ($this->grid as $row_num => $row) {
        foreach ($row as $col_num => $col) {
           $sum += $col;
        }
      }
      // The sum should be equal to the sum of the numbers from 1 to 9 in 9 subgrids
      return $sum == ((1 + $this::GRID_SIZE) / 2 * $this::GRID_SIZE) * $this::GRID_SIZE;
    }

    // PROTECTED/PRIVATE METHODS

    /**
   * From the 2D array (rows) of a grid returns a 2D array of array of length 9
   * Containing the 9th sub grids
   * @param  array  $grid
   * @return array subgrids 2D array
   */
    protected function getSubgrids($grid)
    {
      $subgrids = [];
      $subgrid_size = $this::GRID_SIZE / 3;

      //iterate each row
      for($i = 0; $i < $this::GRID_SIZE; $i++)
      {
        $row_num = (int)($i / $subgrid_size);
        //iterate each column
        for($j = 0; $j < $this::GRID_SIZE; $j++)
        {
          $col_num = (int)($j / $subgrid_size);
          $subgrids[$row_num][$col_num][] = $grid[$i][$j];
        }
      }
      return $subgrids;
    }

    /**
     * From the 2D array (rows) of a grid returns a 2D array of columns
     *
     * @param  array  $grid
     * @return array columns
     */
    protected function getColumns($grid)
    {
      $columns = [];
      foreach ($grid as $row_num => $row)
      {
        foreach ($row as $col_num => $value)
        {
             $columns[$col_num][$row_num] = $value;
        }
      }
      return $columns;
    }

    /**
     * From the 2D array (rows) of a grid returns a 2D array of the two diagonals
     *  unusued because not X sudoku game
     * @param  array  $grid
     * @return array columns
     */
    protected function getDiagonals($grid)
    {
      $first_diag = [];
      $second_diag = [];

      $i = 0;
      for($j = 0; $j < $this::GRID_SIZE; $j++){
          $first_diag[] = $grid[$i++][$j];
          $second_diag[] = $grid[$this::GRID_SIZE - $i][$j];
      }
      return [$first_diag, $second_diag];
    }

    /**
     * From the associative array containing counts of individuals values
     * outputs the index and number that is faulty
     *  unusued because not X sudoku game
     * @param  array  $countings
     * @return array errors
     */
    protected function getErrorReport($countings)
    {
      $errors = [];
      foreach ($countings as $num => $counts) {
        foreach ($counts as $value => $count) {
          if($value == 0) continue;
          if($count > 1)
            $errors[] = [$num, $value];
        }
      }
      return $errors;
    }

    /**
     * Returns all possible numbers possible without violating rules of sudoku for a cell
     *  unusued because not X sudoku game
     * @param  int  $row is the row index
     * @param  int  $col is the col index
     * @return array possibilities
     */
    protected function getPossibilities($row, $col)
    {
      $subgrid_size = $this::GRID_SIZE / 3;
      $row_subgrid = (int)($row / $subgrid_size);
      $col_subgrid = (int)($col / $subgrid_size);

      $values = [];
      for($n = 1; $n <= 9; $n++)
      {
        // Check whether the proposed number is not in the row, not in the col not in the subgrid
        if(!in_array($n, $this->grid[$row]) && !in_array($n, $this->cols[$col]) && !in_array($n, $this->subgrids[$row_subgrid][$col_subgrid])) {
          $values[] = $n;
        }
      }
      //shuffle($values);
      return $values;
    }

    protected function sortOperations($a, $b) {
        $a = count($a['possible']);
        $b = count($b['possible']);
        return $a - $b;
    }
}
