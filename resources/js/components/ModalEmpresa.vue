<template>
  <div
    class="modal fade show"
    tabindex="-1"
    style="display: block; background-color: rgba(0,0,0,0.5);"
    aria-modal="true"
    role="dialog"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ modo === 'criar' ? 'Cadastrar Empresa' : 'Editar Empresa' }}
          </h5>
          <button type="button" class="btn-close" aria-label="Close" @click="$emit('fechar')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="salvarEmpresa">
            <div class="mb-3">
              <label class="form-label">Nome da Empresa</label>
              <input v-model="empresaData.name" type="text" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">CNPJ</label>
              <input v-model="empresaData.cnpj" type="text" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input v-model="empresaData.email" type="email" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Celular</label>
              <input v-model="empresaData.phone" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">CEP</label>
              <input
                v-model="empresaData.cep"
                type="text"
                class="form-control"
                @blur="buscarCep"
              />
            </div>
            <div class="mb-3">
              <label class="form-label">Endereço</label>
              <input v-model="empresaData.address" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Numero</label>
              <input v-model="empresaData.number" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Complemento</label>
              <input v-model="empresaData.complement" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Bairro</label>
              <input v-model="empresaData.district" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Cidade</label>
              <input v-model="empresaData.city" type="text" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label">Estado</label>
              <input v-model="empresaData.state" type="text" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    empresa: Object,
    modo: String,
    signature: [String, Number],
  },
  data() {
    return {
      empresaData: this.empresa
        ? { ...this.empresa }
        : {
            name: '',
            cnpj: '',
            email: '',
            phone: '',
            cep: '',
            address: '',
            number: '',
            complement: '',
            district: '',
            city: '',
            state: '',
            signature_id: this.signature,
          },
    }
  },
  watch: {
    empresa(novaEmpresa) {
      this.empresaData = novaEmpresa
        ? { ...novaEmpresa }
        : {
            name: '',
            cnpj: '',
            email: '',
            phone: '',
            cep: '',
            address: '',
            number: '',
            complement: '',
            district: '',
            city: '',
            state: '',
            signature_id: this.signature,
          }
    },
  },
  methods: {
    async buscarCep() {
      const cepValue = this.empresaData.cep.replace(/\D/g, '')
      if (cepValue.length === 8) {
        try {
          const response = await axios.get(`https://viacep.com.br/ws/${cepValue}/json/`)
          if (!response.data.erro) {
            this.empresaData.address = response.data.logradouro || ''
            this.empresaData.district = response.data.bairro || ''
            this.empresaData.city = response.data.localidade || ''
            this.empresaData.state = response.data.uf || ''
          } else {
            this.$emit('mensagem', { tipo: 'error', texto: 'CEP não encontrado.' })
          }
        } catch (error) {
          console.error('Erro ao buscar CEP:', error)
          this.$emit('mensagem', { tipo: 'error', texto: 'Erro ao buscar CEP.' })
        }
      } else {
        this.$emit('mensagem', { tipo: 'error', texto: 'CEP inválido. Digite 8 números.' })
      }
    },

async salvarEmpresa() {
  try {
    const baseURL = import.meta.env.VITE_API_URL
    const response = this.modo === 'criar'
      ? await axios.post(`${baseURL}/company`, this.empresaData)
      : await axios.put(`${baseURL}/company/${this.empresaData.id}`, this.empresaData)

    this.$emit('mensagem', { tipo: 'success', texto: response.data.message })
    this.$emit('atualizar')
    this.$emit('fechar')
  } catch (error) {
    const mensagem = error.response?.data?.message || 'Erro ao salvar empresa.'
    this.$emit('mensagem', { tipo: 'error', texto: mensagem })
  }
},
  },
}
</script>

<style scoped>
.modal.fade.show {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
