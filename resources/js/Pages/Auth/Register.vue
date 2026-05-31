<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="bg-white border border-gray-200 rounded-2xl p-8 w-full max-w-sm shadow-sm">

      <h1 class="text-2xl font-bold text-gray-900 mb-1">Crea account</h1>
      <p class="text-sm text-gray-400 mb-6">Inizia a usare NexaHub</p>

      <div class="space-y-3">
        <div>
          <input
            v-model="form.name"
            type="text"
            placeholder="Nome"
            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
          />
          <p v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name }}</p>
        </div>

        <div>
          <input
            v-model="form.email"
            type="email"
            placeholder="Email"
            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
          />
          <p v-if="errors.email" class="text-xs text-red-500 mt-1">{{ errors.email }}</p>
        </div>

        <input
          v-model="form.password"
          type="password"
          placeholder="Password"
          class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
        />

        <div>
          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Conferma password"
            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
          />
          <p v-if="errors.password" class="text-xs text-red-500 mt-1">{{ errors.password }}</p>
        </div>
      </div>

      <button
        @click="submit"
        class="w-full mt-5 bg-indigo-600 text-white text-sm py-2 rounded-lg hover:bg-indigo-700"
      >
        Registrati
      </button>

      <p class="text-sm text-center text-gray-400 mt-4">
        Hai già un account?
        <Link href="/login" class="text-indigo-600 hover:underline">Accedi</Link>
      </p>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const errors = page.props.errors

const form = ref({
  name:                  '',
  email:                 '',
  password:              '',
  password_confirmation: '',
})

const submit = () => {
  router.post('/register', form.value)
}
</script>