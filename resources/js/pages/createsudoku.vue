<template>
  <div>
      <div>
        <h1>Create a sudoku puzzle</h1>
        <SudokuGrid/>
      </div>
  </div>
</template>

<script>
export default {
  name: 'CreateSudoku',
  middleware: 'auth',

  computed: {
    user () {
      return this.$store.user;
    }
  },
}
</script>
