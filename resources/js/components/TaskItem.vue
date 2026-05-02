<template>
  <div class="flex items-center gap-4 p-4">
    <div>
      <h4>{{ task.title }}</h4>
      <p>{{ task.description }}</p>
    </div>

    <button @click="$emit('remove', task.id)">
      Remover
    </button>
  </div>
</template>

<script>
export default {
  props: ['task']
}
</script>