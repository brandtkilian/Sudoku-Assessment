<template>
    <div class="form-text invalid-feedback" v-if="form.errors.has(field)" v-html="form.errors.get(field)"/>
</template>

<script>
export default {
    name: 'FormError',
    props: {
        form: {
            type: Object,
            required: true
        },
        field: {
            type: String,
            required: true
        }
    }
}
</script>
