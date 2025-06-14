<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard @update-signature="setSignature" />
    
    <div class="container-fluid mt-4">
      <FlashMessage ref="flash" />

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-box"></i> Categorias {{ signature }}</h1>
        <div>
          <button class="btn btn-primary me-2" @click="abrirCadastro">
            <i class="bi bi-folder"></i> Cadastrar Categorias
          </button>
        <a href="/dashboard/estoque">  <button class="btn btn-primary me-2">
            <i class="bi bi-box-seam"></i> Produtos
          </button>
        </a>
        </div>
      </div>

      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="categoria in categorias" :key="categoria.id">
            <td>{{ categoria.name }}</td>
            <td>
              <button class="btn btn-sm btn-warning me-2" @click="abrirEdicao(categoria)">
                <i class="bi bi-pencil"></i> Editar
              </button>
              <button class="btn btn-sm btn-danger" @click="excluirCategoria(categoria.id)">
                <i class="bi bi-trash"></i> Excluir
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <ModalCategoria
        :mostrar="mostrarModal"
        :modoModal="modoModal"
        :estoque="categoriaSelecionada"
        :signature="signature"
        @fechar="fecharModal"
        @salvou="fetchCategorias"
        @mensagem="mostrarMensagem"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import ModalCategoria from '@/components/ModalCategoria.vue'

export default {
  name: 'Categoria',
  components: {
    MenuDashboard,
    FlashMessage,
    ModalCategoria
  },
  data() {
    return {
      signature: '',
      categorias: [],
      mostrarModal: false,
      modoModal: 'criar',
      categoriaSelecionada: null,
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
      this.fetchCategorias()
    },
    async fetchCategorias() {
      if (!this.signature) return
      const baseURL = import.meta.env.VITE_API_URL
      try {
          const response = await axios.get(`${baseURL}/category/${this.signature}`)
        this.categorias = Array.isArray(response.data) ? response.data : []
      } catch (error) {
        console.error('Erro ao buscar categorias:', error)
        this.categorias = []
        this.mostrarMensagem({ texto: 'Erro ao buscar categorias.', tipo: 'error' })
      }
    },
    abrirCadastro() {
      this.categoriaSelecionada = null
      this.modoModal = 'criar'
      this.mostrarModal = true
    },
    abrirEdicao(categoria) {
      this.categoriaSelecionada = { ...categoria }
      this.modoModal = 'editar'
      this.mostrarModal = true
    },
    fecharModal() {
      this.mostrarModal = false
    },
    async excluirCategoria(id) {
      if (!confirm('Deseja realmente excluir esta categoria?')) return
      const baseURL = import.meta.env.VITE_API_URL
      try {
        await axios.delete(`${baseURL}/category/${id}`)
        this.fetchCategorias()
        this.mostrarMensagem({ texto: 'Categoria excluída com sucesso!', tipo: 'success' })
      } catch (error) {
        console.error('Erro ao excluir categoria:', error)
        this.mostrarMensagem({ texto: 'Erro ao excluir categoria.', tipo: 'error' })
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
