<template>
    <b-modal :model-value="mostrar" @update:model-value="$emit('update:mostrar', $event)" title="Cadastrar Produto" @hide="reset">
      <div class="mb-3">
        <label>Nome</label>
        <input type="text" class="form-control" v-model="form.nome" />
      </div>
      <div class="mb-3">
        <label>Categoria</label>
        <select class="form-control" v-model="form.category_id">
          <option v-for="cat in categorias" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
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
      <template #footer>
        <button class="btn btn-secondary" @click="$emit('close')">Cancelar</button>
        <button class="btn btn-success" @click="salvar">Salvar</button>
      </template>
    </b-modal>
  </template>
  
  <script>
  import axios from 'axios'
  export default {
    name: 'ModalProduto',
    props: {
      mostrar: Boolean,
      categorias: Array
    },
    emits: ['close', 'salvo'],
    data() {
      return {
        form: {
          nome: '',
          category_id: '',
          brand: '',
          cost_price: 0,
          sale_price: 0,
          quantity: 0
        }
      }
    },
    methods: {
      reset() {
        this.form = {
          nome: '',
          category_id: '',
          brand: '',
          cost_price: 0,
          sale_price: 0,
          quantity: 0
        }
      },
      async salvar() {
        try {
          const baseURL = import.meta.env.VITE_API_URL
          await axios.post(`${baseURL}/products`, this.form)
          this.$emit('salvo')
          this.$emit('close')
        } catch (error) {
          console.error('Erro ao salvar produto', error)
        }
      }
    }
  }
  </script>
  