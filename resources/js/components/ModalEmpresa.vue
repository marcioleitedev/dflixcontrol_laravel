<template>
  <div class="modal-backdrop">
    <div class="modal-container">
      <div class="modal-header">
        <h5>{{ modo === 'criar' ? 'Cadastrar Empresa' : 'Editar Empresa' }}</h5>
        <button @click="$emit('fechar')">Fechar</button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="salvarEmpresa">
          <div>
            <label>Nome</label>
            <input v-model="empresaData.name" required />
          </div>
          <div>
            <label>Email</label>
            <input v-model="empresaData.email" type="email" />
          </div>
          <!-- outros campos aqui -->
          <button type="submit">Salvar</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    empresa: Object,
    modo: String,
    signature: [String, Number],
  },
  data() {
    return {
      empresaData: this.empresa
        ? { ...this.empresa }
        : {
            name: '',
            email: '',
            city: '',
            state: '',
            signature_id: this.signature,
          },
    }
  },
  watch: {
    empresa(novaEmpresa) {
      this.empresaData = novaEmpresa
        ? { ...novaEmpresa }
        : {
            name: '',
            email: '',
            city: '',
            state: '',
            signature_id: this.signature,
          }
    },
  },
  methods: {
    async salvarEmpresa() {
      // aqui viria a chamada POST/PUT
      this.$emit('atualizar')
      this.$emit('fechar')
    },
  },
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
}
.modal-container {
  position: fixed;
  top: 20%;
  left: 50%;
  transform: translateX(-50%);
  background: white;
  padding: 2rem;
  border-radius: 8px;
  min-width: 400px;
}
</style>
