<template>
  <div class="custom-control custom-checkbox d-flex">
    <input
      :name="name"
      :checked="internalValue"
      :id="id || name"
      type="checkbox"
      class="custom-control-input"
      @click="handleClick">
    <label :for="id || name" class="custom-control-label my-auto">
      <slot/>
    </label>
  </div>
</template>

<style scoped>
.custom-control-label::after {
  top: 0.1rem;
}

.custom-control-label::before {
  top: 0.1rem;
}
</style>

<script>
export default {
  name: 'Checkbox',

  props: {
    id: { type: String, default: null },
    name: { type: String, default: 'checkbox' },
    value: { type: [Boolean, Number], default: false },
    checked: { type: [Boolean, Number], default: false }
  },

  data: () => ({
    internalValue: false
  }),

  watch: {
    value (val) {
      this.internalValue = Boolean(val)
    },

    checked (val) {
      this.internalValue = Boolean(val)
    },

    internalValue (val, oldVal) {
      if (val !== oldVal) {
        this.$emit('input', val)
      }
    }
  },

  created () {
    this.internalValue = this.value

    if ('checked' in this.$options.propsData) {
      this.internalValue = this.checked
    }
  },

  methods: {
    handleClick (e) {
      this.$emit('click', e)

      if (!e.isPropagationStopped) {
        this.internalValue = e.target.checked
      }
    }
  }
}
</script>
