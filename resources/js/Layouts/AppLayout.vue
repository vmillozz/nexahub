<template>
  <div class="min-h-screen bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

          <span class="text-xl font-bold text-indigo-600">NexaHub</span>

          <div class="flex items-center gap-6">
            <Link href="/dashboard" class="text-sm text-gray-600 hover:text-gray-900">
              Dashboard
            </Link>
            <Link href="/tasks" class="text-sm text-gray-600 hover:text-gray-900">
              Task
            </Link>
            <span class="text-sm font-medium text-gray-800">
              {{ page.props.auth?.user?.name }}
            </span>
          </div>

        </div>
      </div>
    </nav>

    <!-- Contenuto -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>

  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
</script>