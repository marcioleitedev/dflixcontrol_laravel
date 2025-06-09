<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard @update-signature="setSignature" />
    <div class="container-fluid mt-4">
      <FlashMessage ref="flash" />

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-box"></i> Template{{ signature }} </h1>
        <div>
          <button class="btn btn-primary me-2" @click="abrirCadastro">
            <i class="bi-folder"></i> CADASTRAR
          </button>

        </div>
      </div>

      <ModalTemplate
  :mostrar="mostrarModal"
  :modoModal="modoModal"
  :estoque="estoqueSelecionada"
  :signature="signature"
  @fechar="fecharModal"
  @salvou="fetchEstoque"
/>

    </div>
  </div>
</template>

  
  <script>
  import axios from 'axios'
  import MenuDashboard from '@/components/MenuDashboard.vue'
  import FlashMessage from '@/components/FlashMessage.vue'
  import ModalTemplate from '@/components/ModalTemplate.vue'
  
  export default {
    name: 'Template',
    components: {
      MenuDashboard,
      FlashMessage,
      ModalTemplate
    },
    data() {
      return {
        signature: '',
        estoques: [],
        mostrarModal: false,
        modoModal: 'criar',
        estoqueSelecionada: null,
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
        this.fetchEstoque()
      },
      async fetchEstoque() {
        if (!this.signature) return
        const baseURL = import.meta.env.VITE_API_URL
        try {
          const response = await axios.get(`${baseURL}/company/${this.signature}`)
          this.estoque = Array.isArray(response.data) ? response.data : []
        } catch (error) {
          console.error('Erro ao buscar estoque:', error)
          this.estoque = []
          this.mostrarMensagem({ texto: 'Erro ao buscar estoque.', tipo: 'error' })
        }
      },
      abrirCadastro() {
        this.estoqueSelecionada = null
        this.modoModal = 'criar'
        this.mostrarModal = true
      },
      abrirEdicao(estoque) {
        this.estoqueSelecionada = { ...estoque }
        this.modoModal = 'editar'
        this.mostrarModal = true
      },
      fecharModal() {
        this.mostrarModal = false
      },
      async excluirEstoque(id) {
        if (!confirm('Deseja realmente excluir esta estoque?')) return
        const baseURL = import.meta.env.VITE_API_URL
        try {
          await axios.delete(`${baseURL}/company/${id}`)
          this.fetchEstoque()
          this.mostrarMensagem({ texto: 'estoque excluída com sucesso!', tipo: 'success' })
        } catch (error) {
          console.error('Erro ao excluir estoque:', error)
          this.mostrarMensagem({ texto: 'Erro ao excluir estoque.', tipo: 'error' })
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
  