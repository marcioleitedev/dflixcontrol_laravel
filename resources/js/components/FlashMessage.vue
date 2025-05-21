<template>
  <div v-if="visible" :class="['flash', type]" class="flash-message">
    {{ message }}
  </div>
</template>

<script setup>
import { ref } from 'vue'

const visible = ref(false)
const message = ref('')
const type = ref('success')

const showMessage = (msg, msgType = 'success', duration = 3000) => {
  message.value = msg
  type.value = msgType
  visible.value = true
  setTimeout(() => {
    visible.value = false
  }, duration)
}

defineExpose({ showMessage })
</script>

<style scoped>
.flash-message {
margin:0 auto;
width: 50%;
  top: 20px;
  right: 20px;
  padding: 14px 24px;
  border-radius: 8px;
  color: white;
  z-index: 9999;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
  font-weight: bold;
  transition: opacity 0.3s ease;
}
.flash.success {
  background-color: #28a745;
}
.flash.error {
  background-color: #dc3545;
}
.flash.warning {
  background-color: #ffc107;
  color: #333;
}
</style>
