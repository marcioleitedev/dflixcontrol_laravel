<template>
  <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0, 0, 0, 0.5)">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ modo === 'criar' ? 'Cadastrar Perfil de Afiliado' : 'Editar Perfil de Afiliado' }}
          </h5>
          <button type="button" class="btn-close" @click="$emit('fechar')"></button>
        </div>

        <form @submit.prevent="modo === 'criar' ? criarPerfil() : atualizarPerfil()">
          <div class="modal-body row">
            <div class="col-md-6 mb-3" v-for="(label, campo) in campos" :key="campo">
              <label :for="campo" class="form-label">{{ label }}</label>
              <input
                :type="tipos[campo] || 'text'"
                class="form-control"
                :id="campo"
                v-model="perfil[campo]"
                :required="requeridos.includes(campo)"
                :disabled="campo === 'user_id'"
              />
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="$emit('fechar')">Cancelar</button>
            <button type="submit" class="btn btn-primary">
              {{ modo === 'criar' ? 'Cadastrar' : 'Atualizar' }}
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
  name: 'ModalPerfilAfiliado',
  props: {
    modo: String,
    perfilAfiliado: Object,
    userId: Number,
  },
  data() {
    return {
      perfil: {
        user_id: '',
        cpf: '',
        telefone: '',
        data_nascimento: '',
        sexo: '',
        endereco: '',
        cidade: '',
        estado: '',
        cep: '',
        link_afiliado: '',
        foto_perfil: '',
        descricao: '',
        banco: '',
        agencia: '',
        conta: '',
        tipo_conta: '',
        pix: '',
        comissao_percentual: '',
        status: '',
      },
      campos: {
        user_id: 'Usuário ID',
        cpf: 'CPF',
        telefone: 'Telefone',
        data_nascimento: 'Data de Nascimento',
        sexo: 'Sexo',
        endereco: 'Endereço',
        cidade: 'Cidade',
        estado: 'Estado',
        cep: 'CEP',
        link_afiliado: 'Link do Afiliado',
        foto_perfil: 'URL da Foto de Perfil',
        descricao: 'Descrição',
        banco: 'Banco',
        agencia: 'Agência',
        conta: 'Conta',
        tipo_conta: 'Tipo de Conta',
        pix: 'Chave PIX',
        comissao_percentual: 'Comissão (%)',
        status: 'Status',
      },
      tipos: {
        user_id: 'number',
        data_nascimento: 'date',
        comissao_percentual: 'number',
        status: 'number',
      },
      requeridos: ['user_id', 'cpf', 'telefone', 'data_nascimento', 'sexo'],
    }
  },
  watch: {
    perfilAfiliado: {
      immediate: true,
      handler(novo) {
        if (this.modo === 'editar' && novo && Object.keys(novo).length > 0) {
          this.perfil = { ...novo }
        } else {
          this.perfil = {
            ...this.perfil,
            user_id: this.userId || '',
          }
        }
      }
    }
  },
  methods: {
    async criarPerfil() {
      try {
        const token = localStorage.getItem('token')
        await axios.post('http://127.0.0.1:8000/api/profile', this.perfil, {
          headers: { Authorization: `Bearer ${token}` }
        })
        this.$emit('atualizar')
      } catch (error) {
        console.error('Erro ao criar perfil:', error)
        alert('Erro ao criar perfil.')
      }
    },
    async atualizarPerfil() {
      try {
        const token = localStorage.getItem('token')
        await axios.put(`http://127.0.0.1:8000/api/profile/${this.perfil.id}`, this.perfil, {
          headers: { Authorization: `Bearer ${token}` }
        })
        this.$emit('atualizar')
      } catch (error) {
        console.error('Erro ao atualizar perfil:', error)
        alert('Erro ao atualizar perfil.')
      }
    }
  }
}
</script>

<style scoped>
.modal {
  overflow-y: auto;
}
</style>
