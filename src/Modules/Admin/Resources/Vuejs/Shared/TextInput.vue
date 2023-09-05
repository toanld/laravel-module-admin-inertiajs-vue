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
    keyMonitor:function(event) {

        if(this.api) {
            /*
            if(event.key == "ArrowDown"){
                // Di chuyển lựa chọn xuống
                if (this.selectedOptionIndex < this.optionsTemp.length - 1) {
                    this.selectedOptionIndex++;
                }
            }
            if(event.key == "ArrowUp"){
                if (this.selectedOptionIndex > 0) {
                    this.selectedOptionIndex--;
                }
            }
            // Lấy lựa chọn được chọn và cập nhật giá trị input
            if (this.selectedOptionIndex >= 0 && this.selectedOptionIndex < this.optionsTemp.length) {
                const selectedOption = this.optionsTemp[this.selectedOptionIndex];
                this.valueTemp = selectedOption.name;
                this.$emit('update:modelValue', {
                    id:selectedOption.id,
                    name:selectedOption.name
                });
            } else {
                // Nếu không có lựa chọn nào, giữ nguyên giá trị input
                this.valueTemp = event.target.value;
            }
            //*/

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
        z-index: 1;
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
