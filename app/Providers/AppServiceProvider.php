<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      // rules that validates that a sudoku grid has at least N values set (greater than 0)
      Validator::extend('min_values_set', function($attribute, $value, $parameters, $validator) {
        $min = $parameters[0];
        $data = $validator->getData();
        if(isset($data[$attribute]))
        {
          $grid = $data[$attribute];
          $count = 0;
          for($i = 0; $i < count($grid); $i++){
            for($j = 0; $j < count($grid[$i]); $j++)
              $count += $grid[$i][$j] > 0 ? 1 : 0;
            }
          return $count >= $min;
        }
        return false;
      });

      Validator::replacer('min_values_set', function($message, $attribute, $rule, $parameters) {
          return str_replace(":value", $parameters[0], $message);
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
