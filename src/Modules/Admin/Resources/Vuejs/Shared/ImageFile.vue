<template>
    <div :class="{
            'flex items-center  w-full': true,
            'justify-center' : fileUploaded.length == 0
        }">
        <template v-if="label">
            <div :class="$attrs.class">
                <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
                <input
                    class=" rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none"
                    type="file"
                    @change="changeImage" />
                <div v-if="error" class="form-error">{{ error }}</div>
            </div>
        </template>
        <template v-else>
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
        </template>
        <div class="flex pr-3" v-if="fileUploaded.length > 0">
            <div :class="{
                    'flex items-center w-32 h-32 justify-center relative mr-2': true,
                    'border-2 border-green-400 border-dashed rounded-lg' : item.active == 1
                }" v-for="(item, key) in fileUploaded" >
                <div class="absolute group  w-full h-full" >
                    <div class="absolute hidden group-hover:flex div-icon w-full h-full flex">
                       <!--  <svg v-if="multiple" @click="active(key)" class="w-3 h-3 text-green-500 cursor-pointer mr-1.5 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                        </svg> -->
                        <svg  @click="del(key)" class="w-7 h-7 text-red-500 bg-white p-1 cursor-pointer dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                            <path d="M17 4h-4V2a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v2H1a1 1 0 0 0 0 2h1v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V6h1a1 1 0 1 0 0-2ZM7 2h4v2H7V2Zm1 14a1 1 0 1 1-2 0V8a1 1 0 0 1 2 0v8Zm4 0a1 1 0 0 1-2 0V8a1 1 0 0 1 2 0v8Z"/>
                        </svg>
                      </div>
                  </div>
                  <div v-if="item.active == 1" class="bg-green-400 text-white absolute bottom-4 h-6 w-full flex items-center justify-center">Ảnh chính</div>
                <img class="object-scale-down w-32 h-32" :src="item.thumb">
            </div>
        </div>

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
        url: {
            type : String,
            default : route('upload')
        },
        multiple : Boolean,
        error: String,
        key: Number,
        label:{
            type : String,
            default : null
        }
    })
    const emit = defineEmits(['update:data'])

    onMounted(() => {
        fileUploaded.value = props.data;
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
                apiPost(props.url, form)
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
        background: #e7e3e3a3;
    }
</style>
