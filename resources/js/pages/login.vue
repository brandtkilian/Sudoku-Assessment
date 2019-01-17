<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card title="Login">
        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
          <!-- Email -->
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

          <!-- Remember Me -->
          <div class="form-group row">
            <div class="col-md-3"/>
            <div class="col-md-7 d-flex">
              <checkbox v-model="remember" name="remember">Remember me</checkbox>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-7 offset-md-3 d-flex">
              <!-- Submit Button -->
              <button type="submit" class="btn btn-lg btn-primary">Login</button>
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

  components: {
  },

  metaInfo () {
    return { title: 'Login' }
  },

  data: () => ({
    form: new Form({
      nick_name: '',
      password: ''
    }),
    remember: false
  }),

  methods: {
    async login () {
      // Submit the form.
      const { data } = await this.form.post('/api/login')

      // Save the token.
      this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      })

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser')
      this.$router.push({ name: 'home'})
    }
  }
}
</script>
