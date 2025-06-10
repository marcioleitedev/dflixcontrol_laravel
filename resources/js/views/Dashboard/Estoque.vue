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
          <button class="btn btn-primary" @click="abrirModalNovoProduto">
            <i class="bi-tag"></i> Cadastrar Produtos
          </button>
        </div>
      </div>

      <!-- Barra de busca -->
      <div class="mb-3">
        <div class="input-group">
          <input 
            type="text" 
            class="form-control" 
            placeholder="Buscar produtos..." 
            v-model="termoBusca"
            @keyup.enter="buscarProdutos"
          >
          <button class="btn btn-outline-secondary" @click="buscarProdutos">
            <i class="bi bi-search"></i> Buscar
          </button>
          <button class="btn btn-outline-danger" @click="limparBusca" v-if="termoBusca">
            <i class="bi bi-x"></i> Limpar
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
              <td>{{ produto.category?.name || 'N/A' }}</td>
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

        <!-- Paginação -->
        <nav v-if="totalPaginas > 1">
          <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: paginaAtual === 1 }">
              <button class="page-link" @click="mudarPagina(paginaAtual - 1)">Anterior</button>
            </li>
            
            <li 
              class="page-item" 
              v-for="pagina in paginasParaMostrar" 
              :key="pagina"
              :class="{ active: pagina === paginaAtual }"
            >
              <button class="page-link" @click="mudarPagina(pagina)">{{ pagina }}</button>
            </li>
            
            <li class="page-item" :class="{ disabled: paginaAtual === totalPaginas }">
              <button class="page-link" @click="mudarPagina(paginaAtual + 1)">Próxima</button>
            </li>
          </ul>
        </nav>
      </div>
      <div v-else>
        <p>Nenhum produto encontrado.</p>
      </div>
    </div>

    <!-- Modal para adicionar/editar produto -->
    <ModalProduto 
      :mostrar="mostrarModalProduto"
      :categorias="categorias"
      :produto="produtoSelecionado"
      :signature="signature"
      @close="fecharModalProduto"
      @salvo="handleProdutoSalvo"
    />
  </div>
</template>
  
<script>
import MenuDashboard from '@/components/MenuDashboard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import ModalProduto from '@/components/ModalProduto.vue'
import axios from 'axios'
import { debounce } from 'lodash'

export default {
  components: {
    MenuDashboard,
    FlashMessage,
    ModalProduto
  },
  data() {
    return {
      signature: '',
      mostrarModalProduto: false,
      produtos: [],
      categorias: [],
      produtoSelecionado: null,
      
      // Paginação e busca
      termoBusca: '',
      paginaAtual: 1,
      itensPorPagina: 10,
      totalItens: 0,
      totalPaginas: 1,
      paginasParaMostrar: []
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
  computed: {
    mostrarModalEdicao() {
      return this.mostrarModalProduto && this.produtoSelecionado !== null
    }
  },
  methods: {
    setSignature(sig) {
      this.signature = sig
      this.fetchProdutos()
    },
    abrirModalNovoProduto() {
      this.produtoSelecionado = null
      this.mostrarModalProduto = true
    },
    fecharModalProduto() {
      this.mostrarModalProduto = false
      this.produtoSelecionado = null
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
      if (!this.signature) return
      
      const baseURL = import.meta.env.VITE_API_URL
      try {
        const params = {
          page: this.paginaAtual,
          per_page: this.itensPorPagina,
          search: this.termoBusca
        }
        
        const res = await axios.get(`${baseURL}/products/${this.signature}`, { params })
        this.produtos = res.data.data || res.data
        this.totalItens = res.data.total || this.produtos.length
        this.totalPaginas = res.data.last_page || Math.ceil(this.totalItens / this.itensPorPagina)
        
        this.calcularPaginasParaMostrar()
      } catch (e) {
        console.error('Erro ao buscar produtos', e)
      }
    },
    buscarProdutos: debounce(function() {
      this.paginaAtual = 1
      this.fetchProdutos()
    }, 500),
    limparBusca() {
      this.termoBusca = ''
      this.paginaAtual = 1
      this.fetchProdutos()
    },
    mudarPagina(pagina) {
      if (pagina >= 1 && pagina <= this.totalPaginas) {
        this.paginaAtual = pagina
        this.fetchProdutos()
      }
    },
    calcularPaginasParaMostrar() {
      const paginas = []
      const inicio = Math.max(1, this.paginaAtual - 2)
      const fim = Math.min(this.totalPaginas, this.paginaAtual + 2)
      
      for (let i = inicio; i <= fim; i++) {
        paginas.push(i)
      }
      
      this.paginasParaMostrar = paginas
    },
    editarProduto(produto) {
      this.produtoSelecionado = { ...produto }
      this.mostrarModalProduto = true
    },
    async removerProduto(id) {
      if (confirm('Tem certeza que deseja remover este produto?')) {
        const baseURL = import.meta.env.VITE_API_URL
        try {
          await axios.delete(`${baseURL}/products/${id}`)
          this.mostrarMensagem({ texto: 'Produto removido com sucesso!' })
          this.fetchProdutos()
        } catch (error) {
          console.error('Erro ao remover produto', error)
          this.mostrarMensagem({ texto: 'Erro ao remover produto', tipo: 'error' })
        }
      }
    }
  }
}
</script>
  