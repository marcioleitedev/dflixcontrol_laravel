<template>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Login</h2>
            <form @submit.prevent="login">
              <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" v-model="form.email" class="form-control" id="email" required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" v-model="form.password" class="form-control" id="password" required />
              </div>
              <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                Entrar
              </button>
            </form>
            <p v-if="error" class="alert alert-danger mt-3 mb-0 text-center">{{ error }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Login',
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      error: '',
      loading: false,
    }
  },
  methods: {
    async login() {
      this.loading = true
      this.error = ''

      try {
        const response = await axios.post('/api/login', this.form)

        localStorage.setItem('token', response.data.access_token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`

        this.$router.push('/dashboard')
      } catch (err) {
        this.error = 'E-mail ou senha inválidos'
      } finally {
        this.loading = false
      }
    },
  },
}
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 40px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 6px;
}
input {
  width: 100%;
  padding: 8px;
  margin-bottom: 12px;
}
button {
  padding: 8px 16px;
}
.error {
  color: red;
  margin-top: 10px;
}
</style>
