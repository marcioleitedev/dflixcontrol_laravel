<template>
  <Navbar />
  <div class="container mt-5">
    <h1 class="text-center mb-4">Planos</h1>

    <!-- Blocos dos planos -->
    <div class="box">
      <div class="bloco" v-for="plan in plans" :key="plan.id">
        <h2>{{ plan.name }}</h2>
        <p>R$ {{ plan.price.toFixed(2) }}/mês</p>
        <p>{{ plan.company }} Empresa{{ plan.company === 1 ? '' : 's' }}.</p>
        <p>{{ plan.users }} Usuário{{ plan.users === 1 ? '' : 's' }}.</p>
        <p>Espaço Ilimitado.</p>
        <button
          class="btn btn-primary"
          @click="assinarPlano(plan.id)"
        >
          Assinar
        </button>
      </div>
    </div>

    <!-- Tabela de funcionalidades -->
    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
          <tr>
            <th>Funcionalidade</th>
            <th>Plano Básico</th>
            <th>Plano Fiscal</th>
           
          </tr>
        </thead>
        <tbody>
          <!-- Categorias e funcionalidades -->
          <tr class="table-secondary"><th colspan="5" class="text-start">Financeiro</th></tr>
          <tr><td>Contas a pagar e contas a receber</td><td>✔</td><td>✔</td></tr>
          <tr><td>Controle de caixa</td><td>✔</td><td>✔</td></tr>
          <tr><td>DRE - Demonstração do Resultado do Exercício</td><td>✔</td><td>✔</td></tr>
          <tr><td>Fluxo de caixa</td><td>✔</td><td>✔</td></tr>
          <tr><td>Centro de custos</td><td>✔</td><td>✔</td></tr>
          <tr><td>Planos de contas</td><td>✔</td><td>✔</td></tr>

          <tr class="table-secondary"><th colspan="5" class="text-start">Nota Fiscal (ilimitadas)</th></tr>
          <tr><td>NFS-E (emissão automática)</td><td>✘</td><td>✔</td></tr>
          <tr><td>NF-E (emissão automática)</td><td>✘</td><td>✔</td></tr>
          <tr><td>NFC-E (emissão automática)</td><td>✘</td><td>✔</td></tr>

          <tr class="table-secondary"><th colspan="5" class="text-start">Vendas</th></tr>
          <tr><td>Orçamentos</td><td>✔</td><td>✔</td></tr>
          <tr><td>Venda de produtos (mais detalhada)</td><td>✔</td><td>✔</td></tr>
          <!-- <tr><td>Venda balcão (frente de caixa pdv)</td><td>✔</td><td>✔</td></tr>
          <tr><td>Integração com balança (Prix)</td><td>✔</td><td>✔</td></tr> -->
          <!-- <tr><td>Trocas e devoluções</td><td>✔</td><td>✔</td></tr>
          <tr><td>Cupom presente</td><td>✔</td><td>✔</td></tr>
          <tr><td>Cupom não fiscal</td><td>✔</td><td>✔</td></tr> -->
          <tr><td>Envio da venda por WhatsApp e e-mail</td><td>✔</td><td>✔</td></tr>
          <tr><td>Venda com mais de uma forma de pagamento</td><td>✔</td><td>✔</td></tr>
          <tr><td>Situações de venda personalizáveis</td><td>✔</td><td>✔</td></tr>
          <tr><td>Relatórios de vendas</td><td>✔</td><td>✔</td></tr>

          <tr class="table-secondary"><th colspan="5" class="text-start">Gestão de cadastros</th></tr>
          <tr><td>Cartela de clientes (ilimitados)</td><td>✔</td><td>✔</td></tr>
          <tr><td>Cadastro de funcionários</td><td>✔</td><td>✔</td></tr>
          <tr><td>Cadastro de fornecedores e transportadoras</td><td>✔</td><td>✔</td></tr>
          <tr><td>Restrições de permissões dos usuários</td><td>✔</td><td>✔</td></tr>
          <tr><td>Controle de logs de usuários</td><td>✔</td><td>✔</td></tr>

        </tbody>
      </table>
    </div>
  </div>
  <Footer />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'

const plans = ref([])
const router = useRouter()

onMounted(async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/plans')
    plans.value = response.data
  } catch (error) {
    console.error('Erro ao buscar planos:', error)
  }
})

const assinarPlano = (id) => {
  router.push(`/assinar/${id}`)
}
</script>

<style scoped>
.box {
  display: flex;
  justify-content: space-between;
}
.bloco {
  width: 20%;
  min-width: 250px;
  background-color: #f8f9fa;
  border-radius: 5px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-align: center;
  margin: 10px;
}
</style>


