<template>
    <div v-if="new_type != null && new_type != ''" class="flex items-center p-4 mb-4 text-sm border rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" :class="alertClass" role="alert">
        <span class="sr-only">Info</span>
        <div>
            <span class="font-medium"><slot /></span>
        </div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    props: {
        type: String
    },
    data() {
        return {
            new_type:null,
            timer: null
        };
    },
    watch: {
        type(newVal, oldVal) {
            if (newVal) {
                this.new_type = this.type;
            }
        }
    },
    computed: {
        alertClass() {
            return {
                'text-blue-800 border-blue-300': this.new_type === 'info',
                'text-red-800 border-red-300': this.new_type === 'error',
                'text-green-800 border-green-300': this.new_type === 'success',
                'text-yellow-800 border-yellow-300': this.new_type === 'warning',
            };
        }
    },
    created() {
       this.new_type = this.type;
    },
    mounted() {
        this.timer = setTimeout(() => {
            this.isVisible = false;
            this.new_type = null;
            this.$emit('update:type', '');
        }, 5000);
    },
    beforeDestroy() {
        if (this.timer) {
            clearTimeout(this.timer);
        }
    }
}
</script>
