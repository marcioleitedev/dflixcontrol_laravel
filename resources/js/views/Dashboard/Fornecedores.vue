<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard @update-signature="setSignature" />
    <div class="container-fluid mt-4">
      <FlashMessage ref="flash" />

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-box"></i> Fornecedores {{ signature }} </h1>
        <div>
          <button class="btn btn-primary me-2" @click="abrirCadastro">
            <i class="bi-folder"></i> CADASTRAR
          </button>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="mb-3">
        <div class="input-group">
          <input 
            type="text" 
            class="form-control" 
            placeholder="Buscar fornecedor..." 
            v-model="searchTerm"
            @keyup.enter="fetchFornecedores"
          >
          <button class="btn btn-outline-secondary" @click="fetchFornecedores">
            <i class="bi bi-search"></i> Buscar
          </button>
        </div>
      </div>

      <!-- Suppliers Table -->
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Email</th>
              <th>Telefone</th>
              <th>CNPJ</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="fornecedor in fornecedores.data" :key="fornecedor.id">
              <td>{{ fornecedor.name }}</td>
              <td>{{ fornecedor.email }}</td>
              <td>{{ fornecedor.phone }}</td>
              <td>{{ fornecedor.cnpj }}</td>
              <td>
                <button class="btn btn-sm btn-warning me-2" @click="abrirEdicao(fornecedor)">
                  <i class="bi bi-pencil"></i> Editar
                </button>
                <button class="btn btn-sm btn-danger" @click="excluirFornecedor(fornecedor.id)">
                  <i class="bi bi-trash"></i> Excluir
                </button>
              </td>
            </tr>
            <tr v-if="fornecedores.data && fornecedores.data.length === 0">
              <td colspan="5" class="text-center">Nenhum fornecedor encontrado</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav v-if="fornecedores.last_page > 1">
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: fornecedores.current_page === 1 }">
            <button class="page-link" @click="changePage(fornecedores.current_page - 1)">Anterior</button>
          </li>
          <li 
            class="page-item" 
            v-for="page in fornecedores.last_page" 
            :key="page"
            :class="{ active: fornecedores.current_page === page }"
          >
            <button class="page-link" @click="changePage(page)">{{ page }}</button>
          </li>
          <li class="page-item" :class="{ disabled: fornecedores.current_page === fornecedores.last_page }">
            <button class="page-link" @click="changePage(fornecedores.current_page + 1)">Próxima</button>
          </li>
        </ul>
      </nav>

      <ModalFornecedores
        :mostrar="mostrarModal"
        :modoModal="modoModal"
        :fornecedor="fornecedorSelecionado"
        :signature="signature"
        @fechar="fecharModal"
        @salvou="fetchFornecedores"
          @salvou-com-sucesso="mostrarMensagem"
  @erro-ao-salvar="mostrarMensagem"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import ModalFornecedores from '@/components/ModalFornecedores.vue'

export default {
  name: 'Fornecedores',
  components: {
    MenuDashboard,
    FlashMessage,
    ModalFornecedores
  },
  data() {
    return {
      signature: '',
      fornecedores: {
        data: [],
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0
      },
      mostrarModal: false,
      modoModal: 'criar',
      fornecedorSelecionado: null,
      searchTerm: ''
    }
  },
  mounted() {
    const token = localStorage.getItem('token')
    if (!token) {
      this.$router.push('/login')
    } else {
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }
  },
  methods: {
    setSignature(sig) {
      this.signature = sig
      this.fetchFornecedores()
    },
    async fetchFornecedores() {
      if (!this.signature) return
      
      const baseURL = import.meta.env.VITE_API_URL
      try {
        const params = {
          page: this.fornecedores.current_page,
          search: this.searchTerm
        }
        
        const response = await axios.get(`${baseURL}/fornecedores/${this.signature}`, { params })
        
        if (response.data && response.data.data) {
          this.fornecedores = {
            data: response.data.data,
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page,
            total: response.data.total
          }
        } else {
          this.fornecedores = {
            data: [],
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0
          }
          this.mostrarMensagem({ texto: 'Nenhum fornecedor encontrado.', tipo: 'info' })
        }
      } catch (error) {
        console.error('Erro ao buscar fornecedores:', error)
        this.fornecedores = {
          data: [],
          current_page: 1,
          last_page: 1,
          per_page: 10,
          total: 0
        }
        this.mostrarMensagem({ texto: 'Erro ao buscar fornecedores.', tipo: 'error' })
      }
    },
    abrirCadastro() {
      this.fornecedorSelecionado = null
      this.modoModal = 'criar'
      this.mostrarModal = true
    },
    abrirEdicao(fornecedor) {
      this.fornecedorSelecionado = { ...fornecedor }
      this.modoModal = 'editar'
      this.mostrarModal = true
    },
    fecharModal() {
      this.mostrarModal = false
    },
    async excluirFornecedor(id) {
      if (!confirm('Deseja realmente excluir este fornecedor?')) return
      const baseURL = import.meta.env.VITE_API_URL
      try {
        await axios.delete(`${baseURL}/fornecedores/${id}`)
        this.fetchFornecedores()
        this.mostrarMensagem({ texto: 'Fornecedor excluído com sucesso!', tipo: 'success' })
      } catch (error) {
        console.error('Erro ao excluir fornecedor:', error)
        this.mostrarMensagem({ texto: 'Erro ao excluir fornecedor.', tipo: 'error' })
      }
    },
    changePage(page) {
      if (page > 0 && page <= this.fornecedores.last_page) {
        this.fornecedores.current_page = page
        this.fetchFornecedores()
      }
    },
    mostrarMensagem({ texto, tipo = 'success', duracao = 3000 }) {
      if (this.$refs.flash && this.$refs.flash.showMessage) {
        this.$refs.flash.showMessage(texto, tipo, duracao)
      }
    }
  }
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