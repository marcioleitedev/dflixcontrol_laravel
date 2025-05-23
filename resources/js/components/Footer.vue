<template>
  <footer class="bg-dark text-light py-5" style="height: 200px">
    <div class="container h-100 d-flex flex-column justify-content-center">
      <div class="row">
        <!-- Coluna de Navegação -->
        <div class="col-md-6 mb-4 mb-md-0">
          <h5 class="mb-3">Navegação</h5>
          <ul class="list-unstyled">
            <li><router-link to="/" class="text-light text-decoration-none">Home</router-link></li>
            <li><router-link to="/planos" class="text-light text-decoration-none">Planos</router-link></li>
            <li><router-link to="/login" class="text-light text-decoration-none">Login</router-link></li>
          </ul>
        </div>

        <!-- Coluna de Redes Sociais -->
        <div class="col-md-6 d-flex flex-column align-items-md-end">
          <h5 class="mb-3">Redes Sociais</h5>
          <div class="d-flex gap-3">
            <a href="https://www.youtube.com" target="_blank" class="text-light fs-4">
              <i class="bi bi-youtube"></i>
            </a>
            <a href="https://www.instagram.com" target="_blank" class="text-light fs-4">
              <i class="bi bi-instagram"></i>
            </a>
            <a href="https://www.linkedin.com" target="_blank" class="text-light fs-4">
              <i class="bi bi-linkedin"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
// Nenhum script necessário
</script>

<style scoped>
footer a:hover {
  color: #ffc107;
}
</style>
