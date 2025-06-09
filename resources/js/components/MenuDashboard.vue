<template>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div
      class="border-end bg-white sidebar"
      :class="{ 'd-none': isSidebarHidden }"
      id="sidebar-wrapper"
    >
      <div class="sidebar-heading border-bottom bg-light p-3">
        <strong>{{ userName || 'Dflix Control' }}</strong><br>
        <strong>ID Signer: {{ signature }}</strong>
      </div>

      <div v-if="roles.length" class="list-group list-group-flush">
        <router-link
          v-for="(role, index) in roles"
          :key="index"
          :to="role.link_url"
          class="list-group-item list-group-item-action"
        >
          <i :class="role.icon"></i> {{ role.bottom_text }}
        </router-link>
      </div>

      <a
        href="#"
        class="list-group-item list-group-item-action text-danger mt-2"
        @click="logout"
      >
        Sair
      </a>
    </div>

    <!-- Page content -->
    <div id="page-content-wrapper" class="flex-grow-1">
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
          <button class="btn btn-outline-primary" @click="toggleSidebar">☰</button>
        </div>
      </nav>

      <div class="container-fluid mt-4">
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'MenuDashboard',
  data() {
    return {
      isSidebarHidden: false,
      roles: [],
      userName: '',
      signature: '',
    }
  },
  async mounted() {
    const token = localStorage.getItem('token')
    if (!token) {
      this.$router.push('/login')
      return
    }

    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    const payload = this.parseJwt(token)

    this.userName = payload?.name || payload?.nome || ''
    this.signature = payload?.id_signature || ''
    this.$emit('update-signature', this.signature) // <-- Emite para o pai

    try {
      const baseURL = import.meta.env.VITE_API_URL
      const response = await axios.get(`${baseURL}/users/${payload?.sub}`)
      this.roles = response.data.roles || []
    } catch (error) {
      console.error('Erro ao buscar permissões:', error)
      this.$router.push('/login')
    }
  },
  methods: {
    toggleSidebar() {
      this.isSidebarHidden = !this.isSidebarHidden
    },
    logout() {
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
      this.$router.push('/login')
    },
    parseJwt(token) {
      try {
        const base64Url = token.split('.')[1]
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/')
        const jsonPayload = decodeURIComponent(
          atob(base64)
            .split('')
            .map(c => '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2))
            .join('')
        )
        return JSON.parse(jsonPayload)
      } catch (e) {
        return null
      }
    }
  },
}
</script>

<style scoped>
#wrapper {
  display: flex;
  flex-direction: row;
  min-height: 100vh;
}

.sidebar {
  width: 250px;
  transition: all 0.3s ease;
}

#page-content-wrapper {
  flex: 1;
}
</style>
