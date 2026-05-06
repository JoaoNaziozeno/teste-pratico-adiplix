<template>
  <div class="space-y-1.5 w-full">
    <label v-if="label" class="text-sm font-medium text-[#424754]">
      {{ label }}
    </label>
    
    <input
      v-if="type !== 'textarea'"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      v-bind="$attrs"
      class="w-full px-4 py-2 border border-[#c2c6d6] rounded-lg text-sm focus:ring-2 focus:ring-[#0058be] focus:border-[#0058be] outline-none transition-all"
      :type="type"
    />

    <textarea
      v-else
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      v-bind="$attrs"
      class="w-full px-4 py-2 border border-[#c2c6d6] rounded-lg text-sm focus:ring-2 focus:ring-[#0058be] focus:border-[#0058be] outline-none transition-all"
    ></textarea>
  </div>
</template>

<script>
export default {
  props: ['label', 'modelValue', 'type']
}
</script>