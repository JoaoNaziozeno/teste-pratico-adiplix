<template>
  <div class="space-y-1.5 w-full relative">
    <label v-if="label" class="text-sm font-medium text-[#424754]">{{ label }}</label>
    
    <div 
      class="min-h-[42px] p-1.5 border border-[#c2c6d6] rounded-lg bg-white focus-within:ring-2 focus-within:ring-[#0058be] focus-within:border-[#0058be] transition-all cursor-text flex flex-wrap gap-2"
      @click="$refs.searchInput.focus()"
    >
      <div 
        v-for="personId in modelValue" 
        :key="personId"
        class="flex items-center gap-1 bg-blue-50 text-[#0058be] text-xs font-semibold px-2 py-1 rounded-md border border-blue-100"
      >
        {{ getPersonName(personId) }}
        <button @click.stop="toggleOption(personId)" class="hover:text-red-500">
          <span class="material-symbols-outlined text-sm">close</span>
        </button>
      </div>

      <input
        ref="searchInput"
        v-model="search"
        type="text"
        class="flex-1 min-w-[120px] outline-none text-sm bg-transparent px-1"
        :placeholder="modelValue.length === 0 ? placeholder : ''"
        @focus="isOpen = true"
      />
    </div>

    <div 
      v-if="isOpen && filteredOptions.length > 0" 
      class="absolute z-[70] w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
    >
      <div
        v-for="option in filteredOptions"
        :key="option.id"
        @click="toggleOption(option.id)"
        class="px-4 py-2 text-sm cursor-pointer hover:bg-gray-50 flex items-center justify-between"
        :class="{'bg-blue-50 text-[#0058be] font-medium': modelValue.includes(option.id)}"
      >
        <span>{{ option.name }}</span>
        <span v-if="modelValue.includes(option.id)" class="material-symbols-outlined text-sm">check</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    label: String,
    modelValue: { type: Array, default: () => [] },
    options: { type: Array, default: () => [] },
    placeholder: { type: String, default: 'Pesquisar pessoas...' }
  },
  data() {
    return {
      search: '',
      isOpen: false
    }
  },
  computed: {
    filteredOptions() {
      return this.options.filter(opt => 
        opt.name.toLowerCase().includes(this.search.toLowerCase())
      );
    }
  },
  methods: {
    toggleOption(id) {
      const newValue = [...this.modelValue];
      const index = newValue.indexOf(id);
      
      if (index > -1) {
        newValue.splice(index, 1);
      } else {
        newValue.push(id);
      }
      
      this.$emit('update:modelValue', newValue);
      this.search = '';
    },
    getPersonName(id) {
      const person = this.options.find(p => p.id === id);
      return person ? person.name : 'Desconhecido';
    },
    handleClickOutside(e) {
      if (!this.$el.contains(e.target)) {
        this.isOpen = false;
      }
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside);
  },
  unmounted() {
    document.removeEventListener('click', this.handleClickOutside);
  }
}
</script>