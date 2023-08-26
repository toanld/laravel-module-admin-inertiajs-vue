<script setup>
import { computed } from 'vue';

const inheritAttrs= false;

const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: true,
    },
    id: {
      type: String,
      default() {
        return `checkbox`
      },
    },
    error: String,
    label: String,
});


</script>

<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id">{{ label }}:
        <input :id="id" ref="input" type="checkbox" :checked="modelValue" @change="$emit('update:modelValue', $event.target.checked)" :class="{ error: error }"
           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-pointer">
        </label>
           <div v-if="error" class="form-error">{{ error }}</div>
    </div>
    
    
</template>

