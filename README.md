# Sudoku App

This is my Sudoku Gaia test submission.

Host: http://sudokuapp-env.mnseug88n8.eu-central-1.elasticbeanstalk.com/

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

The JS dependencies are manages by npm. All the dependencies descriptors files are in the repository.

## Installation

To install this application on your local environment follow the following steps:
1. Clone this repository
2. Edit the copy the `.env.example` file into `.env`and edit with your configuration
4. Ensure that you have PHP 7, Composer and npm installed
4. In the root folder of the project run
    1. `composer update` to install the php dependencies required by the project
    2. `npm install` to install the JS dependencies
    3. `php artisan key:generate` to generate the App Key
    4. `php artisan jwt:secret` To generate the JSON web Token key used for API auth
4. create an empty mysql database
5. edit the copy the `.env` file with your database connection infos
6. run `php artisan migrate --seed`

## About the App
This app proposes a simple register/login system and two interfaces that allows connected users to create Sudoku Puzzles and Solve other's puzzle. For the most lazy ones a button can solve a Puzzle automatically. A page shows the ranking between the users, user with the most solved puzzles is top 1 rank.

All the datas are coming from an API using JSON and the frontend is designed as a SinglePageApplication, the components are switch using Vue-Router in a default layout page. Authencation is accomplished using JWT-Auth.

I'm using VueJS as my main front-end framework, VueJS is really convenient to work with JSON Data coming from APIs, it is reactive to any change and JSON can directly be used to generate the differents components content display. Since it is a SPA application, all the routes are in the routes/api.php file and all the content is transmitted using JSON format.

## REST API specifications

### `/api/register` [HTTP POST]
Content-type: `application/json`

Register a new user.

Required fields:
* `nick_name`: String (alphanumeric)
* `password`: String
* `password_confirmation`: String

**Example of request content:**
```
{
  "nick_name":"user",
  "password":"test1234",
  "password_confirmation":"test1234"
}
```
**Response `201 Created`:** Returns the created user without hidden fields (password)

```
{
  "nick_name":"user",
  "updated_at":"2019-01-20 18:00:30",
  "created_at":"2019-01-20 18:00:30",
  "id":2
}
```
The user is automatically logged by the backend if the registration is succesfull (`201 Created`)

**Errors `422 Unprocessable Entity`:**

Occurs when backend can't validate data.

**Example of response `422 Unprocessable Entity`**

```
{
  "message":"The given data was invalid.",
  "errors":{
    "nick_name":["The nick name format is invalid."],
    "password":["The password field is required."]
  }
}
```
### `/api/login` [HTTP POST]
Content-type: `application/json`

Log in an existing  user.

Required fields:
* `nick_name`: String (alphanumeric) 
* `password`: String

**Example of request content:**
```
{
    "nick_name":"user",
    "password":"test1234"
}
```

**Response `200 Ok`:** Logs the user, creates a JWT-Auth token
```
{
    "token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvYXBpXC9sb2dpbiIsImlhdCI6MTU0ODAwNzk2NywiZXhwIjoxNTQ4MDExNTY3LCJuYmYiOjE1NDgwMDc5NjcsImp0aSI6ImpqbGk5SGtZalJuV3BPUmUiLCJzdWIiOjIsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.eYkZJdESOKJLQg6Zh0I6iYv_eyOfmtycHTZFmh8SKtY",
    "token_type":"bearer",
    "expires_in":3600
}
```
**Errors `422 Unprocessable Entity`:**

Occurs when backend can't authenticate user.

Example of response `422 Unprocessable Entity`
```
{
    "message":"The given data was invalid.",
    "errors":
        {
            "nick_name":["These credentials do not match our records."]
        }
}
```

### `/api/user` [HTTP GET]
Content-type: `application/json`

Restricted to authenticated users.

Returns the current logged user (used by frontend)

**Example of request response:**
```
{
    "id":2,
    "nick_name":"user",
    "created_at":"2019-01-20 18:00:30",
    "updated_at":"2019-01-20 18:00:30"
}
```

### `/api/logout` [HTTP POST]
Content-type: `application/json`

Restricted to authenticated users.

Logs out the user.
Returns `204 No Content` if successful

### `/api/sudokus` [HTTP GET]
Content-type: `application/json`

Returns the list of all sudokus a logged user hasn't solved yet. The author of the grid is appended to the sudoku in order to display author name and information on the frontend.

