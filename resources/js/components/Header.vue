<template>
      <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 shadow-sm">
        <div class="relative max-w-md w-full">
          <input class="w-full pl-10 pr-4 py-2 bg-[#f2f3fd] border border-[#c2c6d6] rounded-lg text-sm outline-none focus:ring-2 focus:ring-[#0058be]" placeholder="Pesquisar pessoas" type="text"/>
          <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#424754] text-sm">search</span>
        </div>
        <div class="flex items-center gap-4">
          <div class="h-8 w-px bg-gray-200 mx-2"></div>
          <div class="w-8 h-8 rounded-full bg-[#adc6ff] flex items-center justify-center text-[#0058be] font-bold text-xs">JN</div>
        </div>
      </header>
</template>

<script>
export default {
  name: 'Header'
}
</script>