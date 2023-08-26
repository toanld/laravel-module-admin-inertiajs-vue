<template>
    <div :class="$attrs.class">
        <template v-for="(v,k) in options">
            <label class="form-label" :for="id+v.id">{{ v.name }}:</label>
            <input :id="id+v.id" type="radio" v-bind="{ ...$attrs, class: null }" class="form-radio" :class="{ error: error }" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
        </template>
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>
</template>

<script>

export default {
    inheritAttrs: false,
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
        options:[],
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
