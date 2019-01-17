<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SudokuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      //ensure that the grid is
        return [
          'grid' => 'array|required|min:9|max:9|min_values_set:25',
          'grid.*' => 'min:0|max:9',
          'grid.*.*' => 'int|min:0|max:9',
          'name' => 'unique:sudokus',
        ];
    }
}
