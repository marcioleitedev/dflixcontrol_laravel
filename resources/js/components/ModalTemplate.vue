<template>
    <div v-if="mostrar" class="modal fade show d-block" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form @submit.prevent="salvar">
            <div class="modal-header">
              <h5 class="modal-title">
                {{ modoModal === 'criar' ? 'Cadastrar Estoque' : 'Editar Estoque' }}
              </h5>
              <button type="button" class="btn-close" @click="$emit('fechar')" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input
                  type="text"
                  id="nome"
                  class="form-control"
                  v-model="form.nome"
                  required
                />
              </div>
              <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea
                  id="descricao"
                  class="form-control"
                  v-model="form.descricao"
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
  
  export default {
    name: 'ModalTemplate',
    props: {
      mostrar: Boolean,
      modoModal: String,
      estoque: Object,
      signature: String
    },
    data() {
      return {
        form: {
          nome: '',
          descricao: ''
        }
      }
    },
    watch: {
      estoque: {
        immediate: true,
        handler(novo) {
          if (this.modoModal === 'editar' && novo) {
            this.form = { nome: novo.nome || '', descricao: novo.descricao || '' }
          } else {
            this.form = { nome: '', descricao: '' }
          }
        }
      }
    },
    methods: {
      async salvar() {
        const endpoint =
          this.modoModal === 'criar'
            ? `http://127.0.0.1:8000/api/company/${this.signature}`
            : `http://127.0.0.1:8000/api/company/${this.estoque.id}`
  
        const metodo = this.modoModal === 'criar' ? 'post' : 'put'
  
        try {
          await axios[metodo](endpoint, this.form)
          this.$emit('salvou')
          this.$emit('fechar')
        } catch (error) {
          console.error('Erro ao salvar:', error)
          alert('Erro ao salvar o estoque.')
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
  