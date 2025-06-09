<template>
  <div class="modal fade show d-block" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ modo === 'criar' ? 'Cadastrar Role' : 'Editar Role' }}
          </h5>
          <button type="button" class="btn-close" @click="$emit('fechar')"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="salvar">
            <div class="mb-3">
              <label for="level" class="form-label">Nível</label>
              <select v-model="form.level" id="level" class="form-select" required>
                <option disabled value="">Selecione...</option>
                <option value="1">Usuário</option>
                <option value="2">Afiliado</option>
                <option value="3">Master</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="bottom_text" class="form-label">Texto Inferior</label>
              <input v-model="form.bottom_text" type="text" class="form-control" id="bottom_text" required />
            </div>

            <div class="mb-3">
              <label for="link_url" class="form-label">URL</label>
              <input v-model="form.link_url" type="text" class="form-control" id="link_url" required />
            </div>

            <div class="mb-3">
              <label for="icon" class="form-label">Ícone</label>
              <input v-model="form.icon" type="text" class="form-control" id="icon" required />
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="$emit('fechar')">Cancelar</button>
              <button type="submit" class="btn btn-primary">
                {{ modo === 'criar' ? 'Salvar' : 'Atualizar' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ModalRole',
  props: {
    modo: String,
    role: Object,
  },
  data() {
    return {
      form: {
        level: '',
        bottom_text: '',
        link_url: '',
        icon: '',
      },
    }
  },
  mounted() {
    if (this.modo === 'editar' && this.role) {
      this.form = { ...this.role }
    }
  },
  methods: {
    async salvar() {
      try {
        const baseURL = import.meta.env.VITE_API_URL
        if (this.modo === 'criar') {    
          await axios.post(`${baseURL}/roles`, this.form)
          this.$emit('atualizar', 'criado')
        } else {
          await axios.put(`${baseURL}/roles/${this.role.id}`, this.form)
          this.$emit('atualizar', 'editado')
        }
      } catch (error) {
        console.error('Erro ao salvar role:', error)
        alert('Erro ao salvar role.')
      }
    },
  },
}
</script>

<style scoped>
.modal {
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
