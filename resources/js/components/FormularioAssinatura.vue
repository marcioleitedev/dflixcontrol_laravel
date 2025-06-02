<template>
  <form @submit.prevent="assinar" class="form-grid">
    <FlashMessage ref="flashRef" />
    
    <div class="form-column">
      <input type="text" v-model="form.name" placeholder="Nome completo" required />
      <input type="text" v-model="form.cpf" placeholder="CPF" required />
      <input type="text" v-model="form.company_name" placeholder="Nome da empresa" />
      <input type="text" v-model="form.phone" placeholder="Telefone" />
      <input type="email" v-model="form.email" placeholder="E-mail" required />
      <input type="text" v-model="form.cep" placeholder="CEP" required @blur="buscarCep" />
      <input type="text" v-model="form.address" placeholder="Endereço" required />
      <input type="text" v-model="form.number" placeholder="Número" required />
      <input type="text" v-model="form.complement" placeholder="Complemento" />
      <input type="text" v-model="form.city" placeholder="Cidade" required />
      <input type="text" v-model="form.district" placeholder="Bairro" required />
      <input type="text" v-model="form.state" placeholder="Estado" required />
      <input type="text" v-model="form.affiliate" placeholder="Afiliado" required />
    </div>

    <button type="submit">Assinar</button>
  </form>
</template>

<script>
import axios from 'axios'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'FormularioAssinatura',
  props: {
    modo: {
      type: String,
      required: true
    },
    perfilAfiliado: {
      type: Object,
      default: null
    },
    userId: {
      type: [String, Number],
      required: true
    }
  },
  data() {
    return {
      form: {
        name: '',
        cpf: '',
        company_name: '',
        phone: '',
        email: '',
        cep: '',
        address: '',
        number: '',
        complement: '',
        city: '',
        district: '',
        state: '',
        affiliate: this.userId
      }
    }
  },
  mounted() {
    this.router = useRouter()
  },
  methods: {
    validarCPF(cpf) {
      cpf = cpf.replace(/[^\d]+/g, '')
      if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false

      let soma = 0
      for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i)) * (10 - i)
      }
      let resto = (soma * 10) % 11
      if (resto === 10 || resto === 11) resto = 0
      if (resto !== parseInt(cpf.charAt(9))) return false

      soma = 0
      for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i)) * (11 - i)
      }
      resto = (soma * 10) % 11
      if (resto === 10 || resto === 11) resto = 0
      return resto === parseInt(cpf.charAt(10))
    },

    async assinar() {
      const flashRef = this.$refs.flashRef

      if (!this.validarCPF(this.form.cpf)) {
        flashRef.showMessage('CPF inválido. Verifique e tente novamente.', 'error')
        return
      }

      try {
        const response = await axios.post('/api/signature', {
          ...this.form,
          plan_id: 1, // ou ajuste conforme necessário
          status: 1 // ajuste conforme necessário
        })

        if (response.status === 201) {
          flashRef.showMessage('Assinatura realizada com sucesso.', 'success')
          this.router.push('/welcome')
        }
      } catch (error) {
        console.error('Erro ao assinar:', error)
        flashRef.showMessage('Erro ao assinar. Tente novamente.', 'error')
      }
    },

    async buscarCep() {
      const cepValue = this.form.cep.replace(/\D/g, '')

      if (cepValue.length === 8) {
        try {
          const response = await axios.get(`https://viacep.com.br/ws/${cepValue}/json/`)

          if (!response.data.erro) {
            this.form.address = response.data.logradouro || ''
            this.form.district = response.data.bairro || ''
            this.form.city = response.data.localidade || ''
            this.form.state = response.data.uf || ''
          } else {
            this.$refs.flashRef.showMessage('CEP não encontrado.', 'error')
          }
        } catch (error) {
          console.error('Erro ao buscar CEP:', error)
          this.$refs.flashRef.showMessage('Erro ao buscar CEP.', 'error')
        }
      } else {
        this.$refs.flashRef.showMessage('CEP inválido. Digite 8 números.', 'error')
      }
    },

    resetForm() {
      this.form = {
        name: '',
        cpf: '',
        company_name: '',
        phone: '',
        email: '',
        cep: '',
        address: '',
        number: '',
        complement: '',
        city: '',
        district: '',
        state: '',
        affiliate: this.userId
      }
    }
  },
  watch: {
    perfilAfiliado: {
      handler(novoPerfil) {
        if (novoPerfil) {
          this.form = { ...novoPerfil }
        } else {
          this.resetForm()
        }
      },
      immediate: true
    }
  }
}
</script>

<style scoped>
.form-grid {
  justify-content: center;
  max-width: 900px;
  width: 100%;
}
.form-column {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 100%;
}
input {
  padding: 10px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 6px;
}
button {
  margin-top: 20px;
  padding: 12px 24px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  width: 100%;
  max-width: 300px;
  border-radius: 6px;
  font-size: 16px;
  align-self: center;
}
button:hover {
  background-color: #0056b3;
}
</style>
