<template>
  <div>
      <h1>Players ranking</h1>
      <div class="table-responsive">
        <table class="table">
          <tr>
            <th>Position</th><th>Name</th><th>Number of solves</th>
          </tr>
          <tr v-for="(sol, i) in solves">
            <th>{{ i+1 }}</th>
            <td>{{ sol.user.nick_name }}</td>
            <td>{{ sol.total }}</td>
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
    solves: [],
  }),

  computed: {
    user () {
      return this.$store.user;
    }
  },

  methods: {
    fetchSudokus(){
      axios.get('/api/solves')
      .then(response => {
        this.solves = response.data;
      })
      .catch(e => {
        console.error(e);
      });
    }
  }
}
</script>
