<template>
  <div class="d-flex" id="wrapper">
    <!-- Componente de menu emitindo assinatura selecionada -->
    <MenuDashboard @update-signature="setSignature" />

    <div class="container-fluid mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Assinatura {{ signature || '—' }}</h1>
      </div>

      <div v-if="assinatura">
        <h2>{{ assinatura.name }}</h2>
        <p><strong>Email:</strong> {{ assinatura.email }}</p>
        <p><strong>Empresa:</strong> {{ assinatura.company_name }}</p>
        <p><strong>Plano:</strong> {{ assinatura.plan.name }}</p>
        <p><strong>Valor:</strong> R$ {{ assinatura.plan.price }}</p>

        <h4>Pagamento</h4>
        <div v-for="pagamento in assinatura.payments" :key="pagamento.id">
          <p><strong>Método:</strong> {{ pagamento.payment_method }}</p>
          <p><strong>Status:</strong> {{ pagamento.status ? 'Pago' : 'Pendente' }}</p>
          <p><strong>Data limite:</strong> 
            {{ formatDate(pagamento.limit_date) }} 
            ({{ diasParaVencimento(pagamento.limit_date) }})
          </p>
          <hr />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import MenuDashboard from '@/components/MenuDashboard.vue'

export default {
  name: 'Dashboard',
  components: {
    MenuDashboard,
  },
  data() {
    return {
      signature: '',       // ID da assinatura recebido do componente filho
      assinatura: null,    // Objeto com os dados da assinatura
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
      this.fetchAssinatura()
    },
    async fetchAssinatura() {
      if (!this.signature) return
      const baseURL = import.meta.env.VITE_API_URL
      try {
        const response = await axios.get(`${baseURL}/signature/${this.signature}`)
        this.assinatura = response.data
      } catch (error) {
        console.error('Erro ao buscar a assinatura:', error)
        this.assinatura = null
      }
    },
    formatDate(date) {
      if (!date) return '—'
      return new Date(date).toLocaleDateString('pt-BR')
    },
    diasParaVencimento(dateStr) {
      if (!dateStr) return '—'

      const hoje = new Date()
      const vencimento = new Date(dateStr)

      hoje.setHours(0, 0, 0, 0)
      vencimento.setHours(0, 0, 0, 0)

      const diffEmMs = vencimento - hoje
      const diffEmDias = Math.ceil(diffEmMs / (1000 * 60 * 60 * 24))

      if (diffEmDias > 0) {
        return `Faltam ${diffEmDias} dia(s)`
      } else if (diffEmDias < 0) {
        return `Atrasado há ${Math.abs(diffEmDias)} dia(s)`
      } else {
        return 'Vence hoje'
      }
    },
  },
}
</script>

<style scoped>
/* Estilo opcional */
</style>
