<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
        <Datepicker :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error }" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>
import Datepicker from 'vue3-datepicker'
export default {
    inheritAttrs: false,
    components: {
        Datepicker
    },
    props: {
        id: {
            type: String,
            default() {
                return `text-input`
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        error: String,
        label: String,
        modelValue: String,
    },
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        },
        setSelectionRange(start, end) {
            this.$refs.input.setSelectionRange(start, end)
        },
    },
}
</script>
