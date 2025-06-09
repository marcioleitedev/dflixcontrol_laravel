<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard />

    <div class="container-fluid mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Roles</h1>
        <button class="btn btn-primary" @click="abrirCadastro">Cadastrar Role</button>
      </div>

      <FlashMessage ref="flashRef" />

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nível</th>
            <th>Texto Inferior</th>
            <th>URL</th>
            <th>Ícone</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="role in roles" :key="role.id">
            <td>{{ role.level }}</td>
            <td>{{ role.bottom_text }}</td>
            <td>{{ role.link_url }}</td>
            <td>{{ role.icon }}</td>
            <td>
              <button class="btn btn-sm btn-warning me-2" @click="abrirEdicao(role)">Editar</button>
              <button class="btn btn-sm btn-danger" @click="excluirRole(role.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>

      <nav>
        <ul class="pagination">
          <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
            <button class="page-link" @click="fetchRoles(pagination.current_page - 1)">Anterior</button>
          </li>
          <li v-for="link in pagination.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
            <button class="page-link" v-html="link.label" @click="link.url && goToPage(link.url)"></button>
          </li>
          <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
            <button class="page-link" @click="fetchRoles(pagination.current_page + 1)">Próximo</button>
          </li>
        </ul>
      </nav>
    </div>

    <ModalRole
      v-if="mostrarModal"
      :modo="modoModal"
      :role="roleSelecionado"
      @fechar="fecharModal"
      @atualizar="onAtualizarRole"
    />
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import ModalRole from '@/components/ModalRole.vue'
import FlashMessage from '@/components/FlashMessage.vue'

export default {
  name: 'RoleDashboard',
  components: {
    MenuDashboard,
    ModalRole,
    FlashMessage,
  },
  data() {
    return {
      roles: [],
      pagination: {},
      mostrarModal: false,
      modoModal: 'criar',
      roleSelecionado: null,
    }
  },
  mounted() {
    const token = localStorage.getItem('token')
    if (!token) {
      this.$router.push('/login')
    } else {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      this.fetchRoles()
    }
  },
  methods: {
    async fetchRoles(page = 1) {
      const baseURL = import.meta.env.VITE_API_URL
      try {
        const response = await axios.get(`${baseURL}/roles?page=${page}`)
        this.roles = response.data.data
        this.pagination = {
          current_page: response.data.current_page,
          next_page_url: response.data.next_page_url,
          prev_page_url: response.data.prev_page_url,
          links: response.data.links,
        }
      } catch (error) {
        console.error('Erro ao buscar roles:', error)
        this.$refs.flashRef.showMessage('Erro ao buscar roles.', 'danger')
      }
    },
    goToPage(url) {
      const urlObj = new URL(url)
      const page = urlObj.searchParams.get('page')
      this.fetchRoles(page)
    },
    abrirCadastro() {
      this.roleSelecionado = null
      this.modoModal = 'criar'
      this.mostrarModal = true
    },
    abrirEdicao(role) {
      this.roleSelecionado = { ...role }
      this.modoModal = 'editar'
      this.mostrarModal = true
    },
    fecharModal() {
      this.mostrarModal = false
      document.body.style.overflow = ''
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove())
      document.body.classList.remove('modal-open')
    },
    async excluirRole(id) {
      if (confirm('Deseja realmente excluir este role?')) {
        const baseURL = import.meta.env.VITE_API_URL
        try {
          await axios.delete(`${baseURL}/roles/${id}`)
          this.fetchRoles()
          this.$refs.flashRef.showMessage('Role excluído com sucesso!', 'success')
        } catch (error) {
          console.error('Erro ao excluir role:', error)
          this.$refs.flashRef.showMessage('Erro ao excluir role.', 'danger')
        }
      }
    },
    onAtualizarRole(acao) {
      this.fetchRoles()
      this.fecharModal()
      const mensagens = {
        criado: 'Role cadastrado com sucesso!',
        editado: 'Role atualizado com sucesso!',
      }
      this.$refs.flashRef.showMessage(mensagens[acao], 'success')
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
.table {
  background-color: white;
}
</style>
