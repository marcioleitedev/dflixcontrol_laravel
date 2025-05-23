<template>
   <Navbar />
  <div class="signature-container">
    <h1>Assinatura</h1>
    <!-- Flash message -->
    <FlashMessage ref="flashRef" />
    <p v-if="planoData">
      Você está assinando o plano: <strong>{{ planoData.name }}</strong>
    </p>
    <p v-if="planoData">
      Mensalidade do seu Plano:
      <strong>R$ {{ planoData.price.toFixed(2) }}</strong>
    </p>
    <p v-else>Carregando informações do plano...</p>

    <form @submit.prevent="assinar" class="form-grid">
      <div class="form-column">
        <input type="text" v-model="name" placeholder="Nome completo" required />
              <input type="text" v-model="cpf" placeholder="CPF" required />
                      <input
          type="text"
          v-model="cep"
          placeholder="CEP"
          required
          @blur="buscarCep"
        />
        <input type="text" v-model="address" placeholder="Endereço" required />
        <input type="text" v-model="complement" placeholder="Complemento" />
         <input type="text" v-model="city" placeholder="Cidade" required />
        
      </div>

      <div class="form-column">
        <input type="text" v-model="company_name" placeholder="Nome da empresa" />
        <input type="text" v-model="phone" placeholder="Telefone" />
        <input type="email" v-model="email" placeholder="E-mail" required />
 
        <input type="text" v-model="number" placeholder="Número" required />
      
        <input type="text" v-model="district" placeholder="Bairro" required />
        <input type="text" v-model="state" placeholder="Estado" required />
        
        
      </div>

      <button type="submit">Assinar</button>
    </form>
  </div>
  <Footer />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import FlashMessage from '@/components/FlashMessage.vue'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

const flashRef = ref(null)

const router = useRouter()
const route = useRoute()
const planoId = route.params.id

// Form fields
const name = ref('')
const email = ref('')
const cpf = ref('')
const cep = ref('')
const address = ref('')
const number = ref('')
const complement = ref('')
const district = ref('')
const city = ref('')
const state = ref('')
const phone = ref('')
const company_name = ref('')
const status = ref(true)

// Plano info
const planoData = ref(null)

const fetchPlano = async () => {
  try {
    const response = await axios.get(`/api/plans/${planoId}`)
    planoData.value = response.data
  } catch (error) {
    console.error('Erro ao buscar plano:', error)
  }
}

const assinar = async () => {
    if (!validarCPF(cpf.value)) {
    flashRef.value.showMessage('CPF inválido. Verifique e tente novamente.', 'error')
    return
  }

  try {
    const response = await axios.post('/api/signature', {
      name: name.value,
      email: email.value,
      cpf: cpf.value,
      status: status.value,
      plan_id: planoId,
      cep: cep.value,
      address: address.value,
      number: number.value,
      complement: complement.value,
      city: city.value,
      district: district.value,
      state: state.value,
      phone: phone.value,
      company_name: company_name.value
    })

      if (response.status === 201) {
        flashRef.value.showMessage('Assinatura realizada com sucesso.', 'success')
  
          router.push('/welcome');
    }
  } catch (error) {
      console.error('Erro ao assinar:', error)
    flashRef.value.showMessage('Erro ao assinar. Tente novamente.', 'error')
   
  }
}


const validarCPF = (cpf) => {
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
  if (resto !== parseInt(cpf.charAt(10))) return false

  return true
}

const buscarCep = async () => {
  const cepValue = cep.value.replace(/\D/g, '')

  if (cepValue.length === 8) {
    try {
      const response = await axios.get(`https://viacep.com.br/ws/${cepValue}/json/`)
      if (!response.data.erro) {
        address.value = response.data.logradouro || ''
        district.value = response.data.bairro || ''
        city.value = response.data.localidade || ''
        state.value = response.data.uf || ''
      } else {
        alert('CEP não encontrado.')
      }
    } catch (error) {
      console.error('Erro ao buscar CEP:', error)
    }
  }
}

onMounted(() => {
  fetchPlano()
})
</script>

<style scoped>
.signature-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 40px 20px;
  background-color: #f8f9fa;
  min-height: 100vh;
}

h1, p {
  margin-bottom: 20px;
  text-align: center;
}

.form-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
  max-width: 900px;
  width: 100%;
}

.form-column {
  display: flex;
  flex-direction: column;
  gap: 10px;
  width: 100%;
  max-width: 400px;
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
