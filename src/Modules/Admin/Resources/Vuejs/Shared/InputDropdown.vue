<template>
    <div :class="$attrs.class">
        <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
        <div class="dropdown" v-if="optionsTemp">
            <!-- Dropdown Input -->
            <input class="form-input" :class="{ error: error }" autocomplete="off"
                   :name="name"
                   @focus="showOptions()"
                   @blur="exit()"
                   @keyup="keyMonitor"
                   v-model="searchFilter"
                   :disabled="disabled"
                   :placeholder="placeholder" />

            <!-- Dropdown Menu -->
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
        <div v-if="error" class="form-error">{{ error }}</div>
    </div>

</template>
<!--
            <Dropdown
                :options="options"
                v-on:selected="validateSelection"
                v-on:filter="getDropdownValues"
                :disabled="false"
                placeholder="Please select an animal">
              </Dropdown>
              data() {
                return {
                    options: [
                        { name: "Cat", id: "cat" },
                        { name: "Dog", id: "dog" },
                        { name: "Elephant", id: "elephant" },
                        { name: "Girafe", id: "girafe" },
                        { name: "Snake", id: "snake" },
                        { name: "Spider", id: "spider" },
                        { name: "Unicorn", id: "unicorn" }
                    ],

                    selected: { name: null, id: null }
                };
            },

            methods: {
                validateSelection(selection) {
                    this.selected = selection;
                    console.log(selection.name + " has been selected");
                },

                getDropdownValues(keyword) {
                    console.log("You could refresh options by querying the API with " + keyword);
                }
            }
            -->
<script>
  export default {
    name: 'Dropdown',
    template: 'Dropdown',
    props: {
        id: {
            type: String,
            default() {
                return `text-input`
            },
        },
        error: String,
        label: String,
        api:null,
      name: {
        type: String,
        required: false,
        default: 'dropdown',
        note: 'Input name'
      },
      options: {
        type: Array,
        required: true,
        default: [],
        note: 'Options of dropdown. An array of options with id and name',
      },
      placeholder: {
        type: String,
        required: false,
        default: 'Please select an option',
        note: 'Placeholder of dropdown'
      },
      disabled: {
        type: Boolean,
        required: false,
        default: false,
        note: 'Disable the dropdown'
      },
    },
    data() {
      return {
        selected: {},
        optionsShown: false,
        searchFilter: '',
        optionsTemp:[]
      }
    },
    mounted() {
        this.optionsTemp = this.options;
    },
    created() {
      this.$emit('selected', this.selected);
    },
    computed: {
      filteredOptions() {
        const filtered = [];
        const regOption = new RegExp(this.searchFilter, 'ig');
        for (const option of this.optionsTemp) {
          if (this.searchFilter.length < 1 || option.name.match(regOption)){
            filtered.push(option);
          }
        }
        return filtered;
      }
    },
    methods: {
      selectOption(option) {
        this.selected = option;
        this.optionsShown = false;
        this.searchFilter = this.selected.name;
        this.$emit('selected', this.selected);
      },
      showOptions(){
        if (!this.disabled) {
          this.searchFilter = '';
          this.optionsShown = true;
        }
      },
      exit() {
        if (!this.selected.id) {
          this.selected = {};
          this.searchFilter = '';
        } else {
          this.searchFilter = this.selected.name;
        }
        this.$emit('selected', this.selected);
        this.optionsShown = false;
      },
      // Selecting when pressing Enter
      keyMonitor: function(event) {
        if (event.key === "Enter" && this.filteredOptions[0])
          this.selectOption(this.filteredOptions[0]);
      }
    },
    watch: {
      searchFilter() {
        if (this.filteredOptions.length === 0) {
          this.selected = {};
        } else {
          this.selected = this.filteredOptions[0];
        }
        this.$emit('filter', this.searchFilter);
        if(this.api !== null){
            let form = new MyForm({
                q:this.searchFilter
            });
            form.get(this.api).then((res) => {
               this.optionsTemp = res;
            })

        }
      }
    }
  };
</script>


<style lang="scss" scoped>
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
