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

      </div>
    </div>
  </template>
  
  <script>
  import MenuDashboard from '@/components/MenuDashboard.vue'
  import FlashMessage from '@/components/FlashMessage.vue'

  import axios from 'axios'
  
  export default {
    components: {
      MenuDashboard,
      FlashMessage,

    },
    data() {
      return {
        signature: '',
        mostrarCategoria: false,
        mostrarProduto: false,
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
      this.fetchCategorias()
    },
    methods: {
      setSignature(sig) {
        this.signature = sig
      },
      mostrarMensagem({ texto, tipo = 'success', duracao = 3000 }) {
        if (this.$refs.flash && this.$refs.flash.showMessage) {
          this.$refs.flash.showMessage(texto, tipo, duracao)
        }
      },
      async fetchCategorias() {
        const baseURL = import.meta.env.VITE_API_URL
        try {
          const res = await axios.get(`${baseURL}/category`)
          this.categorias = res.data
        } catch (e) {
          console.error('Erro ao buscar categorias', e)
        }
      }
    }
  }
  </script>
  