Example of request response:
```[{
    "id":1,
    "user_id":1,
    "name":"Test sudoku",
    "grid":[[0,0,0,2,6,0,7,0,1],[6,8,0,0,7,0,0,9,0],[1,9,0,0,0,4,5,0,0],[8,2,0,1,0,0,0,4,0],[0,0,4,6,0,2,9,0,0],[0,5,0,0,0,3,0,2,8],[0,0,9,3,0,0,0,7,4],[0,4,0,0,5,0,0,3,6],[7,0,3,0,1,8,0,0,0]],
    "created_at":"2019-01-20 17:29:39","updated_at":null,
    "user":
        {"id":1,
        "nick_name":
        "Kilian",
        "created_at":null,
        "updated_at":null
        }
}]
```

### `/api/sudokus` [HTTP POST]
Content-type: `application/json`

Restricted to authenticated users.

Stores a new sudoku.

Required fields:
* `grid`: Array (9x9 array of integers)

**Example of request content:**
```
{
    "grid":[[1,0,0,4,8,9,0,0,6],[7,3,0,0,0,0,0,4,0],[0,0,0,0,0,1,2,9,5],[0,0,7,1,2,0,6,0,0],[5,0,0,7,0,3,0,0,8],[0,0,6,0,9,5,7,0,0],[9,1,4,6,0,0,0,0,0],[0,2,0,0,0,0,0,3,7],[8,0,0,5,1,2,0,0,4]]
}
```

**Response `201 Created`:** Returns the created sudoku grid
```
{
    "grid":[[1,0,0,4,8,9,0,0,6],[7,3,0,0,0,0,0,4,0],[0,0,0,0,0,1,2,9,5],[0,0,7,1,2,0,6,0,0],[5,0,0,7,0,3,0,0,8],[0,0,6,0,9,5,7,0,0],[9,1,4,6,0,0,0,0,0],[0,2,0,0,0,0,0,3,7],[8,0,0,5,1,2,0,0,4]],
    "name":"20190120_185252_user",
    "user_id":2,
    "updated_at":"2019-01-20 18:52:52",
    "created_at":"2019-01-20 18:52:52",
    "id":2,
    "user":{
        "id":2,
        "nick_name":"user",
        "created_at":"2019-01-20 18:00:30",
        "updated_at":"2019-01-20 18:00:30"
    }
}
```

**Errors `422 Unprocessable Entity`:** Occurs when the request is invalid or the grid is incomplete/unsolvable

Example of response 1:
```
{
    "message":"The given data was invalid.",
    "errors":{
        "grid":["The number of values set in your grid is too small (min: 25)"]
    }
}
```

Example of response 2:
```
{
    "errors":{
        "grid":{"0":"The submited sudoku is in an invalid state",
            "wrongcells": {
                "rows":[[0,1],[1,2],[2,3],[3,4],[4,6],[4,5],[5,6],[6,7],[7,8],[8,9]],
                "cols":[[4,3],[5,4]],
                "subgrids":[[4,4],[4,6]]
            }
        }
    }
}
```

The `wrongcells` object contains for each row/colum/subgrid the id of the row/colum/subgrid and the value of the number in a faulty state. `"rows":[[0,1]]` means that the number '1' in the row with index '0' is in a faulty state. The frontend can then highlight with a simple loop the faulty numbers.

### `/api/sudokus/{id}` [HTTP DELETE]
Content-type: `application/json`

Restricted to authenticated users.
A middleware ensures a user can delete only his own sudokus.

Deletes a sudoku given it's `{id}` (in the route)
**Response `200 OK`:** Returns "1" for success "0" for failure

### `/api/user/sudokus/` [HTTP GET]
Content-type: `application/json`

Returns the list of the current user sudokus.

**Response `200 OK`:**

Example of response:

```
[{
    "id":2,
    "user_id":2,
    "name":"20190120_185252_user",
    "grid":[[1,0,0,4,8,9,0,0,6],[7,3,0,0,0,0,0,4,0],[0,0,0,0,0,1,2,9,5],[0,0,7,1,2,0,6,0,0],[5,0,0,7,0,3,0,0,8],[0,0,6,0,9,5,7,0,0],[9,1,4,6,0,0,0,0,0],[0,2,0,0,0,0,0,3,7],[8,0,0,5,1,2,0,0,4]],
    "created_at":"2019-01-20 18:52:52",
    "updated_at":"2019-01-20 18:52:52"
}]
```
### `/api/sudokus/{id}` [HTTP GET]
Content-type: `application/json`

Restricted to authenticated users.

Returns a sudoku grid given it's `{id}` (in route)

**Response `200 OK`:**

