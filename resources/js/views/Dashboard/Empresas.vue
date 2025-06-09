<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard @update-signature="setSignature" />

    <div class="container-fluid mt-4">
      <!-- Flash message -->
      <FlashMessage ref="flash" />

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-building"></i> Empresas </h1>
        <button class="btn btn-primary" @click="abrirCadastro">Cadastrar Empresa</button>
      </div>

      <!-- Lista de empresas -->
      <div v-if="empresas.length">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nome</th>
              <th>CNPJ</th>
              <th>Email</th>
              <th>Telefone</th>
              <th>Cidade</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="empresa in empresas" :key="empresa.id">
              <td>{{ empresa.name }}</td>
              <td>{{ empresa.cnpj }}</td>
              <td>{{ empresa.email }}</td>
              <td>{{ empresa.phone }}</td>
              <td>{{ empresa.city }} - {{ empresa.state }}</td>
              <td>
                <button class="btn btn-sm btn-warning" @click="abrirEdicao(empresa)">Editar</button>
                <button class="btn btn-sm btn-danger" @click="excluirEmpresa(empresa.id)">Excluir</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else>
        <p>Nenhuma empresa cadastrada para esta assinatura.</p>
      </div>
    </div>

    <!-- Modal de empresa -->
    <ModalEmpresa
      v-if="mostrarModal"
      :empresa="empresaSelecionada"
      :modo="modoModal"
      :signature="signature"
      @fechar="fecharModal"
      @atualizar="fetchEmpresas"
      @mensagem="mostrarMensagem"
    />
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import ModalEmpresa from '@/components/ModalEmpresa.vue'
import FlashMessage from '@/components/FlashMessage.vue'

export default {
  name: 'DashboardEmpresas',
  components: {
    MenuDashboard,
    ModalEmpresa,
    FlashMessage,
  },
  data() {
    return {
      signature: '',
      empresas: [],
      mostrarModal: false,
      modoModal: 'criar',
      empresaSelecionada: null,
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
      this.fetchEmpresas()
    },
    async fetchEmpresas() {
      if (!this.signature) return
      const baseURL = import.meta.env.VITE_API_URL
      try {
        const response = await axios.get(`${baseURL}/company/${this.signature}`)
        this.empresas = Array.isArray(response.data) ? response.data : []
      } catch (error) {
        console.error('Erro ao buscar empresas:', error)
        this.empresas = []
        this.mostrarMensagem({ texto: 'Erro ao buscar empresas.', tipo: 'error' })
      }
    },
    abrirCadastro() {
      this.empresaSelecionada = null
      this.modoModal = 'criar'
      this.mostrarModal = true
    },
    abrirEdicao(empresa) {
      this.empresaSelecionada = { ...empresa }
      this.modoModal = 'editar'
      this.mostrarModal = true
    },
    fecharModal() {
      this.mostrarModal = false
    },
    async excluirEmpresa(id) {
      if (!confirm('Deseja realmente excluir esta empresa?')) return
      const baseURL = import.meta.env.VITE_API_URL
      try {
        await axios.delete(`${baseURL}/company/${id}`)
        this.fetchEmpresas()
        this.mostrarMensagem({ texto: 'Empresa excluída com sucesso!', tipo: 'success' })
      } catch (error) {
        console.error('Erro ao excluir empresa:', error)
        this.mostrarMensagem({ texto: 'Erro ao excluir empresa.', tipo: 'error' })
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
