<template>
    <div :class="{
            'flex items-center  w-full': true,
            'justify-center' : fileUploaded.length == 0
        }">
        <div class="flex " v-if="fileUploaded.length > 0">
            <div :class="{
                    'flex items-center w-32 h-32 justify-center relative mr-2': true,
                    'border-2 border-green-400 border-dashed rounded-lg' : item.active == 1
                }" v-for="(item, key) in fileUploaded" >
                <div class="absolute group  w-full h-full" >
                    <div class="absolute hidden group-hover:flex div-icon w-full h-full flex justify-center items-center">
                        <BaseIcon v-if="multiple"
                            @click="active(key)"
                            :path="mdiProgressCheck"
                            class="flex-none cursor-pointer"
                            w="w-10"
                            :size="18"
                          />
                          <BaseIcon
                            @click="del(item,key)"
                            :path="mdiDeleteForever"
                            class="flex-none cursor-pointer"
                            w="w-10"
                            :size="18"
                          />
                      </div>
                  </div>
                  <div v-if="item.active == 1" class="bg-green-400 text-white absolute bottom-4 h-6 w-full flex items-center justify-center">Ảnh chính</div>
                <img class="object-scale-down w-32 h-32" :src="item.thumb">
            </div>
        </div>
        <label for="dropzone-file" class="flex flex-col items-center justify-center w-32 h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600" v-if="multiple">

            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Tải ảnh</span></p>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center"> PNG, JPG or JPEG</p>
            </div>
            <input id="dropzone-file" type="file" multiple class="hidden" @change="changeImage" />
        </label>
        <label :for="'dropzone-file'+key" class="flex flex-col items-center justify-center w-32 h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600" v-if="!multiple && fileUploaded.length < 1">

            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Tải ảnh</span></p>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center"> PNG, JPG or JPEG</p>
            </div>
            <input :id="'dropzone-file'+key" type="file" multiple class="hidden" @change="changeImage" />
        </label>
    </div>
    <slot></slot>
</template>
<script setup>
    import { ref, defineEmits, watch, onMounted, onUpdated } from 'vue'

    const files = ref([])
    const fileUploaded = ref([])
    const props = defineProps({
        data: {
            type: Array,
            default: () => []
        },

        multiple : Boolean,
        key: Number
    })
    const emit = defineEmits(['update:data'])

    onMounted(() => {
        fileUploaded.value = props.data
    })

    onUpdated(() => {
        fileUploaded.value = props.data
    })

    watch(() => fileUploaded.value,
      (currentValue) => {
         emit('update:data', fileUploaded.value)
      },
      {deep: true}
    );

    const uploadPercent = ref(0)

    const active = (key) => {
        fileUploaded.value.map((value, index) => {
            if(index == key){
                value.active = 1
            }else{
                value.active = 0
            }
        })
    }

    const del = (item,key) => {
        if (confirm("Bạn có muốn xóa ảnh này?")) {
            fileUploaded.value.splice(key, 1);
            let form = {}
            form.name = item.name
            apiPost(route('destroy'), form)
            .then(res => {

            })
            .catch(err => {

            })
        }
    }

    const progressEvent = progressEvent => {
      uploadPercent.value = Math.round(
        (progressEvent.loaded * 100) / progressEvent.total
      )
    }
    const changeImage= async(e) => {
            let file = e.target.files;
            for(let item of file){
                item.url = URL.createObjectURL(item);
                item.show = true;
                files.value.unshift(item);
            }
            console.log(files.value)
            uploadCdn();
    }
    const uploadCdn = async() => {
        let form;
        for(let item of files.value.reverse()){
            if(item.type == 'image/jpeg' || item.type == 'image/png' || item.type == 'image/jpg'){
                form = new FormData();
                form.append('file', item);
                apiPost(route('upload'), form)
                .then(res => {
                    console.log(res.data.error)
                    if(res.data.error){
                        alert(res.data.error)
                    }else{
                        res.data.forEach(function(currentValue, index, array) {
                            fileUploaded.value.unshift(currentValue)
                        });
                    }
                })
                .catch(err => {

                })
            }

        }
        files.value = [];
    }
    const hiddenFile = (lastModified) => {
        if(files.value.lastModified === lastModified){
            files.value.show = false;
        }
    }
</script>
<style lang='css'>
    .div-icon{
        background: #cccccca3;
    }
</style>
