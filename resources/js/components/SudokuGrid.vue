<template>
  <div class="sudoku">
    <table>
    	<caption v-if="isCreationMode">Create your own sudoku</caption>
      <caption v-else>Fill the gaps to solve the puzzle</caption>
    	<tbody>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    		<tr>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    			<td contenteditable="true"></td>
    		</tr>
    	</tbody>
    </table>
    <div>
      <ul>
        <li v-for="msg in gridError">{{msg}}</li>
      </ul>
    </div>
    <nav>
      <button v-on:click="clear()">Clear</button>
      <button v-on:click="submit()">Submit</button>
    </nav>
  </div>
</template>

<script>
import shuffle from 'lodash/shuffle'
import jQuery from 'jquery'
window.jQuery = jQuery
window.$ = jQuery

export default {
  // THIS CODE IS TAKEN PARTIALLY FROM https://codepen.io/kamblack/pen/zmqgoO HUGE THANKS TO Kam Black FOR THE SHARE
  name: "SudokuGrid",

  mounted() {
    this.applyPuzzle(this.default);
      for (let y = 0; y < 9; y++) {
    		for (let x = 0; x < 9; x++) {
          $('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').click(function() {
            if( $('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').css('color') == 'rgb(255, 0, 0)')
              $('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').css({'color': 'black'});
          });
        }
      }
  },

  props: {
    default: { default: function() { return [ // 0 == empty cell
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0],
      	[0,0,0,0,0,0,0,0,0]
      ]; } },
    isCreationMode: { default: true },
  },

  data: () => ({
    gridError: "",
  }),

  methods: {
    shuffle(array) {
      var currentIndex = array.length, temporaryValue, randomIndex;
      while (0 !== currentIndex) {
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
      }
      return array;
    },
    // Reorders an array based on a second array of indexes
    arrangeBy(array, indexes){
    	let result = [];
    	for (let i = 0; i < array.length; i++){
    		result[i] = array[indexes[i]];
    	}
    	return result;
    },
    // Manually pull one of three chunks of a nine-item array
    pullThird(array, index){
    	if (index == 0)
    		return array.slice(0,3);
    	else if (index == 1)
    		return array.slice(3,6);
    	else
    		return array.slice(6);
    },

    // display the puzzle inside the html table
    applyPuzzle(puzzle){
      for (let y = 0; y < this.default.length; y++) {
    		for (let x = 0; x < this.default.length; x++) {
    			$('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').html('');
    			if (puzzle[y][x]) {
    				$('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').html(puzzle[y][x]);
            if(!this.isCreationMode) {
              $('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').attr('contenteditable', 'false');
              $('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').css({'color' : 'blue'});
            }
          }
    		}
    	}
    },

    //clear existing puzzle
    clear() {
      let puzzle = [];
      for (var x = 0; x < this.default.length; x++) {
        puzzle.push([]);
        for (var y = 0; y < this.default[x].length; y++) {
            puzzle[x].push(this.default[x][y]);
        }
      }
      this.applyPuzzle(puzzle);
      this.gridError = [];
    },

    fetchPuzzleFromTable() {
      let puzzle = [];
      for (let y = 0; y < this.default.length; y++) {
        puzzle.push([]);
    		for (let x = 0; x < this.default.length; x++) {
          var val = $('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')').text();
          if(val.length == 0)
    			   puzzle[y].push(0);
          else puzzle[y].push(parseInt(val) || val);
    		}
    	}
      return puzzle;
    },

    submit() {
      for (let y = 0; y < this.default.length; y++) {
    		for (let x = 0; x < this.default.length; x++) {
          var elem = $('tr:nth-child('+(y+1)+') > td:nth-child('+(x+1)+')');
          if(this.default[y][x] == 0)
    			   elem.css({'color': 'black'});
    		}
    	}
      if(this.isCreationMode)
        this.saveSudoku();
      else
        this.validateSolution();
    },

    colorizeWrongCells(wrongcells)
    {
      this.handleWrongCellsType(wrongcells.rows);
      this.handleWrongCellsType(wrongcells.cols);
      this.handleWrongCellsGrid(wrongcells.subgrids);
    },

    handleWrongCellsType(errors)
    {
      for(var i = 0; i < errors.length; i++) {
        let error = errors[i];
        for(var k = 0; k < this.default.length; k++) {
          var elem = $('tr:nth-child('+(error[0]+1)+') > td:nth-child('+(k+1)+')');
          if(parseInt(elem.text()) == error[1])
            elem.css({'color': 'red'});
        }
      }
    },

    handleWrongCellsGrid(errors)
    {
      for(var i = 0; i < errors.length; i++) {
        let error = errors[i];
        let side = this.default.length / 3;
        for(var k = 0; k < this.default.length; k++) {
          for(var l = 0; l < this.default[k].length; l++) {
            var index = (Math.floor(k / side) * side) + (Math.floor(l / side));
            if(index == error[0])
            {
              var elem = $('tr:nth-child('+(l+1)+') > td:nth-child('+(k+1)+')');
              if(parseInt(elem.text()) == error[1])
                elem.css({'color': 'red'});
            }
          }
        }
      }
    },

    processErrors(errors)
    {
      this.gridError = [];
      for (var property in errors) {
        if (errors.hasOwnProperty(property)) {
          if(property.includes('.')){
            let split = property.split('.');
            this.gridError.push(`Row ${parseInt(split[1])+1}, Col ${parseInt(split[2])+1} ${errors[property].split(' ').slice(0,2).join(' ')}`);
          }
          else if(errors[property].wrongcells)
          {
            this.gridError.push(errors[property][0])
            this.colorizeWrongCells(errors[property].wrongcells);
          }
          else
          {
            this.gridError.push(errors[property][0]);
          }
        }
      }
    },

    // SEND The puzzle serverside asynchronously to be saved
    saveSudoku() {
      let puzzle = this.fetchPuzzleFromTable(); // get puzzle from html table
      //post asynchronously
      axios.post('/api/createsudoku/', {
        grid: puzzle
      }).then(res => {
        //redirect to sudokus list
      }).catch(err => {
        //in case of failure
        var errors = err.response.data.errors;
        this.processErrors(errors)
      });
    },

    validateSolution()
    {
      let puzzle = this.fetchPuzzleFromTable(); // get puzzle from html table
      axios.post('/api/validate/', {
        id: this.$route.params.id,
        grid: puzzle
      }).then(res => {
        this.$router.push({name: 'sudokus'});
        //redirect to sudokus list
      }).catch(err => {
        var errors = err.response.data.errors;
        this.processErrors(errors)
      });
    }
  },

  computed: {

  },
}
</script>

<style scoped>
.sudoku {
	justify-content: center;
	align-items: center;
	height: 45vh;
	width: 45vw;
	margin: 0;
	font-family: Verdana, sans-serif;
}

table {
	background: white;
	border-collapse: collapse;
	border: 3px solid black;
	width: 100%; height: 100%;
	max-width: 500px;
	max-height: 500px;
	margin-bottom: 15px;
}
table	td {
		border: 1px solid black;
		text-align: center;
		font-size: 2em;
    width: 5vw;
}

table	td:nth-child(3),
table td:nth-child(6) {
		border-right: 3px solid black;
}
table	tr:nth-child(3),
table	tr:nth-child(6) {
		border-bottom: 3px solid black;
}
table	caption {
		margin-bottom: 15px;
		font-size: 1.5em;
		color: white;
		background: black;
		padding: 5px;
}
</style>
