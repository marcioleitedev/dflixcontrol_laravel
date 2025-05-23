<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dflix">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="/">
        <img :src="logo" alt="Logo" height="100" class="me-2" />
      
      </a>
      
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/planos">Planos</a>
          </li>
        </ul>

        <div class="d-flex">
          <a class="btn btn-outline-light" href="/login">Login</a>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import logo from '../image/logo.jpg'; // ajuste o nome da imagem aqui

export default {
  name: 'Navbar',
  data() {
    return {
      logo
    };
  }
};
</script>

<style scoped>
.bg-dflix{
    background-color: #FA8D20;
    border-bottom: 3px solid #919191;
}
</style>
