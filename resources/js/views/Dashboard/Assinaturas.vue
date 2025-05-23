<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard />

    <div class="container-fluid mt-4">
      <h1>Assinaturas!</h1>
      <p>Aqui você pode gerenciar seu negócio.</p>

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Empresa</th>
            <th>Cliente</th>
            <th>Telefone</th>
            <th>Plano</th>
            <th>Valor</th>
            <th>Vencimento</th>
            <th>Situação</th>
            <th>Afiliado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="assinatura in assinaturas" :key="assinatura.id">
            <td>{{ assinatura.company_name }}</td>
            <td>{{ assinatura.name }}</td>
            <td>{{ assinatura.phone }}</td>
            <td>{{ assinatura.plan.name }}</td>
            <td>{{ assinatura.plan.price.toFixed(2) }}</td>
            <td>{{ formatDate(assinatura.payments[0]?.limit_date) }}</td>
            <td>{{ diasParaVencimento(assinatura.payments[0]?.limit_date) }}</td>
            <td>{{ assinatura.affiliate.name }}</td>
          </tr>
        </tbody>
      </table>

      <nav>
        <ul class="pagination">
          <li
            class="page-item"
            :class="{ disabled: !pagination.prev_page_url }"
          >
            <button class="page-link" @click="fetchAssinaturas(pagination.current_page - 1)">Anterior</button>
          </li>
          <li
            v-for="link in pagination.links"
            :key="link.label"
            class="page-item"
            :class="{ active: link.active, disabled: !link.url }"
          >
            <button
              class="page-link"
              v-html="link.label"
              @click="link.url && goToPage(link.url)"
            ></button>
          </li>
          <li
            class="page-item"
            :class="{ disabled: !pagination.next_page_url }"
          >
            <button class="page-link" @click="fetchAssinaturas(pagination.current_page + 1)">Próximo</button>
          </li>
        </ul>
      </nav>
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
      assinaturas: [],
      pagination: {},
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
        const response = await axios.get(`http://127.0.0.1:8000/api/signature?page=${page}`)
        this.assinaturas = response.data.data
        this.pagination = {
          current_page: response.data.current_page,
          next_page_url: response.data.next_page_url,
          prev_page_url: response.data.prev_page_url,
          links: response.data.links,
        }
      } catch (error) {
        console.error('Erro ao buscar assinaturas:', error)
      }
    },
    goToPage(url) {
      const urlObj = new URL(url)
      const page = urlObj.searchParams.get('page')
      this.fetchAssinaturas(page)
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
#wrapper {
  display: flex;
  flex-direction: row;
  min-height: 100vh;
}
.table {
  background-color: white;
}
</style>
