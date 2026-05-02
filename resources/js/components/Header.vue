<template>
  <header class="fixed top-0 right-0 left-64 h-16 flex items-center justify-between px-6 bg-white border-b shadow-sm">
    
    <input 
      type="text" 
      placeholder="Buscar..." 
      class="border rounded px-3 py-1"
    />

    <div class="flex items-center gap-3">
      <span></span>
    </div>

  </header>
</template>

<script>
export default {
  name: 'Header'
}
</script>