<template>
  <aside class="fixed inset-y-0 left-0 w-64 bg-gray-100 p-4">
    
    <nav class="fixed inset-y-0 left-0 w-64 flex flex-col p-4 z-50 bg-gray-50 border-r border-gray-200">
      <div class="mb-8 px-2 flex items-center gap-3">
        <div class="w-8 h-8 rounded bg-[#0058be] flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-lg">corporate_fare</span>
        </div>
        <div>
          <h1 class="text-lg font-black text-blue-600 leading-none font-bold">TESTE PRÁTICO</h1>
        </div>
      </div>
      <div class="flex-1 space-y-1">
        <router-link to="/people" class="nav-link">
          <span class="material-symbols-outlined">group</span>
          <span class="text-sm">Pessoas</span>
        </router-link>

        <router-link to="/tasks" class="nav-link">
          <span class="material-symbols-outlined">assignment</span>
          <span class="text-sm">Tarefas</span>
        </router-link>
      </div>
    </nav>

  </aside>
</template>

<script>
export default {
  name: 'Sidebar'
}
</script>

<style scoped>

.nav-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 0.875rem;
  color: #4b5563;
  transition: all 0.2s;
}

.nav-link:hover {
  background-color: rgba(229, 231, 235, 0.5);
  color: #111827;
}


.router-link-active {
  background-color: #eff6ff !important;
  color: #2563eb !important; 
  font-weight: 700;
}


.nav-link:active {
  transform: scale(0.95);
}
</style>