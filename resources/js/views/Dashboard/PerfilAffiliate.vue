<!-- PerfilAfiliadoDashboard.vue -->
<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard />

    <div class="container-fluid mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Afiliados - Usuário ID: {{ userId }}</h1>
        <button class="btn btn-primary" @click="abrirCadastro">Cadastrar Afiliado</button>
      </div>

      <FlashMessage ref="flashRef" />

      <table class="table table-bordered" v-if="perfis.length">
        <thead>
          <tr>
            <th>CPF</th>
            <th>Telefone</th>
            <th>Data Nascimento</th>
            <th>Sexo</th>
            <th>Link Afiliado</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="perfil in perfis" :key="perfil.id">
            <td>{{ perfil.cpf }}</td>
            <td>{{ perfil.telefone }}</td>
            <td>{{ perfil.data_nascimento }}</td>
            <td>{{ perfil.sexo }}</td>
            <td>{{ perfil.link_afiliado }}</td>
            <td>{{ perfil.status }}</td>
            <td>
              <button class="btn btn-sm btn-warning me-2" @click="abrirEdicao(perfil)">Editar</button>
              <button class="btn btn-sm btn-danger" @click="excluirPerfil(perfil.id)">Excluir</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else class="alert alert-info">Nenhum afiliado encontrado.</div>
    </div>

    <ModalPerfilAfiliado
      v-if="mostrarModal"
      :modo="modoModal"
      :perfil-afiliado="perfilSelecionado"
      :user-id="userId"
      @fechar="fecharModal"
      @atualizar="onAtualizarPerfil"
    />
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import ModalPerfilAfiliado from '@/components/ModalPerfilAfiliado.vue'

export default {
  name: 'PerfilAfiliadoDashboard',
  components: {
    MenuDashboard,
    FlashMessage,
    ModalPerfilAfiliado
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
      const baseURL = import.meta.env.VITE_API_URL
      try {
        const res = await axios.get(`${baseURL}/profile/${this.userId}`)
        this.perfis = [res.data] // manter compatibilidade com v-for
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
      const baseURL = import.meta.env.VITE_API_URL
      try {
        await axios.delete(`${baseURL}/profile/${this.userId}`)
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
/* Estilos opcionais aqui */
</style>
