<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" href="/admin/cars">Cars</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>Creation
    </h1>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <div class="flex w-full mr-6 mb-6">
            <text-input v-model.trim="form.name" :errors="$page.errors.name" class="pb-4 w-full" label="Car" />
          </div>
          <div class="flex w-full mr-6 mb-6">
            <text-input v-model.trim="form.vin" :errors="$page.errors.vin" class="pb-4 w-full" label="VIN" />
          </div>
          <div class="flex w-full h-18 mr-6 mb-6"> 
            <vue-dropzone id="dropzone_zip" ref="myVueDropzoneZip" :options="dropzoneZipOptions" :use-custom-slot="true" @vdropzone-sending="sendingEventZip" @vdropzone-success="vsuccessZip">
              <span class="block text-grey">Drag and drop or <br>select file to load zip!</span> 
            </vue-dropzone>
            <div class="bg-white border-none w-full px-6 text-md flex flex-wrap justify-around items-center self-center mr-6 flex-col">
              <div class="w-full flex-1">
                <base-progress :percentage="progress" class="mx-2 mb-2 h-6"/>
                    <div class="text-center">{{progress ? progressText : 'Progress...'}} </div>
              </div>
<!--               <button class="w-full lg:w-40 mb-4 bg-indigo-600 h-10 rounded cursor-pointer text-white hover:bg-indigo-300" title="Start processing" >
                <icon name="checkmark" class="fill-current text-white w-6 h-6 inline" />
                Start processing
              </button>   -->           
            </div>                   
          </div>                   
          <div class="bg-white border-none w-full px-4 text-md flex flex-wrap justify-around items-center mr-6">
                <button v-for="type in typeImages" :key="`button2-`+type.name" class="mb-2 w-32 h-10 rounded p-2 text-white cursor-pointer hover:bg-indigo-300" :class="(imageField == type.name) ? 'bg-red-600' :'bg-indigo-600'" :value="type.name" @click.stop.prevent="imageField=type.name">{{ type.title }}</button>
          </div> 
          <button class="w-full lg:w-40 mb-4 bg-green-600 h-10 rounded cursor-pointer text-white" title="Add a photo" @click.prevent="addPhoto()">
            <svg class="fill-current text-white w-6 h-6 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"> <path d="M11 9V5H9v4H5v2h4v4h2v-4h4V9h-4zm-1 11a10 10 0 1 1 0-20 10 10 0 0 1 0 20z" /></svg>
            Add a photo
          </button> 
          <template v-for="(image, imageindex) in form.image_auction">
            <div v-show="imageField=='image_auction'" :key="`image_auction`+imageindex" class="flex w-full flex-wrap mr-2 justify-between items-center mb-8">
              <img :src="image ? image : noImage" class="h-16 object-contain">
              <div class="flex flex-col w-4/7 mr-2">
                <div class="pr-2 text-md">Image:</div>
                <div class="pt-2 text-sm w-5/7 break-all mx-4">{{ image ? image : 'Nothing selected' }}</div>
                <div v-if="$page.errors.image" class="form-error">{{ $page.errors.image[0] }}</div>
                <button title="Delete" :class="image ? 'pt-2 px-4 w-0' : 'invisible'" @click.stop.prevent="form.image_auction.splice(imageindex, 1)">
                  <icon name="trash" class="block w-4 h-4 fill-red-600" />
                </button>
              </div>                
              <button title="Select image" class="inline-flex bg-red-600 text-white w-14 h-10 rounded mt-6 cursor-pointer w-auto ml-5 pr-2 items-center" @click.stop.prevent="open = true; imageField='image_auction'; imageFieldIndex=imageindex">
                <svg class="fill-current text-white w-6 h-6 ml-2 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>
                ...
              </button>                 
            </div>
          </template> 
          <template v-for="(image, imageindex) in form.image_taken_auction">
            <div v-show="imageField=='image_taken_auction'" :key="`image_taken_auction`+imageindex" class="flex w-full flex-wrap justify-between items-center mb-8 mr-6">
              <img :src="image ? image : noImage" class="h-16 object-contain">
              <div class="flex flex-col w-4/7 mr-2">
                <div class="pr-2 text-md">Image:</div>
                <div class="pt-2 text-sm w-5/7 break-all mx-4">{{ image ? image : 'Nothing selected' }}</div>
                <div v-if="$page.errors.image" class="form-error">{{ $page.errors.image[0] }}</div>
                <button title="Delete" :class="image ? 'pt-2 px-4 w-0' : 'invisible'" @click.stop.prevent="form.image_taken_auction.splice(imageindex, 1)">
                  <icon name="trash" class="block w-4 h-4 fill-red-600" />
                </button>
              </div>                
              <button title="Select image" class="inline-flex bg-red-600 text-white w-14 h-10 rounded mt-6 cursor-pointer w-auto pr-2 items-center" @click.stop.prevent="open = true; imageField='image_taken_auction'; imageFieldIndex=imageindex">
                <svg class="fill-current text-white w-6 h-6 ml-2 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>
                ...
              </button>                 
            </div>
          </template>
          <template v-for="(image, imageindex) in form.image_in_stock">
            <div v-show="imageField=='image_in_stock'" :key="`image_in_stock`+imageindex" class="flex w-full flex-wrap justify-between items-center mb-8 mr-6">
              <img :src="image ? image : noImage" class="h-16 object-contain">
              <div class="flex flex-col w-4/7 mr-2">
                <div class="pr-2 text-md">Image:</div>
                <div class="pt-2 text-sm w-5/7 break-all mx-4">{{ image ? image : 'Nothing selected' }}</div>
                <div v-if="$page.errors.image" class="form-error">{{ $page.errors.image[0] }}</div>
                <button title="Delete" :class="image ? 'pt-2 px-4 w-0' : 'invisible'" @click.stop.prevent="form.image_in_stock.splice(imageindex, 1)">
                  <icon name="trash" class="block w-4 h-4 fill-red-600" />
                </button>
              </div>                
              <button title="Select image" class="inline-flex bg-red-600 text-white w-14 h-10 rounded mt-6 cursor-pointer w-auto pr-2 items-center" @click.stop.prevent="open = true; imageField='image_in_stock'; imageFieldIndex=imageindex">
                <svg class="fill-current text-white w-6 h-6 ml-2 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>
                ...
              </button>                 
            </div>
          </template>
          <template v-for="(image, imageindex) in form.image_delivered">
            <div v-show="imageField=='image_delivered'" :key="`image_delivered`+imageindex" class="flex w-full flex-wrap justify-between items-center mb-8 mr-6">
              <img :src="image ? image : noImage" class="h-16 object-contain">
              <div class="flex flex-col w-4/7 mr-2">
                <div class="pr-2 text-md">Image:</div>
                <div class="pt-2 text-sm w-5/7 break-all mx-4">{{ image ? image : 'Nothing selected' }}</div>
                <div v-if="$page.errors.image" class="form-error">{{ $page.errors.image[0] }}</div>
                <button title="Delete" :class="image ? 'pt-2 px-4 w-0' : 'invisible'" @click.stop.prevent="form.image_delivered.splice(imageindex, 1)">
                  <icon name="trash" class="block w-4 h-4 fill-red-600" />
                </button>
              </div>                
              <button title="Select image" class="inline-flex bg-red-600 text-white w-14 h-10 rounded mt-6 cursor-pointer w-auto pr-2 items-center" @click.stop.prevent="open = true; imageField='image_delivered'; imageFieldIndex=imageindex">
                <svg class="fill-current text-white w-6 h-6 ml-2 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>
                ...
              </button>                 
            </div>
          </template>
          <template v-for="(image, imageindex) in form.image_left_to_client">
            <div v-show="imageField=='image_left_to_client'" :key="`image_left_to_client`+imageindex" class="flex w-full flex-wrap justify-between items-center mb-8 mr-6">
              <img :src="image ? image : noImage" class="h-16 object-contain">
              <div class="flex flex-col w-4/7 mr-2">
                <div class="pr-2 text-md">Image:</div>
                <div class="pt-2 text-sm w-5/7 break-all mx-4">{{ image ? image : 'Nothing selected' }}</div>
                <div v-if="$page.errors.image" class="form-error">{{ $page.errors.image[0] }}</div>
                <button title="Delete" :class="image ? 'pt-2 px-4 w-0' : 'invisible'" @click.stop.prevent="form.image_left_to_client.splice(imageindex, 1)">
                  <icon name="trash" class="block w-4 h-4 fill-red-600" />
                </button>
              </div>                
              <button title="Select image" class="inline-flex bg-red-600 text-white w-14 h-10 rounded mt-6 cursor-pointer w-auto pr-2 items-center" @click.stop.prevent="open = true; imageField='image_left_to_client'; imageFieldIndex=imageindex">
                <svg class="fill-current text-white w-6 h-6 ml-2 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path d="M0 4c0-1.1.9-2 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm11 9l-3-3-6 6h16l-5-5-2 2zm4-4a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" /></svg>
                ...
              </button>                 
            </div>
          </template>                                 
          <div class="py-4 bg-gray-100 border-t border-gray-200 flex w-full justify-end items-center">
            <loading-button :loading="sending" class="w-32 btn-indigo text-white hover:bg-red-600 focus:bg-red-600 justify-center" type="submit">Save</loading-button>
          </div>
        </div>
      </form>
    </div>
    <modal :showing="open" @close="open = false; showDropzone = false">
      <div>
        <h1 class="text-xl font-bold text-gray-900">Select image</h1>
        <nav class="text-black font-bold my-8" aria-label="Breadcrumb">
          <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
              <icon name="home" class="flex-shrink-0 w-6 h-6" @click.native="deleteFromPatch(-1)" />
              <icon name="cheveron-right" class="flex-shrink-0 w-5 h-5" @click="deleteFromPatch(-1)" />
            </li>
            <li v-for="(breadcrum, index) in fullPatch" :key="breadcrum" class="flex items-center">
              <span v-if="index !== fullPatch.length - 1" class="cursor-pointer" @click="deleteFromPatch(index)">{{ breadcrum }}</span>
              <span v-else class="text-gray-500 aria-current:page cursor-default">{{ breadcrum }}</span>
              <svg v-if="index !== fullPatch.length - 1" class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" /></svg>
            </li>
          </ol>
        </nav>
        <!-- show showDropzone -->
        <vue-dropzone v-show="showDropzone" id="dropzone" ref="myVueDropzone" :options="dropzoneOptions" :use-custom-slot="true" @vdropzone-sending="sendingEvent" @vdropzone-success="vsuccess">
          <dropzone class="h-8 text-grey mr-2 z-49" />
          <span class="block text-grey">Drag and drop to load content! <br> ... or click to select a file</span>
        </vue-dropzone>
        <div class="flex text-center flex-wrap pb-10 overflow-y-auto h-sm">
          <folder v-for="(folder, index) in folders" v-show="index = folders.length" :key="folder.name" :name="folder.name" @click.native="addToPatch(folder.name)" />
          <file v-for="(file, index) in files" v-show="index = files.length" :key="file.name" :name="file.name" :source="file.path" @click.native="selectFile(file.path)" />
        </div>
        <div class="mt-6 mb-6 flex flex-wrap">
          <template v-for="(link, key) in links">
            <div v-if="link.url === null" :key="key" class="mr-1 mb-1 px-4 py-3 text-sm border rounded text-gray-400" :class="{ 'ml-auto': link.label === 'Next' }">{{ link.label }}</div>
            <span v-else :key="key" class="mr-1 mb-1 px-4 py-3 text-sm border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-white': link.active, 'ml-auto': link.label === 'Next' }" @click="onPage(link.label)">{{ link.label }}</span>
          </template>
        </div>
      </div>
      <div class="flex justify-end">
        <button class="justify-end bg-indigo-600 text-white h-10 px-4 py-2 text-sm tracking-wide font-bold rounded" @click="open = false; showDropzone = false ">
          Close
        </button>
        <button class="inline-flex items-center justify-end bg-indigo-600 text-white ml-5 px-4 py-2 text-sm tracking-wide font-bold rounded" @click="showDropzone = !showDropzone">
          <dropzone class="fill-white w-4 h-4 mr-2" />
          Upload
        </button>
      </div>
    </modal>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import TextInput from '@/Shared/TextInput'
