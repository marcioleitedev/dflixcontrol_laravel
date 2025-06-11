<template>
  <div v-if="mostrar" class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <form @submit.prevent="salvar">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ modoModal === 'criar' ? 'Cadastrar Cliente' : 'Editar Cliente' }} {{ signature }}
            </h5>
            <button type="button" class="btn-close" @click="$emit('fechar')" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Primeira Coluna -->
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="name" class="form-label">Nome Completo*</label>
                  <input
                    type="text"
                    id="name"
                    class="form-control"
                    v-model="form.name"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    type="email"
                    id="email"
                    class="form-control"
                    v-model="form.email"
                  />
                </div>

                <div class="mb-3">
                  <label for="phone" class="form-label">Telefone*</label>
                  <input
                    type="tel"
                    id="phone"
                    class="form-control"
                    v-model="form.phone"
                    required
                    v-mask="['(##) ####-####', '(##) #####-####']"
                  />
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="document_type" class="form-label">Tipo Documento</label>
                    <select id="document_type" class="form-select" v-model="form.document_type">
                      <option value="CPF">CPF</option>
                      <option value="CNPJ">CNPJ</option>
                      <option value="RG">RG</option>
                      <option value="Outro">Outro</option>
                    </select>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="document" class="form-label">Documento</label>
                    <input
                      type="text"
                      id="document"
                      class="form-control"
                      v-model="form.document"
                      :v-mask="form.document_type === 'CPF' ? '###.###.###-##' : '##.###.###/####-##'"
                    />
                  </div>
                </div>

                <div class="mb-3">
                  <label for="birth_date" class="form-label">Data Nascimento</label>
                  <input
                    type="date"
                    id="birth_date"
                    class="form-control"
                    v-model="form.birth_date"
                  />
                </div>

                <div class="mb-3">
                  <label for="gender" class="form-label">Gênero</label>
                  <select id="gender" class="form-select" v-model="form.gender">
                    <option value="">Selecione</option>
                    <option value="male">Masculino</option>
                    <option value="Feminino">Feminino</option>
                    <option value="Outro">Outro</option>
                  </select>
                </div>
              </div>

              <!-- Segunda Coluna -->
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="postal_code" class="form-label">CEP</label>
                  <div class="input-group">
                    <input
                      type="text"
                      id="postal_code"
                      class="form-control"
                      v-model="form.postal_code"
                      v-mask="'#####-###'"
                      @blur="buscarCep"
                    />
                    <button class="btn btn-outline-secondary" type="button" @click="buscarCep">
                      <i class="bi bi-search"></i>
                    </button>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="address" class="form-label">Endereço</label>
                  <input
                    type="text"
                    id="address"
                    class="form-control"
                    v-model="form.address"
                  />
                </div>

                <div class="row">
                  <div class="col-md-3 mb-3">
                    <label for="address_number" class="form-label">Número</label>
                    <input
                      type="text"
                      id="address_number"
                      class="form-control"
                      v-model="form.address_number"
                    />
                  </div>
                  <div class="col-md-9 mb-3">
                    <label for="complement" class="form-label">Complemento</label>
                    <input
                      type="text"
                      id="complement"
                      class="form-control"
                      v-model="form.complement"
                    />
                  </div>
                </div>

                <div class="mb-3">
                  <label for="neighborhood" class="form-label">Bairro</label>
                  <input
                    type="text"
                    id="neighborhood"
                    class="form-control"
                    v-model="form.neighborhood"
                  />
                </div>

                <div class="row">
                  <div class="col-md-8 mb-3">
                    <label for="city" class="form-label">Cidade</label>
                    <input
                      type="text"
                      id="city"
                      class="form-control"
                      v-model="form.city"
                    />
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="state" class="form-label">UF</label>
                    <select id="state" class="form-select" v-model="form.state">
                      <option value="">Selecione</option>
                      <option v-for="uf in ufs" :value="uf">{{ uf }}</option>
                    </select>
                  </div>
                </div>

                <div class="mb-3 form-check">
                  <input
                    type="checkbox"
                    id="is_active"
                    class="form-check-input"
                    v-model="form.is_active"
                  />
                  <label for="is_active" class="form-check-label">Cliente Ativo</label>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label for="notes" class="form-label">Observações</label>
              <textarea
                id="notes"
                class="form-control"
                v-model="form.notes"
                rows="3"
              ></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('fechar')">Cancelar</button>
            <button type="submit" class="btn btn-primary">
              {{ modoModal === 'criar' ? 'Salvar' : 'Atualizar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { mask } from 'vue-the-mask'

export default {
  name: 'CustomerModal',
  directives: { mask },
  props: {
    mostrar: Boolean,
    modoModal: String,
    customer: Object,
    signature: String
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        phone: '',
        document: '',
        document_type: 'CPF',
        postal_code: '',
        address: '',
        address_number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
        birth_date: '',
        gender: '',
        notes: '',
        is_active: true,
        signature_id: this.signature
      },
      ufs: [
        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 
        'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 
        'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
      ]
    }
  },
  watch: {
    customer: {
      immediate: true,
      handler(novo) {
        if (this.modoModal === 'editar' && novo) {
          // Formatar dados para exibição
          this.form = { 
            ...novo,
            birth_date: novo.birth_date ? this.formatDate(novo.birth_date) : '',
            phone: novo.phone || '',
            is_active: novo.is_active ?? true
          }
        } else {
          this.resetForm()
        }
      }
    }
  },
  methods: {
    resetForm() {
      this.form = {
        name: '',
        email: '',
        phone: '',
        document: '',
        document_type: 'CPF',
        postal_code: '',
        address: '',
        address_number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
        birth_date: '',
        gender: '',
        notes: '',
        is_active: true,
        signature_id: this.signature
      }
    },
    formatDate(date) {
      if (!date) return ''
      const d = new Date(date)
      return d.toISOString().split('T')[0]
    },
    async buscarCep() {
      if (!this.form.postal_code || this.form.postal_code.length < 8) return
      
      const cep = this.form.postal_code.replace(/\D/g, '')
      try {
        const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`)
        if (!response.data.erro) {
          this.form.address = response.data.logradouro || ''
          this.form.neighborhood = response.data.bairro || ''
          this.form.city = response.data.localidade || ''
          this.form.state = response.data.uf || ''
          this.form.complement = response.data.complemento || ''
        }
      } catch (error) {
        console.error('Erro ao buscar CEP:', error)
      }
    },
    async salvar() {
      const baseURL = import.meta.env.VITE_API_URL
      const endpoint = this.modoModal === 'criar'
        ? `${baseURL}/clientes`
        : `${baseURL}/clientes/${this.customer.id}`

      const metodo = this.modoModal === 'criar' ? 'post' : 'put'

      try {
        // Formatando dados antes de enviar
        const payload = {
          ...this.form,
          phone: this.form.phone.replace(/\D/g, ''),
          signature_id: this.signature,
          document: this.form.document.replace(/\D/g, ''),
          postal_code: this.form.postal_code.replace(/\D/g, '')
        }

        await axios[metodo](endpoint, payload)
        this.$emit('salvou')
         this.$emit('mensagem', {
          texto: this.modoModal === 'criar' ? 'Cliente criado com sucesso!' : 'Cliente Atualizado com sucesso!',
          tipo: 'success'
        })
        this.$emit('fechar')
      } catch (error) {
        console.error('Erro ao salvar cliente:', error)
         this.$emit('mensagem', {
          texto: 'Erro ao salvar o cliente. Verifique os dados e tente novamente.',
          tipo: 'error'
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
.form-check-label {
  cursor: pointer;
}
</style>