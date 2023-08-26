<template>
    <div class="select-search">
        <input type="text" v-model="searchKeyword" @input="filterOptions" placeholder="Search..." />
        <select v-model="selectedOption">
            <option value="" disabled>Select an option</option>
            <option v-for="option in filteredOptions" :value="option.value" :key="option.value">{{ option.label }}</option>
        </select>
    </div>
</template>
<script>
export default {
    name: 'SelectSearch',
    props: {
        options: Array
    },
    data() {
        return {
            selectedOption: '',
            searchKeyword: ''
        };
    },
    computed: {
        filteredOptions() {
            if (this.searchKeyword.trim() === '') {
                return this.options;
            }
            const keyword = this.searchKeyword.trim().toLowerCase();
            return this.options.filter(option =>
                option.label.toLowerCase().includes(keyword)
            );
        }
    },
    methods: {
        emitSelection() {
            this.$emit('input', this.selectedOption);
        },
        filterOptions() {
            // Force re-selection after filtering if the selected option is not in the filtered list
            if (!this.filteredOptions.some(option => option.value === this.selectedOption)) {
                this.selectedOption = '';
            }
        }
    }
};
</script>
