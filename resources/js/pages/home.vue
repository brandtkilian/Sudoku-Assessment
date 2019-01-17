<template>
  <div>
      <div>
        <Home/>
      </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  computed: {
    user () {
      return this.$store.user;
    }
  },
}
</script>
