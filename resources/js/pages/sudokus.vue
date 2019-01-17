<template>
  <div>
      <h1>Play a sudoku Puzzle</h1>
      <div class="table-responsive">
        <table class="table">
          <tr>
            <th>Name</th><th>Autor</th><th>Creation time</th>
          </tr>
          <tr v-for="sud in sudokus">
            <td><a :href="`/sudokus/${sud.id}`" alt="play the sudoku">{{ sud.name }}</a></td>
            <td>{{sud.user.nick_name}}</td>
            <td>{{sud.user.created_at}}</td>
          </tr>
        </table>
      </div>
  </div>
</template>

<script>
export default {
  name: 'CreateSudoku',
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
      axios.get('/api/sudokus')
      .then(response => {
        this.sudokus = response.data;
      })
      .catch(e => {
        console.error(e);
      });
    }
  }
}
</script>
