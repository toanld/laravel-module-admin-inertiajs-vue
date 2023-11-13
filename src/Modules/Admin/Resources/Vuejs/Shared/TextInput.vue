<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <template v-if="api || options">
        <div class="dropdown">
            <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" @focus="showOptions()" @blur="exit()" class="form-input relative" :class="{ error: error }" :type="type" v-model="valueTemp"  autocomplete="off" @keyup="keyMonitor" />
            <div v-if="isLoading" class="input-spinner mr-2"></div>
            <div class="dropdown-content"
                 v-show="optionsShown">
                <div
                    class="dropdown-item"
                    @mousedown="selectOption(option)"
                    v-for="(option, index) in optionsTemp"
                    :key="index">
                    {{ option.name || option.id || '-' }}
                </div>
            </div>
        </div>
    </template>
    <template v-else>
        <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error }" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </template>
      <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import Fuse from 'fuse.js'
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
    modelValue: null,
      options:null,
    api:String
  },
  data() {
    return {
        selected: {},
        optionsShown: false,
        optionsTemp:[],
        valueTemp:null,
        isLoading:false,
        selectedOptionIndex: -1,
        searchTimeout: null,
        selectedIndex: -1, // Track the index of the currently selected option
    }
  },
  emits: ['update:modelValue'],
    created() {
      if(this.api) {
          this.valueTemp = this.modelValue.name;
      }
      if(this.options){
          this.optionsTemp = this.options;
      }
    },
    methods: {
    selectOption(option) {
      this.selected = option;
      this.optionsShown = false;
      this.valueTemp = option.name;
      this.$emit('update:modelValue', this.selected);
    },
    getApi(){
        if(this.api !== null){
            let form = new MyForm({
                q:this.valueTemp
            });
            this.isLoading = true;
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                // Sử dụng setTimeout để gọi API sau 0.3 giây
                form.get(this.api).then((res) => {
                    this.optionsTemp = res;
                    if (this.optionsTemp.length > 0) {
                        this.optionsShown = true;
                    }
                    this.isLoading = false;
                });
            }, 300);


        }
    },
    focus() {
      this.$refs.input.focus()
    },
    exit() {
        this.optionsShown = false;
    },
    showOptions(){
        if(this.optionsTemp.length > 0){
            this.optionsShown = true;
        }
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
    searchItem(keyword){
        // Tạo một instance của Fuse với các tùy chọn tìm kiếm
        const options = {
            includeScore: true,
            useExtendedSearch: true,
            keys: ['name'], // Thuộc tính bạn muốn tìm kiếm
        };
        const fuse = new Fuse(this.options, options);

        // Tìm kiếm gần đúng từ khóa
        const results = fuse.search(keyword);
        // Tạo một mảng mới chứa các bản ghi được tìm thấy
        this.optionsTemp = results.map(result => result.item);
        if(this.optionsTemp.length == 0){
            this.optionsTemp = this.options;
        }
    },
    keyMonitor:function(event) {
        if(this.options){
            this.valueTemp = event.target.value;
            this.searchItem(this.valueTemp);
            if(event.target.value !== this.modelValue.name) {
                this.$emit('update:modelValue', {
                    id:0,
                    name:event.target.value
                });
            }
        }
        if(this.api) {
            this.valueTemp = event.target.value;
            if(event.target.value !== this.modelValue.name) {
                this.$emit('update:modelValue', {
                    id:0,
                    name:event.target.value
                });
                this.getApi();
            }
        }
        //console.log(event);
    }
  },
}
</script>
<style lang="scss" scoped>
.input-spinner,
.input-spinner:after {
    border-radius: 50%;
    width: 1.7em;
    height: 1.7em;
}

.input-spinner {
    top:12px;
    right:10px;
    font-size: 10px;
    position: absolute;
    text-indent: -9999em;
    border-top: 0.2em solid #0c5460;
    border-right: 0.2em solid #0c5460;
    border-bottom: 0.2em solid #0c5460;
    border-left: 0.2em solid transparent;
    transform: translateZ(0);
    animation: spinningInput 1s infinite linear;
}
@keyframes spinningInput {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.dropdown {
    position: relative;
    display: block;
    margin: auto;
    .dropdown-input {
        background: #fff;
        cursor: pointer;
        border: 1px solid #e7ecf5;
        border-radius: 3px;
        color: #333;
        display: block;
        font-size: .8em;
        padding: 6px;
        min-width: 250px;
        max-width: 250px;
        &:hover {
            background: #f8f8fa;
        }
    }
    .dropdown-content {
        position: absolute;
        background-color: #fff;
        min-width: 248px;
        max-width: 248px;
        border: 1px solid #e7ecf5;
        box-shadow: 0px -8px 34px 0px rgba(0,0,0,0.05);
        z-index: 100000;
        .dropdown-item {
            color: black;
            font-size: .7em;
            line-height: 1em;
            padding: 8px;
            text-decoration: none;
            display: block;
            cursor: pointer;
            &:hover {
                background-color: #e7ecf5;
            }
        }
    }
    .dropdown:hover .dropdowncontent {
        display: block;
    }
}
</style>