Example of response:
```
{
    "id":1,
    "user_id":1,
    "name":"Test sudoku",
    "grid":[[0,0,0,2,6,0,7,0,1],[6,8,0,0,7,0,0,9,0],[1,9,0,0,0,4,5,0,0],[8,2,0,1,0,0,0,4,0],[0,0,4,6,0,2,9,0,0],[0,5,0,0,0,3,0,2,8],[0,0,9,3,0,0,0,7,4],[0,4,0,0,5,0,0,3,6],[7,0,3,0,1,8,0,0,0]],
    "created_at":"2019-01-20 17:29:39",
    "updated_at":null,
    "user": {
        "id":1,
        "nick_name":
        "Kilian",
        "created_at":2019-01-20 18:50:12,
        "updated_at":null
    }
}
```

The user in the returned JSON is the author.

**Response `404 Not Found`:**

```
{
    "message": "No query results for model [App\\Sudoku] 123"
}
```

### `/api/validate/` [HTTP POST]
Content-type: `application/json`

Restricted to authenticated users.
A middleware ensures a user can't send a solve twice for the same sudoku grid.

Validate a given sudoku puzzle (check if full, and in a valid state).

Required fields:
* `id`: Integer (id of the sudoku grid)
* `grid`: Array (9x9 array of integers)

**Example of request content**:
```
{
    "id":"2",
    "grid":[[1,5,2,4,8,9,3,7,6],[7,3,9,2,5,6,8,4,1],[4,6,8,3,7,1,2,9,5],[3,8,7,1,2,4,6,5,9],[5,9,1,7,6,3,4,2,8],[2,4,6,8,9,5,7,1,3],[9,1,4,6,3,7,5,8,2],[6,2,5,9,4,8,1,3,7],[8,7,3,5,1,2,9,6,4]]
}
```

**Response `200 OK`:** The grid is valid

Example of response containing the solve records the user and the sudoku solved record:
```
{
    "user_id":1,
    "sudoku_id":1,
    "updated_at":"2019-01-20 19:25:32",
    "created_at":"2019-01-20 19:25:32",
    "id":1,
    "user":
        {
        "id":1,
        "nick_name":"Kilian",
        "created_at":null,
        "updated_at":null
        },
        "sudoku":
            {
            "id":1,
            "user_id":1,
            "name":"Test sudoku",
            "grid":[[0,0,0,2,6,0,7,0,1],[6,8,0,0,7,0,0,9,0],[1,9,0,0,0,4,5,0,0],[8,2,0,1,0,0,0,4,0],[0,0,4,6,0,2,9,0,0],[0,5,0,0,0,3,0,2,8],[0,0,9,3,0,0,0,7,4],[0,4,0,0,5,0,0,3,6],[7,0,3,0,1,8,0,0,0]],
            "created_at":"2019-01-20 19:22:25",
            "updated_at":null
        }
}
```

**Errors `422 Unprocessable Entity`:** Occurs when the the grid is incomplete or the solution is false

Example of response 1:
```
{
    "errors":
        {
        "grid":
            {
                "0":"Your grid is in an invalid state",
                "wrongcells":
                    {
                    "rows":[[8,8]],
                    "cols":[[8,8]],
                    "subgrids":[[8,8]]
                    }
            }
        }
}
```
The `wrongcells` attribute contains the ids of rows/cols/subgrids and the number faulty for each one. In this example the number 8 is faulty in the 9th row, 9th column and 9th subgrid. There is some redondancy.

Example of response 2:
```
{
    "errors":
        {
            "grid":["Please fill all the blank cells before submission"]
        }
}
```
Example of response 3:
```
{
    "message":"The given data was invalid.",
    "errors":
        {
            "grid.0.6":["The grid.0.6 must be an integer."]
        }
}
```
It gives which grid cell `grid.[row].[col]` contains non integer.

### `/api/answer/{id}` [HTTP GET]
Content-type: `application/json`

Restricted to authenticated users.

Return the answer for a given sudoku puzzle given its `{id}` (in route)

**Response `200 OK`:** Returns the solution

Example of response:
```
{
    "id":2,
    "user_id":1,
    "name":"Test sudoku 2 ",
    "grid":[[1,5,2,4,8,9,3,7,6],[7,3,9,2,5,6,8,4,1],[4,6,8,3,7,1,2,9,5],[3,8,7,1,2,4,6,5,9],[5,9,1,7,6,3,4,2,8],[2,4,6,8,9,5,7,1,3],[9,1,4,6,3,7,5,8,2],[6,2,5,9,4,8,1,3,7],[8,7,3,5,1,2,9,6,4]],
    "created_at":"2019-01-20 19:22:25",
    "updated_at":null,
    "user":
        {
            "id":1,
            "nick_name":"Kilian",
            "created_at":"2019-01-20 19:22:25",
            "updated_at":null
        }
}
```

