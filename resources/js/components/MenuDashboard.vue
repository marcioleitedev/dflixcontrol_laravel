<template>
  <div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div
      class="border-end bg-white sidebar"
      :class="{ 'd-none': isSidebarHidden }"
      id="sidebar-wrapper"
    >
      <div class="sidebar-heading border-bottom bg-light p-3">
        <strong>Dflix Control</strong>
      </div>
      <div class="list-group list-group-flush">
        <router-link to="/dashboard/" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2"></i> Dashboard</router-link>
        <router-link to="/dashboard/usuarios" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Usuários</router-link>
        <router-link to="/dashboard/funcionarios" class="list-group-item list-group-item-action"><i class="bi bi-person-add"></i> Funcionarios</router-link>
        <router-link to="/dashboard/fornecedores" class="list-group-item list-group-item-action"><i class="bi bi-cloud-upload"></i> Fornecedores</router-link>
        <router-link to="/dashboard/orcamentos" class="list-group-item list-group-item-action"><i class="bi bi-file-bar-graph"></i> Orçamentos</router-link>
        <router-link to="/dashboard/clientes" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Clientes</router-link>
        <router-link to="/dashboard/servicos" class="list-group-item list-group-item-action"><i class="bi bi-tools"></i> Serviços</router-link>
        <router-link to="/dashboard/estoque" class="list-group-item list-group-item-action"><i class="bi bi-box"></i> Estoque</router-link>
        <router-link to="/dashboard/vendas" class="list-group-item list-group-item-action"><i class="bi bi-magic"></i> Vendas</router-link>
        <router-link to="/dashboard/financeiro" class="list-group-item list-group-item-action"><i class="bi bi-currency-dollar"></i> Financeiro</router-link>
        <router-link to="/dashboard/nf" class="list-group-item list-group-item-action"><i class="bi bi-file-plus"></i> Nota Fiscal</router-link>
        <router-link to="/dashboard/perfil" class="list-group-item list-group-item-action"><i class="bi bi-person-fill-check"></i> Perfil</router-link>
        <router-link to="/dashboard/assinatura" class="list-group-item list-group-item-action"><i class="bi bi-pencil-square"></i> Assinatura</router-link>
        <a href="#" class="list-group-item list-group-item-action text-danger" @click="logout">Sair</a>
      </div>
      <hr>
            <div class="list-group list-group-flush">
        <router-link to="/dashboard/perfil-afiliado" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2"></i> Afiliado</router-link>
        <router-link to="/dashboard/clientes" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Clientes</router-link>
        <router-link to="/dashboard/comissoes" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Comissões</router-link>

      </div>
      <hr>
            <div class="list-group list-group-flush">
        <router-link to="/dashboard/" class="list-group-item list-group-item-action"><i class="bi bi-speedometer2"></i> Admin</router-link>
        <router-link to="/dashboard/afiliados" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Afiliados</router-link>
        <router-link to="/dashboard/assinaturas" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Assinaturas</router-link>
        <router-link to="/dashboard/cobranças" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Cobranças</router-link>
        <router-link to="/dashboard/nota-fiscal" class="list-group-item list-group-item-action"><i class="bi bi-person"></i> Nota Fiscal</router-link>

      </div>
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
  name: 'AppLayout',
  data() {
    return {
      isSidebarHidden: false,
    }
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

</style>
