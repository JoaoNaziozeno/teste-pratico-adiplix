<template>
  <button 
    class="flex items-center gap-2 bg-[#0058be] text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-sm hover:opacity-90 active:scale-95 transition-all"
  >
    <span v-if="icon" class="material-symbols-outlined text-sm">
      {{ icon }}
    </span>
    
    <slot></slot>
  </button>
</template>

<script>
export default {
  name: 'BaseButton',
  props: {
    icon: {
      type: String,
      default: ''
    }
  }
}
</script>