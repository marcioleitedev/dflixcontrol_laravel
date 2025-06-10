<template>
  <div v-if="mostrar" class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form @submit.prevent="salvar">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ modoModal === 'criar' ? 'Cadastrar Funcionário' : 'Editar Funcionário' }}
            </h5>
            <button type="button" class="btn-close" @click="$emit('fechar')" aria-label="Fechar"></button>
          </div>
          
          <div class="modal-body">
            <div class="row">
              <!-- Coluna 1: Informações Pessoais -->
              <div class="col-md-6">
                <h6 class="mb-3 text-primary">Informações Pessoais</h6>
                
                <div class="mb-3">
                  <label for="name" class="form-label">Nome Completo*</label>
                  <input type="text" id="name" class="form-control" v-model="form.name" required>
                </div>
                
                <div class="mb-3">
                  <label for="cpf" class="form-label">CPF*</label>
                  <input type="text" id="cpf" class="form-control" v-model="form.cpf" v-mask="'###.###.###-##'" required>
                </div>
                
                <div class="mb-3">
                  <label for="rg" class="form-label">RG</label>
                  <input type="text" id="rg" class="form-control" v-model="form.rg">
                </div>
                
                <div class="mb-3">
                  <label for="birth_date" class="form-label">Data de Nascimento</label>
                  <input type="date" id="birth_date" class="form-control" v-model="form.birth_date">
                </div>
                
                <div class="mb-3">
                  <label class="form-label">Gênero</label>
                  <select class="form-select" v-model="form.gender">
                    <option value="">Selecione</option>
                    <option value="male">Masculino</option>
                    <option value="female">Feminino</option>
                    <option value="other">Outro</option>
                  </select>
                </div>
              </div>
              
              <!-- Coluna 2: Contato e Endereço -->
              <div class="col-md-6">
                <h6 class="mb-3 text-primary">Contato</h6>
                
                <div class="mb-3">
                  <label for="email" class="form-label">E-mail*</label>
                  <input type="email" id="email" class="form-control" v-model="form.email" required>
                </div>
                
                <div class="mb-3">
                  <label for="phone" class="form-label">Telefone*</label>
                  <input type="text" id="phone" class="form-control" v-model="form.phone" v-mask="'(##) #####-####'" required>
                </div>
                
                <div class="mb-3">
                  <label for="emergency_phone" class="form-label">Telefone de Emergência</label>
                  <input type="text" id="emergency_phone" class="form-control" v-model="form.emergency_phone" v-mask="'(##) #####-####'">
                </div>
                
                <h6 class="mb-3 mt-4 text-primary">Endereço</h6>
                
                <div class="row mb-3">
                  <div class="col-4">
                    <label for="postal_code" class="form-label">CEP</label>
                    <input type="text" id="postal_code" class="form-control" v-model="form.postal_code" v-mask="'#####-###'" @blur="buscarCep">
                  </div>
                  <div class="col-8">
                    <label for="address" class="form-label">Logradouro</label>
                    <input type="text" id="address" class="form-control" v-model="form.address">
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-3">
                    <label for="address_number" class="form-label">Número</label>
                    <input type="text" id="address_number" class="form-control" v-model="form.address_number">
                  </div>
                  <div class="col-9">
                    <label for="complement" class="form-label">Complemento</label>
                    <input type="text" id="complement" class="form-control" v-model="form.complement">
                  </div>
                </div>
                
                <div class="row mb-3">
                  <div class="col-6">
                    <label for="neighborhood" class="form-label">Bairro</label>
                    <input type="text" id="neighborhood" class="form-control" v-model="form.neighborhood">
                  </div>
                  <div class="col-4">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" id="city" class="form-control" v-model="form.city">
                  </div>
                  <div class="col-2">
                    <label for="state" class="form-label">UF</label>
                    <input type="text" id="state" class="form-control" v-model="form.state" maxlength="2">
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Informações Profissionais -->
            <div class="row mt-4">
              <div class="col-12">
                <h6 class="mb-3 text-primary">Informações Profissionais</h6>
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="position" class="form-label">Cargo*</label>
                <input type="text" id="position" class="form-control" v-model="form.position" required>
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="department" class="form-label">Departamento*</label>
                <input type="text" id="department" class="form-control" v-model="form.department" required>
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="salary" class="form-label">Salário (R$)</label>
                <input type="text" id="salary" class="form-control" v-model="form.salary" v-money="moneyFormat">
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="hire_date" class="form-label">Data de Contratação*</label>
                <input type="date" id="hire_date" class="form-control" v-model="form.hire_date" required>
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="status" class="form-label">Status*</label>
                <select id="status" class="form-select" v-model="form.status" required>
                  <option value="active">Ativo</option>
                  <option value="on_leave">Afastado</option>
                  <option value="terminated">Desligado</option>
                </select>
              </div>
              
              <div class="col-md-4 mb-3" v-if="form.status === 'terminated'">
                <label for="termination_date" class="form-label">Data de Desligamento</label>
                <input type="date" id="termination_date" class="form-control" v-model="form.termination_date">
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="pis" class="form-label">PIS</label>
                <input type="text" id="pis" class="form-control" v-model="form.pis" v-mask="'###.#####.##-#'">
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="ctps_number" class="form-label">Número da CTPS</label>
                <input type="text" id="ctps_number" class="form-control" v-model="form.ctps_number">
              </div>
              
              <div class="col-md-4 mb-3">
                <label for="ctps_series" class="form-label">Série da CTPS</label>
                <input type="text" id="ctps_series" class="form-control" v-model="form.ctps_series">
              </div>
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('fechar')">Cancelar</button>
            <button type="submit" class="btn btn-primary">
              {{ modoModal === 'criar' ? 'Cadastrar' : 'Atualizar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ModalEmployees',
  props: {
    mostrar: Boolean,
    modoModal: String,
    employee: Object,
    signature: String
  },
  data() {
    return {
      form: this.getFormPadrao(),
      moneyFormat: {
        decimal: ',',
        thousands: '.',
        prefix: 'R$ ',
        precision: 2,
        masked: false
      }
    }
  },
  watch: {
    employee: {
      immediate: true,
      deep: true,
      handler(novoEmployee) {
        if (this.modoModal === 'editar' && novoEmployee && Object.keys(novoEmployee).length) {
          this.preencherFormulario(novoEmployee)
        } else {
          this.resetarFormulario()
        }
      }
    }
  },
  methods: {
    getFormPadrao() {
      return {
        // Informações pessoais
        name: '',
        cpf: '',
        rg: '',
        birth_date: '',
        gender: '',
        
        // Dados de contato
        email: '',
        phone: '',
        emergency_phone: '',
        
        // Endereço
        postal_code: '',
        address: '',
        address_number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
        
        // Informações profissionais
        position: '',
        department: '',
        salary: '',
        hire_date: '',
        termination_date: '',
        status: 'active',
        
        // Documentos
        pis: '',
        ctps_number: '',
        ctps_series: '',
        
        // Relacionamento
        signature_id: this.signature
      }
    },
    preencherFormulario(dados) {
      this.form = {
        ...this.getFormPadrao(),
        ...dados,
        signature_id: this.signature
      }
    },
    resetarFormulario() {
      this.form = this.getFormPadrao()
    },
    async buscarCep() {
      if (this.form.postal_code && this.form.postal_code.length === 9) {
        try {
          const cep = this.form.postal_code.replace('-', '')
          const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`)
          
          if (response.data && !response.data.erro) {
            this.form.address = response.data.logradouro || ''
            this.form.neighborhood = response.data.bairro || ''
            this.form.city = response.data.localidade || ''
            this.form.state = response.data.uf || ''
          }
        } catch (error) {
          console.error('Erro ao buscar CEP:', error)
        }
      }
    },
    async salvar() {
      try {
        const baseURL = import.meta.env.VITE_API_URL
        const endpoint = this.modoModal === 'criar' 
          ? `${baseURL}/empregados` 
          : `${baseURL}/empregados/${this.employee.id}`
        
        const metodo = this.modoModal === 'criar' ? 'post' : 'put'
        
        // Formatar dados antes de enviar
        const dadosParaEnviar = {
          ...this.form,
          cpf: this.form.cpf.replace(/\D/g, ''),
          phone: this.form.phone.replace(/\D/g, ''),
          emergency_phone: this.form.emergency_phone?.replace(/\D/g, '') || null,
          postal_code: this.form.postal_code.replace(/\D/g, ''),
          salary: this.form.salary ? parseFloat(this.form.salary.replace(/\D/g, '')) / 100 : null,
          signature_id: this.signature
        }
        
        await axios[metodo](endpoint, dadosParaEnviar)
        
        this.$emit('salvou-com-sucesso', {
          texto: this.modoModal === 'criar' ? 'Funcionário cadastrado com sucesso!' : 'Funcionário atualizado com sucesso!',
          tipo: 'success'
        })
        this.$emit('fechar')
      } catch (error) {
        console.error('Erro ao salvar funcionário:', error)
        this.$emit('erro-ao-salvar', {
          texto: `Erro ao ${this.modoModal === 'criar' ? 'cadastrar' : 'atualizar'} o funcionário.`,
          tipo: 'error',
          error: error.response?.data
        })
      }
    }
  }
}
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>