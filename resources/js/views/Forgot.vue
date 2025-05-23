<template>
   <Navbar />
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Esqueceu sua Senha</h2>
            <form @submit.prevent="login">
              <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" v-model="form.email" class="form-control" id="email" required />
              </div>

              <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                Solicitar Lembreete 
              </button>
            </form>
            <p v-if="error" class="alert alert-danger mt-3 mb-0 text-center">{{ error }}</p>
          <br>
      
          </div>
         
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script>
import axios from 'axios'

import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

export default {
  name: 'Forgot',
    components: {
    Navbar, Footer
  },
  data() {
    return {
      form: {
        email: '',
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
        const response = await axios.post('/api/forgot', this.form)
        this.error = 'Verifique seu e-mail para redefinir sua senha'
        this.$router.push('/login')
      } catch (err) {
        this.error = 'E-mail não existe na nossa base como cliente'
      } finally {
        this.loading = false
      }
    },
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
