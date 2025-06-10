<template>
  <div class="d-flex" id="wrapper">
    <MenuDashboard @update-signature="setSignature" />
    <div class="container-fluid mt-4">
      <FlashMessage ref="flash" />

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><i class="bi bi-people-fill"></i> Funcionários {{ signature ? `(${signature})` : '' }}</h1>
        <div>
          <button class="btn btn-primary me-2" @click="abrirCadastro">
            <i class="bi bi-person-plus"></i> CADASTRAR
          </button>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="mb-3">
        <div class="input-group">
          <input 
            type="text" 
            class="form-control" 
            placeholder="Buscar funcionário..." 
            v-model="searchTerm"
            @keyup.enter="fetchEmployees"
          >
          <button class="btn btn-outline-secondary" @click="fetchEmployees">
            <i class="bi bi-search"></i> Buscar
          </button>
          <select class="form-select" style="max-width: 100px;" v-model="perPage" @change="fetchEmployees">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
          </select>
        </div>
      </div>

      <!-- Employees Table -->
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Email</th>
              <th>Telefone</th>
              <th>CPF</th>
              <th>Cargo</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="employee in employees.data" :key="employee.id">
              <td>{{ employee.name }}</td>
              <td>{{ employee.email }}</td>
              <td>{{ formatPhone(employee.phone) }}</td>
              <td>{{ formatCPF(employee.cpf) }}</td>
              <td>{{ employee.position }}</td>
              <td>
                <span :class="`badge bg-${getStatusBadge(employee.status)}`">
                  {{ getStatusText(employee.status) }}
                </span>
              </td>
              <td>
                <button class="btn btn-sm btn-warning me-2" @click="abrirEdicao(employee)">
                  <i class="bi bi-pencil"></i> Editar
                </button>
                <button class="btn btn-sm btn-danger" @click="excluirEmployee(employee.id)">
                  <i class="bi bi-trash"></i> Excluir
                </button>
              </td>
            </tr>
            <tr v-if="employees.data && employees.data.length === 0">
              <td colspan="7" class="text-center">Nenhum funcionário encontrado</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <nav v-if="employees.last_page > 1">
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: employees.current_page === 1 }">
            <button class="page-link" @click="changePage(employees.current_page - 1)">Anterior</button>
          </li>
          <li 
            class="page-item" 
            v-for="page in employees.last_page" 
            :key="page"
            :class="{ active: employees.current_page === page }"
          >
            <button class="page-link" @click="changePage(page)">{{ page }}</button>
          </li>
          <li class="page-item" :class="{ disabled: employees.current_page === employees.last_page }">
            <button class="page-link" @click="changePage(employees.current_page + 1)">Próxima</button>
          </li>
        </ul>
      </nav>

      <ModalFuncionarios
        :mostrar="mostrarModal"
        :modoModal="modoModal"
        :employee="employeeSelecionado"
        :signature="signature"
        @fechar="fecharModal"
        @salvou="fetchEmployees"
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
import ModalFuncionarios from '@/components/ModalFuncionarios.vue'

export default {
  name: 'Funcionarios',
  components: {
    MenuDashboard,
    FlashMessage,
    ModalFuncionarios
  },
  data() {
    return {
      signature: '',
      employees: {
        data: [],
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0
      },
      mostrarModal: false,
      modoModal: 'criar',
      employeeSelecionado: null,
      searchTerm: '',
      perPage: 10
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
      this.fetchEmployees()
    },
    async fetchEmployees() {
      if (!this.signature) return
      
      const baseURL = import.meta.env.VITE_API_URL
      try {
        const params = {
          page: this.employees.current_page,
          per_page: this.perPage,
          search: this.searchTerm
        }
        
        const response = await axios.get(`${baseURL}/empregados/${this.signature}`, { params })
        
        if (response.data && response.data.data) {
          this.employees = {
            data: response.data.data,
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            per_page: response.data.per_page,
            total: response.data.total
          }
        } else {
          this.employees = {
            data: [],
            current_page: 1,
            last_page: 1,
            per_page: this.perPage,
            total: 0
          }
          this.mostrarMensagem({ texto: 'Nenhum funcionário encontrado.', tipo: 'info' })
        }
      } catch (error) {
        console.error('Erro ao buscar funcionários:', error)
        this.employees = {
          data: [],
          current_page: 1,
          last_page: 1,
          per_page: this.perPage,
          total: 0
        }
        this.mostrarMensagem({ texto: 'Erro ao buscar funcionários.', tipo: 'error' })
      }
    },
    abrirCadastro() {
      this.employeeSelecionado = null
      this.modoModal = 'criar'
      this.mostrarModal = true
    },
    abrirEdicao(employee) {
      this.employeeSelecionado = { ...employee }
      this.modoModal = 'editar'
      this.mostrarModal = true
    },
    fecharModal() {
      this.mostrarModal = false
    },
    async excluirEmployee(id) {
      if (!confirm('Deseja realmente excluir este funcionário?')) return
      const baseURL = import.meta.env.VITE_API_URL
      try {
        await axios.delete(`${baseURL}/empregados/${id}`)
        this.fetchEmployees()
        this.mostrarMensagem({ texto: 'Funcionário excluído com sucesso!', tipo: 'success' })
      } catch (error) {
        console.error('Erro ao excluir funcionário:', error)
        this.mostrarMensagem({ texto: 'Erro ao excluir funcionário.', tipo: 'error' })
      }
    },
    changePage(page) {
      if (page > 0 && page <= this.employees.last_page) {
        this.employees.current_page = page
        this.fetchEmployees()
      }
    },
    mostrarMensagem({ texto, tipo = 'success', duracao = 3000 }) {
      if (this.$refs.flash && this.$refs.flash.showMessage) {
        this.$refs.flash.showMessage(texto, tipo, duracao)
      }
    },
    formatCPF(cpf) {
      if (!cpf) return ''
      return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
    },
    formatPhone(phone) {
      if (!phone) return ''
      // Formatação para (XX) XXXXX-XXXX
      return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3')
    },
    getStatusText(status) {
      const statusMap = {
        active: 'Ativo',
        on_leave: 'Afastado',
        terminated: 'Desligado'
      }
      return statusMap[status] || status
    },
    getStatusBadge(status) {
      const badgeMap = {
        active: 'success',
        on_leave: 'warning',
        terminated: 'danger'
      }
      return badgeMap[status] || 'secondary'
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