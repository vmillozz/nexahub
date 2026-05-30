<template>
  <div style="padding: 2rem; font-family: sans-serif;">
    <h1>Benvenuto su NexaHub</h1>
    <p>Ciao, {{ user.name }}</p>
  </div>
</template>

<script setup>
defineProps({
  user: Object,
})
</script>