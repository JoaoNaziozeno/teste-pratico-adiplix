<template>
  <aside class="fixed inset-y-0 left-0 w-64 bg-gray-100 p-4">
    
    <nav class="fixed inset-y-0 left-0 w-64 flex flex-col p-4 z-50 bg-gray-50 border-r border-gray-200">
      <div class="mb-8 px-2 flex items-center gap-3">
        <div class="w-8 h-8 rounded bg-[#0058be] flex items-center justify-center">
          <span class="material-symbols-outlined text-white text-lg">corporate_fare</span>
        </div>
        <div>
          <h1 class="text-lg font-black text-blue-600 leading-none font-bold">TESTE PRÁTICO</h1>
          <!--<p class="text-[10px]  tracking-widest text-[#424754]">Gerenciamento de Pessoas e Tarefas</p>-->
        </div>
      </div>
      <div class="flex-1 space-y-1">
        <router-link to="/people" class="flex items-center gap-3 px-4 py-2 text-blue-600 font-bold bg-blue-50 rounded-lg">
          <span class="material-symbols-outlined">group</span>
          <span class="text-sm">Pessoas</span>
        </router-link>
        <router-link to="/tasks" class="flex items-center gap-3 px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200/50 rounded-lg transition-all">
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