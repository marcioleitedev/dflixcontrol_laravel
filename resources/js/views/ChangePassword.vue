<template>
   <Navbar />
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Alterar sua Senha</h2>
            <form @submit.prevent="forgot">
              <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" v-model="form.email" class="form-control" id="email" required readonly />
              </div>
<div class="position-relative">
  <label for="password" class="form-label">Nova Senha</label>
  <div class="input-group">
    <input
      :type="showPassword ? 'text' : 'password'"
      v-model="form.password"
      class="form-control"
      id="password"
      required
    />
    <span class="input-group-text" @click="togglePassword" style="cursor: pointer;">
      <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
    </span>
  </div>
</div>
              <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                Entrar
              </button>
            </form>
            <p v-if="error" class="alert alert-danger mt-3 mb-0 text-center">{{ error }}</p>
          <br>
            <p><a href="/forgot">Esqueci minha senha</a></p> 
          </div>
         
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script>
import axios from 'axios'
import { useRouter } from 'vue-router'
import { onMounted } from 'vue'

import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

export default {
  name: 'Login',
    components: {
    Navbar, Footer
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      error: '',
      loading: false,
      showPassword: false,
    }
  },
  mounted(){
     const email = this.$route.query.email
    if (email) {
      this.form.email = email
    }
  },
  methods: {
    async forgot() {
      this.loading = true
      this.error = ''

      try {
        const baseURL = import.meta.env.VITE_API_URL
        const response = await axios.post(`${baseURL}/change`, this.form)

        localStorage.setItem('token', response.data.access_token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`

        this.$router.push('/login')
      } catch (err) {
        this.error = 'E-mail ou senha inválidos'
      } finally {
        this.loading = false
      }
    },
     togglePassword() {
    this.showPassword = !this.showPassword
  }
  },
}
</script>

<style scoped>
.container{
  margin-top: 150px;
  margin-bottom: 150px;
}
.login-container {
  max-width: 400px;

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
