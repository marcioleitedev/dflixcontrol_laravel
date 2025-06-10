<template>
  <div v-if="mostrar" class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form @submit.prevent="salvar">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ isModoCriar ? 'Cadastrar Fornecedor' : 'Editar Fornecedor' }} 
              <span v-if="signature" class="text-muted">({{ signature }})</span>
            </h5>
            <button type="button" class="btn-close" @click="fecharModal" aria-label="Fechar"></button>
          </div>
          
          <div class="modal-body">
            <FornecedoresFormFields :form="form" />
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="fecharModal">Cancelar</button>
            <button type="submit" class="btn btn-primary">
              {{ isModoCriar ? 'Salvar' : 'Atualizar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import FornecedoresFormFields from './FornecedoresFormFields.vue'

export default {
  name: 'ModalFornecedores',
  components: {
    FornecedoresFormFields
  },
  props: {
    mostrar: {
      type: Boolean,
      required: true
    },
    modoModal: {
      type: String,
      required: true,
      validator: value => ['criar', 'editar'].includes(value)
    },
    fornecedor: {
      type: Object,
      default: () => ({})
    },
    signature: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      form: this.getFormPadrao()
    }
  },
  computed: {
    isModoCriar() {
      return this.modoModal === 'criar'
    }
  },
  watch: {
    fornecedor: {
      immediate: true,
      deep: true,
      handler(novoFornecedor) {
        if (!this.isModoCriar && novoFornecedor && Object.keys(novoFornecedor).length) {
          this.preencherFormulario(novoFornecedor)
        } else {
          this.resetarFormulario()
        }
      }
    }
  },
  methods: {
    getFormPadrao() {
      return {
        name: '',
        cnpj_cpf: '',
        ie: '',
        contact_name: '',
        email: '',
        phone: '',
        postal_code: '',
        address: '',
        city: '',
        state: '',
        id_signature: this.signature
      }
    },
    preencherFormulario(dados) {
      this.form = {
        ...this.getFormPadrao(),
        ...dados,
        id_signature: this.signature
      }
    },
    resetarFormulario() {
      this.form = this.getFormPadrao()
    },
    async salvar() {
  try {
    const baseURL = import.meta.env.VITE_API_URL
    const endpoint = this.isModoCriar 
      ? `${baseURL}/fornecedores` 
      : `${baseURL}/fornecedores/${this.fornecedor.id}`
    
    const metodo = this.isModoCriar ? 'post' : 'put'
    
    await axios[metodo](endpoint, this.form)
    
    this.$emit('salvou-com-sucesso', {
      texto: this.isModoCriar ? 'Fornecedor cadastrado com sucesso!' : 'Fornecedor atualizado com sucesso!',
      tipo: 'success'
    })
    this.fecharModal()
  } catch (error) {
    console.error('Erro ao salvar fornecedor:', error)
    this.$emit('erro-ao-salvar', {
      texto: `Erro ao ${this.isModoCriar ? 'salvar' : 'atualizar'} o fornecedor.`,
      tipo: 'error'
    })
  }
},
    fecharModal() {
      this.$emit('fechar')
    }
  }
}
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>