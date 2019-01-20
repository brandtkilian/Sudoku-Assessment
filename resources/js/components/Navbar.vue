<template>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="/home">Brandt's Sudokus</a>
      </div>
      <ul class="nav navbar-justified">
        <li><a v-if="!user" href="/login">Login</a></li>
        <li><a v-if="!user" href="/register">Register</a></li>
        <li><a v-if="user" href="/create">Create Sudoku</a></li>
        <li><a v-if="user" href="/mysudokus">My Sudokus</a></li>
        <li><a v-if="user" href="/sudokus">Play Sudoku</a></li>
        <li><a v-if="user" href="/ranking">Ranking</a></li>
        <li><a v-if="user" href="#" @click.prevent="logout">Logout</a></li>
      </ul>
    </div>
  </nav>
</template>

<script>

import { mapGetters } from 'vuex'

export default {
  name: 'Navbar',
  data: () => ({
    appName: window.config.appName
  }),

  computed:
    mapGetters({
      user: 'auth/user'
    }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout');

      // Redirect to login.
      this.$router.push({ name: 'home' });
    },
  }
}
</script>
<style scope>
nav li {
   display: inline-block;
   padding: 10px 10px;
}
</style>
