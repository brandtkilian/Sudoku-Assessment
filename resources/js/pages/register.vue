<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card title="Register">
        <form @submit.prevent="register" @keydown="form.onKeydown($event)">
          <!-- Nick name -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Nick name</label>
            <div class="col-md-7">
              <input v-model="form.nick_name" :class="{ 'is-invalid': form.errors.has('nick_name') }" class="form-control" type="text" name="nick_name">
              <FormError :form="form" field="nick_name"/>
            </div>
          </div>


          <!-- Password -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Password</label>
            <div class="col-md-7">
              <input v-model="form.password" :class="{ 'is-invalid': form.errors.has('password') }" class="form-control" type="password" name="password">
              <FormError :form="form" field="password"/>
            </div>
          </div>

          <!-- Password Confirmation -->
          <div class="form-group row">
            <label class="col-md-3 col-form-label text-md-right">Confirm password</label>
            <div class="col-md-7">
              <input v-model="form.password_confirmation" :class="{ 'is-invalid': form.errors.has('password_confirmation') }" class="form-control" type="password" name="password_confirmation">
              <FormError :form="form" field="password_confirmation"/>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex">
              <!-- Submit Button -->
              <button type="submit" class="btn btn-lg btn-primary">Register</button>
            </div>
          </div>
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform'

export default {
  middleware: 'guest',

  metaInfo () {
    return { title: "Register" }
  },

  data: () => ({
    form: new Form({
      nick_name: '',
      password: '',
      password_confirmation: ''
    })
  }),

  methods: {
    async register () {
      // Register the user.
      const { data } = await this.form.post('/api/register')

      // Log in the user.
      const { data: { token } } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', { token })

      // Update the user.
      await this.$store.dispatch('auth/updateUser', { user: data })

      // Redirect home.
      this.$router.push({ name: 'home' })
    }
  }
}
</script>
