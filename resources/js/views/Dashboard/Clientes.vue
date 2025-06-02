<!-- PerfilAfiliadoDashboard.vue -->
<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard />

    <div class="container-fluid mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Clientes - Usuário ID: {{ userId }}</h1>
        <button class="btn btn-primary" @click="abrirCadastro">Cadastrar Cliente</button>
      </div>

      <FlashMessage ref="flashRef" />

      <table class="table table-bordered" v-if="perfis.length">
        <thead>
          <tr>
            <th>Empresa</th>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="perfil in perfis" :key="perfil.id">
            <td>{{ perfil.company_name }}</td>
            <td>{{ perfil.phone }}</td>
            <td>{{ perfil.address }}</td>
            <td>{{ perfil.address }}</td>
   
            <td>
              <button class="btn btn-sm btn-warning me-2" @click="abrirEdicao(perfil)">Editar</button>
              <!-- <button class="btn btn-sm btn-danger" @click="excluirPerfil(perfil.id)">Excluir</button> -->
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else class="alert alert-info">Nenhum Cliente Encontrado.</div>
    </div>

    <div v-if="mostrarModal" class="modal fade show d-block" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modoModal === 'criar' ? 'Cadastrar Cliente' : 'Editar Cliente' }}</h5>
            <button type="button" class="btn-close" @click="fecharModal"></button>
          </div>
          <div class="modal-body">
            <FormularioAssinatura
              :modo="modoModal"
              :perfil-afiliado="perfilSelecionado"
              :user-id="userId"
              @fechar="fecharModal"
              @atualizar="onAtualizarPerfil"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import FormularioAssinatura from '@/components/FormularioAssinatura.vue'

export default {
  name: 'ClientesDashboard',
  components: {
    MenuDashboard,
    FlashMessage,
    FormularioAssinatura
  },
  data() {
    return {
      userId: null,
      perfis: [],
      mostrarModal: false,
      modoModal: 'criar',
      perfilSelecionado: null
    }
  },
  mounted() {
    const token = localStorage.getItem('token')
    if (!token) return this.$router.push('/login')

    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
    this.userId = this.extrairUserIdDoToken()
    if (this.userId) {
      this.fetchPerfil()
    } else {
      this.$refs.flashRef.showMessage('Token inválido. Faça login novamente.', 'danger')
    }
  },
  methods: {
    extrairUserIdDoToken() {
      try {
        const token = localStorage.getItem('token')
        const payload = JSON.parse(atob(token.split('.')[1]))
        return payload.sub || null
      } catch (error) {
        console.error('Erro ao decodificar o token:', error)
        return null
      }
    },
    async fetchPerfil() {
  try {
    const res = await axios.get(`http://localhost:8000/api/signature/${this.userId}`)
    this.perfis = res.data.data 
  } catch (err) {
    console.error(err)
    this.$refs.flashRef.showMessage('Erro ao buscar afiliado.', 'danger')
  }
},
    abrirCadastro() {
      this.perfilSelecionado = null
      this.modoModal = 'criar'
      this.mostrarModal = true
    },
    abrirEdicao(perfil) {
      this.perfilSelecionado = { ...perfil }
      this.modoModal = 'editar'
      this.mostrarModal = true
    },
    fecharModal() {
      this.mostrarModal = false
    },
    onAtualizarPerfil() {
      this.fetchPerfil()
      this.fecharModal()
    },
    async excluirPerfil(id) {
      if (!confirm('Deseja realmente excluir este perfil?')) return

      try {
        await axios.delete(`http://127.0.0.1:8000/api/signature/${id}`)
        this.$refs.flashRef.showMessage('Perfil excluído com sucesso.', 'success')
        this.fetchPerfil()
      } catch (err) {
        console.error(err)
        this.$refs.flashRef.showMessage('Erro ao excluir perfil.', 'danger')
      }
    }
  }
}
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
.modal-dialog {
  max-width: 800px;
  margin: 1.75rem auto;
}
</style>