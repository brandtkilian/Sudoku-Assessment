<template>
  <div>
      <h1>My Sudoku grids</h1>
      <div class="table-responsive">
        <table class="table">
          <tr>
            <th>Name</th><th>Creation time</th><th>Actions</th>
          </tr>
          <tr v-for="sud in sudokus">
            <td><a :href="`/sudokus/${sud.id}`" alt="play the sudoku">{{ sud.name }}</a></td>
            <td>{{sud.created_at}}</td>
            <td><button class="btn btn-danger" v-on:click="deleteSudoku(sud.id)" alt="Delete the sudoku">Delete</button></td>
          </tr>
        </table>
      </div>
  </div>
</template>

<script>
export default {
  name: 'MySudoku',
  middleware: 'auth',

  mounted() {
    this.fetchSudokus();
  },

  data: () => ({
    sudokus: [],
  }),

  computed: {
    user () {
      return this.$store.user;
    }
  },

  methods: {
    fetchSudokus(){
      axios.get('/api/user/sudokus')
      .then(response => {
        this.sudokus = response.data;
      })
      .catch(e => {
        console.error(e);
      });
    },

    deleteSudoku(id) {
      axios.delete(`/api/sudokus/${id}`)
      .then(response => {
        var index = this.sudokus.map(o => o.id).indexOf(id);
        if(index > -1)
          this.sudokus.splice(index, 1);
      })
      .catch(e => {
        console.error(e);
      });
    },
  }
}
</script>
