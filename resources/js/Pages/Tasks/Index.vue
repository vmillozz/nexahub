<template>
  <AppLayout>

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Task</h1>
      <button
        @click="showForm = true"
        class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700"
      >
        + Nuovo task
      </button>
    </div>

    <!-- Filtri -->
    <div class="flex gap-3 mb-6">
      <select
        v-model="filters.status"
        @change="applyFilters"
        class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white"
      >
        <option value="">Tutti gli stati</option>
        <option value="todo">Todo</option>
        <option value="in_progress">In corso</option>
        <option value="done">Completati</option>
      </select>

      <select
        v-model="filters.priority"
        @change="applyFilters"
        class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white"
      >
        <option value="">Tutte le priorità</option>
        <option value="low">Bassa</option>
        <option value="medium">Media</option>
        <option value="high">Alta</option>
      </select>
    </div>

    <!-- Lista task -->
    <div class="space-y-3">
      <div
        v-for="task in tasks"
        :key="task.id"
        class="bg-white border border-gray-200 rounded-xl p-4 flex items-center justify-between"
      >
        <div class="flex items-center gap-3">
          <span :class="priorityColor(task.priority)" class="w-2 h-2 rounded-full flex-shrink-0"></span>
          <div>
            <p class="text-sm font-medium text-gray-900">{{ task.title }}</p>
            <p class="text-xs text-gray-400 mt-0.5">
              {{ task.assignee?.name ?? 'Non assegnato' }}
              <span v-if="task.due_at"> · Scade {{ formatDate(task.due_at) }}</span>
            </p>
          </div>
        </div>

        <div class="flex items-center gap-2">
          <select
            :value="task.status"
            @change="updateStatus(task, $event.target.value)"
            class="text-xs border border-gray-200 rounded-lg px-2 py-1 bg-white"
          >
            <option value="todo">Todo</option>
            <option value="in_progress">In corso</option>
            <option value="done">Fatto</option>
          </select>

          <button
            @click="deleteTask(task)"
            class="text-xs text-red-400 hover:text-red-600 px-2 py-1"
          >
            Elimina
          </button>
        </div>
      </div>

      <p v-if="tasks.length === 0" class="text-sm text-gray-400 text-center py-12">
        Nessun task trovato.
      </p>
    </div>

    <!-- Form nuovo task -->
    <div
      v-if="showForm"
      class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
      @click.self="showForm = false"
    >
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Nuovo task</h2>

        <div class="space-y-3">
          <input
            v-model="form.title"
            type="text"
            placeholder="Titolo del task"
            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
          />

          <select
            v-model="form.priority"
            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
          >
            <option value="low">Priorità bassa</option>
            <option value="medium">Priorità media</option>
            <option value="high">Priorità alta</option>
          </select>

          <select
            v-model="form.assigned_to"
            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
          >
            <option value="">Non assegnato</option>
            <option v-for="m in members" :key="m.id" :value="m.id">
              {{ m.name }}
            </option>
          </select>

          <input
            v-model="form.due_at"
            type="date"
            class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2"
          />
        </div>

        <div class="flex justify-end gap-2 mt-5">
          <button
            @click="showForm = false"
            class="text-sm px-4 py-2 rounded-lg border border-gray-200 text-gray-600"
          >
            Annulla
          </button>
          <button
            @click="submitForm"
            class="text-sm px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"
          >
            Crea task
          </button>
        </div>
      </div>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  tasks:   Array,
  members: Array,
  filters: Object,
})

const showForm = ref(false)

const filters = ref({
  status:   props.filters.status   ?? '',
  priority: props.filters.priority ?? '',
})

const form = ref({
  title:       '',
  priority:    'medium',
  assigned_to: '',
  due_at:      '',
})

const applyFilters = () => {
  router.get('/tasks', filters.value, { preserveState: true, replace: true })
}

const submitForm = () => {
  router.post('/tasks', form.value, {
    onSuccess: () => {
      showForm.value = false
      form.value = { title: '', priority: 'medium', assigned_to: '', due_at: '' }
    },
  })
}

const updateStatus = (task, status) => {
  router.patch(`/tasks/${task.id}`, { status }, { preserveScroll: true })
}

const deleteTask = (task) => {
  if (confirm(`Eliminare "${task.title}"?`)) {
    router.delete(`/tasks/${task.id}`, { preserveScroll: true })
  }
}

const priorityColor = (p) => ({
  low:    'bg-gray-300',
  medium: 'bg-yellow-400',
  high:   'bg-red-500',
}[p])

const formatDate = (d) => new Date(d).toLocaleDateString('it-IT')
</script>