<template>
  <teleport to="body">
    <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5)">
      <div class="modal-dialog">
        <div class="modal-content">
          <form @submit.prevent="salvar">
            <div class="modal-header">
              <h5 class="modal-title">
                {{ modo === 'criar' ? 'Cadastrar Afiliado' : 'Editar Afiliado' }}
              </h5>
              <button type="button" class="btn-close" @click="$emit('fechar')"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label>Nome</label>
                <input type="text" class="form-control" v-model="form.name" required />
              </div>
              <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control" v-model="form.email" required />
              </div>
              <input type="hidden" v-model="form.level" />
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="$emit('fechar')">Cancelar</button>
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </teleport>
</template>

<script>
import axios from 'axios'

export default {
  props: {
    modo: String,
    afiliado: Object,
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        level: 2,
      },
    }
  },
  watch: {
    afiliado: {
      immediate: true,
      handler(novo) {
        if (novo) {
          this.form = {
            name: novo.name || '',
            email: novo.email || '',
            level: 2,
          }
        }
      },
    },
  },
  methods: {
    async salvar() {
      try {
       
        if (this.modo === 'criar') {
          const baseURL = import.meta.env.VITE_API_URL
          await axios.post(`${baseURL}/affiliate`, this.form)
        } else if (this.afiliado?.id) {
          const baseURL = import.meta.env.VITE_API_URL
          await axios.put(`${baseURL}/affiliate/${this.afiliado.id}`, this.form)
        }
        this.$emit('fechar')
        this.$emit('atualizar')
      } catch (error) {
        console.error('Erro ao salvar afiliado:', error)
      }
    },
  },
}
</script>
