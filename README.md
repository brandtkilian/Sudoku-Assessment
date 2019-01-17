# Sudoku App

This is my Sudoku Gaia test submission.

## Dependencies
Main dependencies:
* Laravel Framework 5.7
* VueJS 2.5.22
* Bootstrap 4.2.1

## Project structure
The project follows the classical Laravel framework structures, the dependencies are manages with Composer. The front-end pages developed with VueJS are under `resources\js` here you can find:
* `components/` the VueJS components
* `layouts/` the defaults layout in where pages are loaded
* `pages` the main web application pages that make use of components

The JS dependencies are manages by npm. All the dependencies descriptors files are up in the repository.

## Installation

To install this application on your local environment follow the following steps:
1. Clone this repository
2. Edit the copy the `.env.example` file into `.env`and edit with your configuration
4. Ensure that you have PHP 7, Composer and npm installed
4. In the root folder of the project run
4.1 `composer update` to install the php dependencies required by the project
4.2 `npm install` to install the JS dependencies
4.3 `php artisan key:generate` to generate the App Key
4.4 `php artisan jwt:secret` To generate the JSON web Token key used for API auth
4. create an empty mysql database
5. edit the copy the `.env` file with your database connection infos
6. run `php artisan migrate --seed`

## About the App
This app proposes simple a simple register/login system and two interfaces that allows connected users to create Sudoku Puzzles and Solve other's puzzle. For the most lazy ones a button can solve a Puzzle automatically. A page shows the ranking between the users, user with the most solved puzzles is top 1 rank.

All the datas are coming from an API using JSON and the frontend is designed as a SinglePageApplication, the components are switch using Vue-Router in a default layout page. Authencation is accomplished using JWT-Auth.

## About the code
I'm using VueJS as my main front-end framework, VueJS is really convenient to work with JSON Data coming from APIs, it is reactive to any change and JSON can directly be used to generate the differents components content display. Since it is a SPA application, all the routes are in the routes/api.php file and all the content is transmitted using JSON format.

### 1. Registration/Login
I tuned the default Registration/Login provided by Laravel templates by integrating JWT-Auth and symplifying the number of fields. The authentication process goes through the API using JWT.

I removed the email field required to create an account and make the login process use a nick_name instead.

#### Possible improvements
* Enable password recovery
* Enable profile update/deletion

### 2. Sudoku
The sudoku is the key part of this app. All sudoku grids are represented as a 2D array (in PHP as well as in JSON), the array can contains value between "0" and "9" where "0" means it's a blank cell.

#### 2.1 Frontend
In the frontend I used a [codepen](https://codepen.io/kamblack/pen/zmqgoO) code sample with a HTML table (contenteditable) and some javascript function. I put and adapted this code into a VueJS component that I can reuse in any of my views. The component supports requests with axios library to fetch a sudoku, submit a proposed solution, submit a proposed new grid.

All the errors are returned by the backend as JSON. When errors concern faulty values in a grid (regarding the sudoku rules) the backend returns an array with the rows/colums and subgrids and the values to highlight. Highligting of faulty values helps users to figure out where are their mistakes.

When submiting a proposed solution or a new sudoku puzzle, all the verifications are done on server side and the results are parsed and displayed from JSON to update the frontend.

The different pages related to the sudoku parts are resources/js/pages/:
* `createsudoku.vue`
* `play.vue`
* `sudokus.vue`

#### 2.2 Backend
In the Backend a sudoku Grid is also a 2D array of integers. I created a Model/Controller/Migrations. The Model carries all functions used to verify the grid in terms of state (no faults) and functions to solve it using a Backtracking algorithm (kind of bruteforce).

The validation of datas coming from frontend are made in a custom HTTP Request `App\Http\Requests\SudokuRequest.php`. This Request specifies the size validation rule of the grid size (a 2D array of size 9 x 9) and the content of the array as well (integers between 0 and 9). I made a custom validation rule that can be find in `App\Providers\AppServiceProviders` that will check how many of the 81 grid cells are filled up, this to force user entering a minimum number of values (otherwise there exists many solutions and the Backtracking algortihm used to validate a grid is solvable will take too much time). This custom validation rules comes with a custom error message and placeholder, the minimum number of required cells is customizable in the rule declaration.

After the format of the grid checked, the grid is checked. In the case where a user submit a new grid proposal to play, the verifications are made as follow:
1. Check if any rule is violated (no duplicata in no row, column or subgrid 3x3)
2. Check if the grid has a solution using backtracking.

The [Backtracking algorithm](https://en.wikipedia.org/wiki/Sudoku_solving_algorithms) is an algorithm that acts like a bruteforce, given a grid state it will discover every numbers that can be fill in any blank cell without violate any rule. Starting from the cell with the minimum of possible possibilities (the less possible number can be fill in a blank cell, the more confident we are about the correctness). If many possibilities exists it will call the function recursively creating a recursive call for each scenario. If the gris is full then we have a solution and the grid is solvable. If the recursive calls stops before an end and we cannot find any other cell in which a number can be put then the grid is not solvable.

All the code to check the state of a grid and check the solvability is carried in the `App\Sudoku.php` Eloquent model class.

Sudokus are stored in Database in a Text field using JSON serialization. Using the `$casts` Laravel built-in attribute on Eloquent Model the serialization/Deserialization is automatic and the pipeline Backend/Frontend between PHP and JSON is smooth with no additional code required.$

#### Possible improvements
* Let user specify the difficulty, store the difficulty in database
* Actually the name is autogenerated, maybe we can let users be creative about names
* Try to use some other kind of algorithms more efficient (using kind of heuristics or genetics algorithms)
* Allow users to store an unfinished puzzle for later

### 3. Solves and Ranking

Once the user has imported/created some puzzle it's time to solve them. A user by difinition can solve any puzzle he hasn't solve yet. The solved are stored in database table with the reference on the sudoku and a date. The actual ranking system is based on the number of solves.

While solving a grid the user can regularly ask for check of his current solution. The same algorithms embedded in the Sudoku Model are used to give feedback and highlighting. If the user asks for the answer, the algorithm used to check the solvability of the grid is used and a JSON grid is return to the frontend and loaded in the VueJS component. To handle those functionnalities a couple Model/Controller/Migration has been created. The class `App\Http\Controllers\SolveController.php` carries the functionnal code to check a grid submission (using the methods of the Sudoku Model) and store a solve.

To ensure that a user will not solve multiple times the same sudoku I set up a Middleware on the routes to store a solve and display a sudoku, if the solve exists the user cannot solve it again.

#### Possible improvements
* Measure the time a user takes to solve a grid (include it in score)
* Adapt score from difficulty (if difficulty exists)
* Logs the use of the "Autocomplete" function so that a user cannot cheat and solve like this
* Allow user to save a not finished yet grid and continue later
