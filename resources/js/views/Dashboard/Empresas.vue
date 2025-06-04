<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard @update-signature="setSignature" />

    <div class="container-fluid mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Empresas da Assinatura {{ signature || '—' }}</h1>
        <button class="btn btn-primary" @click="abrirCadastro">Cadastrar Empresa</button>
      </div>

      <!-- Lista de empresas -->
      <div v-if="empresas.length">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Email</th>
              <th>Cidade</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="empresa in empresas" :key="empresa.id">
              <td>{{ empresa.name }}</td>
              <td>{{ empresa.email }}</td>
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
    />
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import ModalEmpresa from '@/components/ModalEmpresa.vue'

export default {
  name: 'DashboardEmpresas',
  components: {
    MenuDashboard,
    ModalEmpresa,
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
      try {
        const response = await axios.get(`http://127.0.0.1:8000/api/company/${this.signature}`)
        this.empresas = Array.isArray(response.data) ? response.data : []
      } catch (error) {
        console.error('Erro ao buscar empresas:', error)
        this.empresas = []
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
      try {
        await axios.delete(`http://127.0.0.1:8000/api/company/${id}`)
        this.fetchEmpresas()
      } catch (error) {
        console.error('Erro ao excluir empresa:', error)
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
