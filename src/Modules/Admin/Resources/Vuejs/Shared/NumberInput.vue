<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error }" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import debounce from 'lodash.debounce';
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
    modelValue: String,
    valueTemp:String,
  },
  watch:{
      modelValue:function (vale) {
          this.convertOnBlur();
      }
  },
  emits: ['update:modelValue'],
  methods: {
      convertOnBlur: debounce(function() {

          let newValue =  this.convertCurrencyToNumber(this.modelValue);
          if(newValue != this.modelValue){
              this.$emit('update:modelValue',newValue);
          }
      }, 1000), // Đợi 1 giây trước khi chuyển đổi
      convertCurrencyToNumber(currencyString) {
          const conversionTable = {
              "tr": 1000000,
              "triệu": 1000000,
              "tỷ": 1000000000
              // Các viết tắt khác có thể thêm vào đây
          };

          const matches = currencyString.match(/([\d,.]+)\s*([a-z]+)/i);

          if (matches) {
              const amount = parseFloat(matches[1].replace(/,/g, '').replace(/\./g, '').replace(/,/g, '.'));
              const unit = matches[2].toLowerCase();

              if (conversionTable.hasOwnProperty(unit)) {
                  const convertedValue = amount * conversionTable[unit];
                  return new Intl.NumberFormat('vi', { maximumSignificantDigits: 3 }).format(convertedValue);
              }
          }

          return currencyString; // Trả về NaN nếu không thể chuyển đổi
      },
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
