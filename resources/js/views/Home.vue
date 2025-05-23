<!-- resources/js/views/Home.vue -->
<template>
   <Navbar />

  <div class="container-fluid home-bg d-flex flex-column justify-content-center align-items-center text-white">
   <div class="blocotexto">
    <h1 class="text-center mb-4">Sistema de Gestão para Auto Center</h1>
    <p class="lead text-center">
      Organize sua oficina, gere orçamentos, controle de estoque , fluxo de caixa e muito mais com nosso sistema!
    </p>

    <div class="text-center mt-4">
      <router-link to="/planos" class="btn btn-primary btn-lg">Conheça os Planos</router-link>
      <router-link to="/login" class="btn btn-outline-success btn-lg ms-3">Já sou cliente</router-link>
    </div>
    </div>
  </div>
  <Footer />
</template>

<script setup>
import Navbar from '@/components/Navbar.vue'
import Footer from '@/components/Footer.vue'
</script>

<style scoped>
.home-bg {
  background-image: url('/images/oficina-mecanica.jpg');
  background-size: cover;
  background-position: center;
  min-height: 100vh;
}
.blocotexto{
  background-color: aliceblue;
  padding: 100px;
  border-radius: 10px;
  color:#000;
  opacity:0.9;
}
</style>