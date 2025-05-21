<!-- resources/js/views/Dashboard.vue -->
<template>
  <div class="dashboard">
    <h1>Dashboard</h1>
    <p>Bem-vindo ao sistema Auto Center!</p>
    <button @click="logout">Sair</button>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
  mounted() {
    const token = localStorage.getItem('token')
    if (!token) {
      this.$router.push('/login')
    }
  },
  methods: {
    logout() {
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
      this.$router.push('/login')
    },
  },
}
</script>

<style scoped>
.dashboard {
  padding: 20px;
}
</style>
