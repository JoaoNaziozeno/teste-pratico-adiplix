<template>
  <aside class="fixed inset-y-0 left-0 w-64 bg-gray-100 p-4">

    <h1 class="text-xl font-bold mb-6">TaskFlow</h1>

    <nav class="flex flex-col gap-2">
      <router-link to="/people" class="p-2 hover:bg-gray-200 rounded">
        Pessoas
      </router-link>

      <router-link to="/tasks" class="p-2 hover:bg-gray-200 rounded">
        Tarefas
      </router-link>
    </nav>

  </aside>
</template>

<script>
export default {
  name: 'Sidebar'
}
</script>