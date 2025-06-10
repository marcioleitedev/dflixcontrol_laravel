<template>
  <div class="modal fade" tabindex="-1" :class="{ show: mostrar }" :style="mostrar ? 'display: block;' : ''" @click.self="$emit('close')">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Cadastrar Produto {{ signature }}</h5>
          <button type="button" class="btn-close" @click="$emit('close')"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Empresa</label>
 <!-- O restante do template permanece igual -->
 <select class="form-control" v-model="form.id_company">
    <option value="">Selecione uma empresas</option>
    <option v-for="empresa in empresasDisponiveis" :key="empresa.id" :value="empresa.id">{{ empresa.name }}</option>
  </select>
          </div>
          <div class="mb-3">
            <label>Categoria</label>
 <!-- O restante do template permanece igual -->
 <select class="form-control" v-model="form.category_id">
    <option value="">Selecione uma categoria</option>
    <option v-for="cat in categoriasDisponiveis" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
  </select>
          </div>
          <div class="mb-3">
            <label>Nome</label>
            <input type="text" class="form-control" v-model="form.name" />
          </div>

          <div class="mb-3">
            <label>Marca</label>
            <input type="text" class="form-control" v-model="form.brand" />
          </div>
          <div class="mb-3">
            <label>Preço de Custo</label>
            <input type="number" class="form-control" v-model="form.cost_price" />
          </div>
          <div class="mb-3">
            <label>Preço de Venda</label>
            <input type="number" class="form-control" v-model="form.sale_price" />
          </div>
          <div class="mb-3">
            <label>Quantidade</label>
            <input type="number" class="form-control" v-model="form.quantity" />
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
          <button class="btn btn-success" @click="salvar">Salvar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ModalProduto',
  props: {
    mostrar: Boolean,
    categorias: {
      type: Array,
      default: () => []
    },
    empresas: {
      type: Array,
      default: () => []
    },
  signature: {
    type: String,
    default: ''
  }
  },
  emits: ['close', 'salvo'],
  data() {
    return {
      form: {
        name: '',
        category_id: '',
        id_company: '',
        brand: '',
        cost_price: 0,
        sale_price: 0,
        quantity: 0,
        id_signature: this.signature
      },
      categoriasCarregadas: [],
      empresasCarregadas: []

    }
  },
  computed: {
    categoriasDisponiveis() {
      // Combina categorias recebidas como prop com as carregadas localmente
      return [...this.categorias, ...this.categoriasCarregadas]
    },
    empresasDisponiveis() {
      // Combina categorias recebidas como prop com as carregadas localmente
      return [...this.empresas, ...this.empresasCarregadas]
    }
  },
  watch: {
    mostrar(val) {
      if (val) {
        this.carregarCategorias()
        this.carregarEmpresas()

      } else {
        this.reset()
      }
    },
    signature(newVal) {
    this.form.id_signature = newVal;
  },
  },
  methods: {
    reset() {
      this.form = {
        name: '',
        category_id: '',
        id_company: '',
        brand: '',
        cost_price: 0,
        sale_price: 0,
        quantity: 0,
        id_signature: this.signature
      }
    },
    async salvar() {
      const baseURL = import.meta.env.VITE_API_URL
      try {
        
        await axios.post(`${baseURL}/products`, this.form)
        this.$emit('salvo')
        this.$emit('close')
      } catch (error) {
        console.error('Erro ao salvar produto', error)
      }
    },
    async carregarCategorias() {
  if (!this.signature) return;
  try {
    const baseURL = import.meta.env.VITE_API_URL
    const response = await axios.get(`${baseURL}/category/${this.signature}`)
    this.categoriasCarregadas = Array.isArray(response.data) ? response.data : [response.data]
  } catch (error) {
    console.error('Erro ao carregar categorias:', error)
  }
},
async carregarEmpresas() {
  if (!this.signature) return;
  try {
    const baseURL = import.meta.env.VITE_API_URL
    const response = await axios.get(`${baseURL}/company/${this.signature}`)
    this.empresasCarregadas = Array.isArray(response.data) ? response.data : [response.data]
  } catch (error) {
    console.error('Erro ao carregar empresas:', error)
  }
},
  }
}
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