### `/api/solves/` [HTTP GET]
Content-type: `application/json`

Restricted to authenticated users.

Returns the number of solves for each users sorted (Descending) to display the ranking page.

Example of response:
```
[{
    "user_id":1,
    "total":1,
    "user":
        {
            "id":1,
            "nick_name":"Kilian",
            "created_at":"2019-01-20 19:22:25",
            "updated_at":null
        }
}]
```

## Details about the code

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

The validation of datas coming from frontend are made in a custom HTTP Request `App\Http\Requests\SudokuRequest.php`. This Request specifies the size validation rule of the grid size (a 2D array of size 9 x 9) and the content of the array as well (integers between 0 and 9). I made a custom validation rule that can be find in `App\Providers\AppServiceProviders` that will check how many of the 81 grid cells are filled up, this to force user entering a minimum number of values (otherwise there exists many solutions and the Backtracking algortihm used to validate if a grid is solvable will take too much time). This custom validation rules comes with a custom error message and placeholder, the minimum number of required cells is customizable in the rule declaration.

![Validation of new grid](https://github.com/brandtkilian/Sudoku-Assessment/blob/master/images/new_sudoku.png "Validation of new grid")

After the format of the grid checked (size and data types), the grid state is checked regarding the rules of the game. In the case where a user submit a new grid to play, the verifications are made as follow:
1. Check if any rule is violated (no duplicata in no row, column or subgrid 3x3)
2. Check if the grid has a solution using backtracking.

The [Backtracking algorithm](https://en.wikipedia.org/wiki/Sudoku_solving_algorithms) is an algorithm that acts like a bruteforce, given a grid state it will discover every numbers that can be fill in any blank cell without violate any rule. Starting from the cell with the minimum of possible moves (the less possible numbers can be fill in a blank cell, the more confident we are about the correctness). If many possibilities exists it will call the function recursively creating a recursive call for each scenario. If the grid is full then we have a solution and the grid is solvable. If the recursive calls stops before an end and we cannot find any other cell in which a number can be put then the grid is not solvable.

![How backtracking works](https://github.com/brandtkilian/Sudoku-Assessment/blob/master/images/Sudoku_solved_by_bactracking.gif "How backtracking works")
_source: https://commons.wikimedia.org/wiki/File:Sudoku_solved_by_bactracking.gif_

All the code to check the state of a grid and check the solvability is carried in the `App\Sudoku.php` Eloquent model class.

Sudokus are stored in Database in a Text field using JSON serialization. Using the `$casts` Laravel built-in attribute on Eloquent Model the serialization/Deserialization is automatic and the pipeline Backend/Frontend between PHP and JSON is smooth with no additional code required.$

#### Possible improvements
* Let user specify the difficulty, store the difficulty in database
* Actually the name is autogenerated, maybe we can let users be creative about names
* Try to use some other kind of algorithms more efficient (using kind of heuristics or genetics algorithms)
* Allow users to store an unfinished puzzle for later
* Do not allow user to store an already existing puzzle
* Add a processing gif while the backend is looking for a solution to approve the puzzle is solvable

### 3. Solves and Ranking

Once the user has imported/created some puzzle it's time to solve them. A user by difinition can solve any puzzle he hasn't solve yet. The solved are stored in database table with the reference on the sudoku and a date. The actual ranking system is based on the number of solves.

While solving a grid the user can regularly ask for check of his current solution. The same algorithms embedded in the Sudoku Model are used to give feedback and highlighting. If the user asks for the answer, the algorithm used to check the solvability of the grid is used and a JSON grid is return to the frontend and loaded in the VueJS component. To handle those functionnalities a couple Model/Controller/Migration has been created. The class `App\Http\Controllers\SolveController.php` carries the functionnal code to check a grid submission (using the methods of the Sudoku Model) and store a solve.

![Validation of a solution](https://github.com/brandtkilian/Sudoku-Assessment/blob/master/images/validate.png "Validation of a grid solution")

To ensure that a user will not solve multiple times the same sudoku I set up a Middleware on the routes to store a solve and display a sudoku, if the solve exists the user cannot solve it again.

#### Possible improvements
* Measure the time a user takes to solve a grid (include it in score)
* Adapt score from difficulty (if difficulty exists)
* Logs the use of the "Autocomplete" function so that a user cannot cheat and solve like this
* Allow user to save a not finished yet grid and continue later

## Known issues

* Impossible to retype values after asking for "answer" and then "clear" while solving a sudoku without refreshing
* Strange error appearing only in production `SyntaxError: expected expression, got '<'` in manifest.js and vendor.js
    * tried some of solutions here https://github.com/vuejs-templates/pwa/issues/165 without success
