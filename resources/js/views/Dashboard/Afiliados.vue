<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard />

    <div class="container-fluid mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Afiliados</h1>
        <button class="btn btn-primary" @click="abrirCadastro">Cadastrar Afiliado</button>
      </div>

      <!-- Flash message -->
      <FlashMessage ref="flashRef" />

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Afiliado</th>
            <th>Email</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="assinatura in assinaturas" :key="assinatura.id">
            <td>{{ assinatura.name }}</td>
            <td>{{ assinatura.email }}</td>
            <td>
              <button class="btn btn-sm btn-warning me-2" @click="abrirEdicao(assinatura)">Editar</button>
              <button class="btn btn-sm btn-danger" @click="excluirAfiliado(assinatura.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>

      <nav>
        <ul class="pagination">
          <li class="page-item" :class="{ disabled: !pagination.prev_page_url }">
            <button class="page-link" @click="fetchAssinaturas(pagination.current_page - 1)">Anterior</button>
          </li>
          <li v-for="link in pagination.links" :key="link.label" class="page-item" :class="{ active: link.active, disabled: !link.url }">
            <button class="page-link" v-html="link.label" @click="link.url && goToPage(link.url)"></button>
          </li>
          <li class="page-item" :class="{ disabled: !pagination.next_page_url }">
            <button class="page-link" @click="fetchAssinaturas(pagination.current_page + 1)">Próximo</button>
          </li>
        </ul>
      </nav>
    </div>

    <ModalAfiliado
      v-if="mostrarModal"
      :modo="modoModal"
      :afiliado="afiliadoSelecionado"
      @fechar="fecharModal"
      @atualizar="onAtualizarAfiliado"
    />
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import ModalAfiliado from '@/components/ModalAfiliado.vue'
import FlashMessage from '@/components/FlashMessage.vue'

export default {
  name: 'Dashboard',
  components: {
    MenuDashboard,
    ModalAfiliado,
    FlashMessage,
  },
  data() {
    return {
      assinaturas: [],
      pagination: {},
      mostrarModal: false,
      modoModal: 'criar', // ou 'editar'
      afiliadoSelecionado: null,
    }
  },
  mounted() {
    const token = localStorage.getItem('token')
    if (!token) {
      this.$router.push('/login')
    } else {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      this.fetchAssinaturas()
    }
  },
  methods: {
    async fetchAssinaturas(page = 1) {
      try {
        const baseURL = import.meta.env.VITE_API_URL
        const response = await axios.get(`${baseURL}/affiliate?page=${page}`)
        this.assinaturas = response.data.data
        this.pagination = {
          current_page: response.data.current_page,
          next_page_url: response.data.next_page_url,
          prev_page_url: response.data.prev_page_url,
          links: response.data.links,
        }
      } catch (error) {
        console.error('Erro ao buscar afiliados:', error)
        this.$refs.flashRef.mostrar('Erro ao buscar afiliados.', 'danger')
      }
    },
    goToPage(url) {
      const urlObj = new URL(url)
      const page = urlObj.searchParams.get('page')
      this.fetchAssinaturas(page)
    },
    abrirCadastro() {
      this.afiliadoSelecionado = null
      this.modoModal = 'criar'
      this.mostrarModal = true
    },
    abrirEdicao(afiliado) {
      this.afiliadoSelecionado = { ...afiliado }
      this.modoModal = 'editar'
      this.mostrarModal = true
    },
    fecharModal() {
      this.mostrarModal = false
      document.body.style.overflow = ''
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove())
      document.body.classList.remove('modal-open')
    },
    async excluirAfiliado(id) {
      if (confirm('Deseja realmente excluir este afiliado?')) {
        const baseURL = import.meta.env.VITE_API_URL
        try {
          
          await axios.delete(`${baseURL}/affiliate/${id}`)
          this.fetchAssinaturas()
         
          this.$refs.flashRef.showMessage('Afiliado excluído com sucesso!', 'success')
        } catch (error) {
          console.error('Erro ao excluir afiliado:', error)
          this.$refs.flashRef.showMessage('Erro ao excluir afiliado.', 'danger')
        }
      }
    },
    onAtualizarAfiliado(acao) {
      this.fetchAssinaturas()
      this.fecharModal()
      if (acao === 'criado') {
        this.$refs.flashRef.showMessage('Afiliado cadastrado com sucesso!', 'success')
      } else if (acao === 'editado') {
        this.$refs.flashRef.showMessage('Afiliado atualizado com sucesso!', 'success')
      }
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
