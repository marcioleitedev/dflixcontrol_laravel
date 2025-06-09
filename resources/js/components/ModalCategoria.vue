<template>
  <div v-if="mostrar" class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form @submit.prevent="salvar">
          <div class="modal-header">
            <h5 class="modal-title">
              {{ modoModal === 'criar' ? 'Cadastrar Categoria' : 'Editar Categoria' }} {{ signature }}
            </h5>
            <button type="button" class="btn-close" @click="$emit('fechar')" aria-label="Fechar"></button>
          </div>
          <div class="modal-body">
            <!-- Empresa -->
            <div class="mb-3">
              <label for="id_company" class="form-label">Empresa</label>
              <select
                id="id_company"
                class="form-select"
                v-model="form.id_company"
                required
              >
                <option disabled value="">Selecione uma empresa</option>
                <option
                  v-for="empresa in empresas"
                  :key="empresa.id"
                  :value="empresa.id"
                >
                  {{ empresa.name }}
                </option>
              </select>
            </div>

            <!-- Categoria Pai -->
            <div class="mb-3">
              <label for="parent_id" class="form-label">Categoria Pai (ID)</label>
              <select
  id="parent_id"
  class="form-select"
  v-model="form.parent_id"
>
  <option disabled value="">Selecione uma categoria pai (opcional)</option>
  <option
    v-for="categoria in categorias"
    :key="categoria.id"
    :value="categoria.id"
  >
    {{ categoria.name }}
  </option>
</select>

            </div>

            <!-- Nome da Categoria -->
            <div class="mb-3">
              <label for="name" class="form-label">Nome da Categoria</label>
              <input
                type="text"
                id="name"
                class="form-control"
                v-model="form.name"
                required
              />
            </div>

            <!-- Descrição -->
            <div class="mb-3">
              <label for="description" class="form-label">Descrição</label>
              <textarea
                id="description"
                class="form-control"
                v-model="form.description"
              ></textarea>
            </div>
          </div>

          <!-- Footer -->
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

export default {
  name: 'ModalCategoria',
  props: {
    mostrar: Boolean,
    modoModal: String,
    estoque: Object,
    signature: String
  },
  data() {
    return {
      form: {
        name: '',
        description: '',
        parent_id: '',
        id_signature: this.signature,
        id_company: ''
      },
      empresas: [],
      categorias: []
    }
  },
  mounted() {
    this.carregarEmpresas()
    this.carregarCategorias()
  },
  watch: {
    estoque: {
      immediate: true,
      handler(novo) {
        if (this.modoModal === 'editar' && novo) {
          this.form = {
            name: novo.name || '',
            description: novo.description || '',
            parent_id: novo.parent_id || '',
            id_signature: this.signature,
            id_company: novo.id_company || ''
          }
        } else {
          this.form = {
            name: '',
            description: '',
            parent_id: '',
            id_signature: this.signature,
            id_company: ''
          }
        }
      }
    }
  },
  methods: {
    async carregarEmpresas() {
      try {
        const baseURL = import.meta.env.VITE_API_URL
        const responser = await axios.get(`${baseURL}/company/${this.signature}`)
        this.empresas = Array.isArray(responser.data) ? responser.data : [responser.data]
        console.log('Empresas carregadas:',  this.empresas)
      } catch (error) {
        console.error('Erro ao carregar empresas:', error)
      }
    },
  async carregarCategorias() {
  try {
    const baseURL = import.meta.env.VITE_API_URL
    const response = await axios.get(`${baseURL}/category/${this.signature}`)
    this.categorias = Array.isArray(response.data) ? response.data : [response.data]
  } catch (error) {
    console.error('Erro ao carregar categorias pais:', error)
  }
},
    async salvar() {
      const baseURL = import.meta.env.VITE_API_URL
      const endpoint = this.modoModal === 'criar'
        ? `${baseURL}/category/`
        : `${baseURL}/category/${this.estoque.id}`

      const metodo = this.modoModal === 'criar' ? 'post' : 'put'

      try {
        await axios[metodo](endpoint, this.form)
        this.$emit('salvou')
        this.$emit('mensagem', {
    texto: this.modoModal === 'criar' ? 'Categoria criada com sucesso!' : 'Categoria atualizada com sucesso!',
    tipo: 'success'
  })
        this.$emit('fechar')
      } catch (error) {
        console.error('Erro ao salvar:', error)
        this.$emit('mensagem', {
    texto: 'Erro ao salvar a categoria.',
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
</style>
