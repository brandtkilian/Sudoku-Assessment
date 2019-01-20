<template>
  <div>
      <div>
        <Home/>
      </div>
  </div>
</template>

<script>
export default {
  computed: {
    user () {
      return this.$store.user;
    }
  },
}
</script>
