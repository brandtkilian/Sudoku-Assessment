<template>
  <div>
      <div>
        <h1>Play the sudoku game</h1>
        <SudokuGrid v-if="sudoku" :default="sudoku.grid" :isCreationMode="false" :id="sudoku.id"/>
      </div>
  </div>
</template>

<script>
export default {
  name: 'Play',
  middleware: 'auth',

  mounted() {
    this.fetchSudoku();
  },

  data: () => ({
    sudoku: [],
  }),

  computed: {
    user () {
      return this.$store.user;
    }
  },

  methods: {
    fetchSudoku(){
      axios.get(`/api/sudokus/${this.$route.params.id}`)
      .then(response => {
        this.sudoku = response.data;
      })
      .catch(e => {
        this.$router.push({name: "sudokus"});
        console.error(e);
      });
    }
  }
}
</script>
