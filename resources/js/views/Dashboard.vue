<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard />
    

      <div class="container-fluid mt-4">
        <h1>Bem-vindo ao sistema Auto Center!</h1>
        <p>Aqui você pode gerenciar seu negócio.</p>
      </div>
    </div>
  <!-- </div> -->
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'

export default {
  name: 'Dashboard',
  data() {
    return {
      isSidebarHidden: false,
    }
  },
  components:{
    MenuDashboard,
  },
  mounted() {
    const token = localStorage.getItem('token')
    if (!token) {
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

/* Responsivo */
@media (max-width: 900px) {
  .sidebar {
    position: absolute;
    z-index: 1030;
    height: 100%;
    background: white;
  }
}
</style>
