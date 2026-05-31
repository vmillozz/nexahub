<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="bg-white border border-gray-200 rounded-2xl p-8 w-full max-w-sm shadow-sm">

      <h1 class="text-2xl font-bold text-gray-900 mb-1">Accedi</h1>
      <p class="text-sm text-gray-400 mb-6">Bentornato su NexaHub</p>

      <div class="space-y-3">
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

        <label class="flex items-center gap-2 text-sm text-gray-600">
          <input v-model="form.remember" type="checkbox" class="rounded" />
          Ricordami
        </label>
      </div>

      <button
        @click="submit"
        class="w-full mt-5 bg-indigo-600 text-white text-sm py-2 rounded-lg hover:bg-indigo-700"
      >
        Accedi
      </button>

      <p class="text-sm text-center text-gray-400 mt-4">
        Non hai un account?
        <Link href="/register" class="text-indigo-600 hover:underline">Registrati</Link>
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
  email:    '',
  password: '',
  remember: false,
})

const submit = () => {
  router.post('/login', form.value)
}
</script>