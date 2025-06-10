<template>
    <div>
      <div class="mb-3">
        <label for="name" class="form-label">Empresa</label>
        <input
          type="text"
          id="name"
          class="form-control"
          v-model="form.name"
          required
        />
      </div>
      <div class="mb-3">
        <label for="cnpj_cpf" class="form-label">CNPJ</label>
        <input
          type="text"
          id="cnpj_cpf"
          class="form-control"
          v-model="form.cnpj_cpf"
          required
        />
      </div>
      <div class="mb-3">
        <label for="ie" class="form-label">IE</label>
        <input
          type="text"
          id="ie"
          class="form-control"
          v-model="form.ie"
          required
        />
      </div>
      <div class="mb-3">
        <label for="contact_name" class="form-label">Nome do Contato</label>
        <input
          type="text"
          id="contact_name"
          class="form-control"
          v-model="form.contact_name"
          required
        />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input
          type="email"
          id="email"
          class="form-control"
          v-model="form.email"
          required
        />
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Telefone</label>
        <input
          type="text"
          id="phone"
          class="form-control"
          v-model="form.phone"
          required
        />
      </div>
      <div class="mb-3">
        <label for="postal_code" class="form-label">CEP</label>
        <input
          type="text"
          id="postal_code"
          class="form-control"
          v-model="form.postal_code"
          required
        />
      </div>
      <div class="mb-3">
        <label for="address" class="form-label">Endereço</label>
        <input
          type="text"
          id="address"
          class="form-control"
          v-model="form.address"
          required
        />
      </div>
      <div class="mb-3">
        <label for="city" class="form-label">Cidade</label>
        <input
          type="text"
          id="city"
          class="form-control"
          v-model="form.city"
          required
        />
      </div>
      <div class="mb-3">
        <label for="state" class="form-label">Estado</label>
        <input
          type="text"
          id="state"
          class="form-control"
          v-model="form.state"
          required
        />
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'FornecedoresFormFields',
    props: {
      form: {
        type: Object,
        required: true
      }
    }
  }
  </script>