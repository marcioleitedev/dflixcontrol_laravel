<template>
    <div class="d-flex" id="wrapper">
      <MenuDashboard @update-signature="setSignature" />
      <div class="container-fluid mt-4">
        <FlashMessage ref="flash" />
  
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h1><i class="bi bi-box"></i> Estoque {{ signature }} </h1>
          <div>
            <a href="/dashboard/categorias">
            <button class="btn btn-primary me-2">
             <i class="bi-folder"></i> Cadastrar Categorias
            </button>
        </a> 
            <button class="btn btn-primary" @click="mostrarProduto = true">
              <i class="bi-tag"></i> Cadastrar Produtos
            </button>
          </div>
        </div>

        <div v-if="produtos.length">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Categoria</th>
        <th>Preço</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="produto in produtos" :key="produto.id">
        <td>{{ produto.name }}</td>
        <td>{{ produto.category.name }}</td>
        <td>R$ {{ produto.sale_price }}</td>
        <td>
          <button class="btn btn-sm btn-warning me-2" @click="editarProduto(produto)">
            <i class="bi bi-pencil"></i> Editar
          </button>
          <button class="btn btn-sm btn-danger" @click="removerProduto(produto.id)">
            <i class="bi bi-trash"></i> Remover
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<div v-else>
  <p>Nenhum produto cadastrado.</p>
</div>

      </div>

<ModalProduto 
  :mostrar="mostrarProduto"
  :categorias="categorias"
  :signature="signature"
  @close="mostrarProduto = false"
  @salvo="handleProdutoSalvo"
/>
    </div>

  </template>
  
  <script>
  import MenuDashboard from '@/components/MenuDashboard.vue'
  import FlashMessage from '@/components/FlashMessage.vue'
  import ModalProduto from '@/components/ModalProduto.vue'

  import axios from 'axios'
  
  export default {
    components: {
      MenuDashboard,
      FlashMessage,
      ModalProduto
    },
    data() {
      return {
        signature: '',
        mostrarCategoria: false,
        mostrarProduto: false,
        produtos: [],
        categorias: []
      }
    },
    mounted() {
      const token = localStorage.getItem('token')
      if (!token) {
        this.$router.push('/login')
      } else {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      }
      this.fetchProdutos()
    },
    methods: {
      setSignature(sig) {
        this.signature = sig
      },
      handleProdutoSalvo() {
    this.fetchProdutos()
    this.mostrarMensagem({ texto: 'Produto salvo com sucesso!' })
  },
      mostrarMensagem({ texto, tipo = 'success', duracao = 3000 }) {
        if (this.$refs.flash && this.$refs.flash.showMessage) {
          this.$refs.flash.showMessage(texto, tipo, duracao)
        }
      },
      async fetchProdutos() {
        const baseURL = import.meta.env.VITE_API_URL
        try {
          const res = await axios.get(`${baseURL}/products/${this.signature}`)
          this.produtos = res.data
        } catch (e) {
          console.error('Erro ao buscar categorias', e)
        }
      }
    }
  }
  </script>
  