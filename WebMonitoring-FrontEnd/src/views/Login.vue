<template>
  <section class="login-page">
    <div class="login-card">
      <h1>Login</h1>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="email" type="email" id="email" required placeholder="Email">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input v-model="password" type="password" id="password" required placeholder="Password">
        </div>

        <button type="submit">Masuk</button>
      </form>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const router = useRouter()


const handleLogin = async () => {
  try {
    const response = await axios.post(
      'http://localhost:8000/api/login',
      {
        email: email.value,
        password: password.value
      },
      {
        withCredentials: true
      }
    )
    localStorage.setItem('token', response.data.token)
    alert('Login berhasil!')
     router.push('/admin/dashboard')
  }     catch (error) {
    
    alert(error.response?.data?.message || 'Terjadi kesalahan login')
  }
}
</script>

<style scoped lang="scss">
@use "sass:color";
@use "@/assets/scss/abstracts/variables" as *;
.login-page {
  background-color: $background-color;
  min-height: 80vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}

.login-card {
  background: white;
  padding: 2.5rem;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

input {
  padding: 0.8rem;
  border: 1px solid $border-color;
  border-radius: 5px;
  background: $light-gray;
}

button {
  padding: 0.8rem;
  background-color: $primary-color;
  color: white;
  border: none;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s;
}

button:hover {
  background-color: color.adjust($primary-color, $lightness: -10%);
}
</style>