import Icon from '@/Shared/Icon'
import BaseProgress from '@/Shared/BaseProgress'
import Modal from '@/Shared/Modal'
import Dropzone from '@/Shared/Dropzone'
import Folder from '@/Shared/Folder'
import File from '@/Shared/File'
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
export default {
  metaInfo: {
    title: 'Create car',
  },
  layout: Layout,
  components: {
    LoadingButton,
    TextInput,
    Icon,
    BaseProgress,
    Modal,
    Dropzone,
    Folder,
    File,
    vueDropzone: vue2Dropzone,
  },
  remember: {
    data: ['form'],
  },
  props:{
    
  },
  data() {
    return {
      sending: false,
      form: {
        name: null,
        vin: null,
        image_auction:[],
        image_taken_auction:[],
        image_in_stock:[],
        image_delivered:[],
        image_left_to_client:[],
      },
      progress: 0,
      progressText: '',
      zipFilename: '',
      typeImages: [{title:'Auction', name: 'image_auction'},{title:'Pick Up', name: 'image_taken_auction'},{title:'Shipper', name: 'image_in_stock'},{title:'Consignee', name: 'image_delivered'},{title:'Out', name: 'image_left_to_client'}],   
      imageField: 'image_auction',
      imageFieldIndex: '',
      error_msg: {},
      noImage: this.$page.image.noImagePath,
      homeDirectory: 'cars',
      folders: Array,
      files: Array,
      items: Object,
      links: [],
      linkItems: {},
      fullPatch: [],
      currentPage: 0,
      open: false,
      showDropzone: false,
      dropzoneOptions: {
        url: '/admin/upload',
        thumbnailWidth: 150,
        maxFilesize: 15,
        addRemoveLinks: true,
        acceptedFiles: '.jpeg,.jpg,.png',
        headers: {
          // eslint-disable-next-line no-undef
          'X-CSRF-TOKEN': this.$page.auth.user.token,
        },
        paramName: 'sFilename',

        /* dictDefaultMessage: "<dropzone class='fill-white w-4 h-4 mr-2'> </dropzone>UPLOAD ME"  */

      },
      dropzoneZipOptions: {
        url: '/admin/upload/zip',
        maxFilesize: 120,
        addRemoveLinks: true,
        acceptedFiles: '.zip',
        uploadMultiple: false,
        timeout: 540000,
        maxFiles: 1,
        headers: {
          // eslint-disable-next-line no-undef
          'X-CSRF-TOKEN': this.$page.auth.user.token,
        },
        paramName: 'zipFilename',

        /* dictDefaultMessage: "<dropzone class='fill-white w-4 h-4 mr-2'> </dropzone>UPLOAD ME"  */

      },      
    }
  },
  computed: {
    disabledUp() {
      if (this.fullPatch.length == 0) {
        return false
      }
      return true
    },
  },
  watch: {
    '$page.errors': function (val) {
      this.error_msg = {}
      const objectArray = Object.entries(val)
      objectArray.forEach(([key, value]) => {
        this.$set(this.error_msg, key, value)
      })
    },
  },

  created() {
    this.fullPatch.push(this.homeDirectory)
    this.loadFiles()
  },

  methods: {
    submit() {
      this.sending = true
      this.$inertia.post('/admin/cars', this.form)
        .then(() => (this.sending = false))
    },

    addPhoto(){
      (this.form[this.imageField]).push('')
    },    

    selectFile(fName) {
      this.form[this.imageField][this.imageFieldIndex] = fName
      this.open = false
      this.showDropzone = false
    },
    onPage(numberPage) {
      var nPage = 0
      if (numberPage == 'Prev') {
        nPage = (--this.currentPage)
      } else if (numberPage == 'Next') {
        nPage = (++this.currentPage)
      } else {
        nPage = numberPage
      }
      var qString = ''
      qString = 'directory=' + this.fullPatch.join('/') + '&page=' +nPage
      this.axios.get('/admin/fm/index?'+qString).then(response => {

        this.items = response.data.items
        this.items.data = Object.values(this.items.data)

        var aFolders = []
        var aFiles = []

        this.items.data.forEach(function (item) {
          if (item.type === 'folder') {
            aFolders.push(item)
          } else {
            aFiles.push(item)
          }
        })
        this.folders = aFolders
        this.files = aFiles
        this.links = []
        var total = this.items.total
        var perPage = this.items.per_page
        var path = this.items.path
        var adPath = (this.fullPatch.join('/') == '') ? '?page=' : '?directory=' + this.fullPatch.join('/') + '&page='
        var pages = Math.ceil(total / perPage)
        var prevPageUrl = this.items.prev_page_url
        var nextPageUrl = this.items.next_page_url
        var lastPage = this.items.last_page

        this.currentPage = this.items.current_page
        if (pages > 1) {
          this.linkItems.url = (this.currentPage > 1) ? (prevPageUrl) : null
          this.linkItems.label = 'Prev'
          this.linkItems.active = false
          this.links.push(this.linkItems)
          this.linkItems = {}
          for (var i = 0; i < pages; i++) {
            this.linkItems = {}
            this.linkItems.url = (path + adPath + (i + 1))
            this.linkItems.label = (i + 1)
            this.linkItems.active = (this.currentPage == (i + 1))
            this.links.push(this.linkItems)
          }
          this.linkItems = {}
          this.linkItems.url = (this.currentPage < lastPage) ? (nextPageUrl) : null
          this.linkItems.label = 'Next'
          this.linkItems.active = false
          this.links.push(this.linkItems)
        }
      })
    },
    deleteFromPatch(index) {
      if (this.fullPatch[index] != this.homeDirectory && this.fullPatch[index] != undefined) {
        while (index < this.fullPatch.length - 1) {
          this.fullPatch.pop()
        }
      }
      this.loadFiles()
    },
    addToPatch(fname) {
      this.fullPatch.push(fname)
      this.loadFiles()
    },
    sendingEventZip(file, xhr, formData) {
      var directory = 'zips'
      formData.append('directory', directory)
      //console.log('ZIP sendingEventZip')
    },
    async vsuccessZip(file, success) {
      const image_fields = ['auction', 'pick_up', 'shipper', 'consignee', 'out']

      let timerId1 = setTimeout(await this.progressing(1, 11, 'Zip file upload success!'), 100)
      clearInterval(timerId1) 

      const unzipResponse = await this.unzip(file.name)
      let timerId2 = setTimeout( await this.progressing(10, 31, 'Zip file extract!'), 500)
      clearInterval(timerId2) 

      const optimizeResponse = await this.optimize(unzipResponse.data.zipFiles)
      let timerId3 = setTimeout( await this.progressing(30, 71, 'Image files are optimized!'), 500)
      clearInterval(timerId3) 

      let timerId4 = setTimeout( await this.progressing(70, 91, 'Image files added'), 1000)
      clearInterval(timerId4) 

      var arrFilename = file.name.split('.')
      if(image_fields.includes(arrFilename[0])) {
          switch(arrFilename[0]) {
            case 'auction':  
            optimizeResponse.data.files.forEach(element => (this.form.image_auction).push(element))
            this.imageField = 'image_auction'
            break

            case 'pick_up':  
            optimizeResponse.data.files.forEach(element => (this.form.image_taken_auction).push(element))
            this.imageField = 'image_taken_auction'
            break

            case 'shipper':  
            optimizeResponse.data.files.forEach(element => (this.form.image_in_stock).push(element))
            this.imageField = 'image_in_stock'
            break

            case 'consignee':  
            optimizeResponse.data.files.forEach(element => (this.form.image_delivered).push(element))
            this.imageField = 'image_delivered'
            break

            case 'out':  
            optimizeResponse.data.files.forEach(element => (this.form.image_left_to_client).push(element))
            this.imageField = 'image_left_to_client'
            break                        
          }
      }
      const deleteResponse = await this.deleteZip(file.name)
      let timerId5 = setTimeout( await this.progressing(90, 101, 'Temp zip file delete. Done!'), 500)
      clearInterval(timerId5)       

    }, 
    async unzip(file) {
      return this.axios.get('/admin/fm/unzip'+'?zipFilename='+file);
    }, 
/*     async loadOptimize(files) {
      return this.axios.get('/admin/fm/optimize'+'?listFiles='+files);
    },  */
    async optimize(files) {
      return this.axios.get('/admin/fm/optimize'+'?listFiles='+files);
    },
    async deleteZip(file) {
      return this.axios.get('/admin/fm/delete'+'?filename='+file);
    },    
    async progressing(start, n, text) {
      while (start < n) { // выводит 0, затем 1, затем 2
        this.progress = start
        start++;
        
      }
      this.sleep(100)
      this.progressText = text
    },
    sleep(millis) {
        var t = (new Date()).getTime();
        var i = 0;
        while (((new Date()).getTime() - t) < millis) {
            i++;
        }
    },
    sendingEvent(file, xhr, formData) {
      //тут типа проверить и добавить имя папки
      var directory = ''
      directory = this.fullPatch.join('/')
      formData.append('directory', directory)
    },
    vsuccess( /* file, success */ ) {
      this.loadFiles()
      //console.log('upload finishe')
    },
    loadFiles() {
      var qString = ''
      qString = 'directory=' + this.fullPatch.join('/')
      this.axios.get('/admin/fm/index?' + qString).then(response => {
        this.items = response.data.items
        var aFolders = []
        var aFiles = []
        //console.log(this.items.data);
        this.items.data.forEach(function (item) {
          if (item.type === 'folder') {
            aFolders.push(item)
          } else {
            aFiles.push(item)
          }
        })
        this.folders = aFolders
        this.files = aFiles
        this.links = []
        var total = this.items.total
        var perPage = this.items.per_page
        var path = this.items.path
        var adPath = (this.fullPatch.join('/') == '') ? '?page=' : '?directory=' + this.fullPatch.join('/') + '&page='
        var pages = Math.ceil(total / perPage)
        this.currentPage = this.items.current_page
        var prevPageUrl = this.items.prev_page_url
        var nextPageUrl = this.items.next_page_url
        var lastPage = this.items.last_page

        if (pages > 1) {
          this.linkItems.url = (this.currentPage > 1) ? prevPageUrl : null
          this.linkItems.label = 'Prev'
          this.linkItems.active = false
          this.links.push(this.linkItems)
          this.linkItems = {}
          for (var i = 0; i < pages; i++) {
            this.linkItems = {}
            this.linkItems.url = (path + adPath + (i + 1))
            this.linkItems.label = (i + 1)
            this.linkItems.active = (this.currentPage == (i + 1))
            this.links.push(this.linkItems)
          }
          this.linkItems = {}
          this.linkItems.url = (this.currentPage < lastPage) ? nextPageUrl : null
          this.linkItems.label = 'Next'
          this.linkItems.active = false
          this.links.push(this.linkItems)
        }
      })

    },
  },
}
</script>

<style>
.dropzone .dz-preview .dz-image img {
    display: block;
    max-height: 120px;
}
.dropzone.dz-clickable {
    cursor: pointer;
    height: 200px;
    overflow-y: overlay;
}
  #dropzone_zip {
    line-height: 20px;
    width: 100%;
    border-width: 1px;
    border-radius: 0.25rem;
    max-height: 180px;
    padding: 10px 10px;
    text-align: center;
    z-index:0;
  }
  #dropzone_zip .dz-remove {
    border: none;
    bottom: 5px;
    padding: 5px;
    color: #e53e3e;
    background-color: white;
  }
</style>
