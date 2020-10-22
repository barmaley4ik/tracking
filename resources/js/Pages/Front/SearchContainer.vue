<template>
  <div class="container__flex__car">
    <div class="item__container">
      <div class="item__row">
        <div class="item__tracking">
          Tracking
        </div>
        <div class="item__tracking__image">
          <inertia-link href="/" tabindex="-1">
            Back to home
          </inertia-link>
          <div class="track__image" />
        </div>
      </div>
      <div class="item__row item__row__row row__padding">
        <div class="item__number__car">
          {{ container.name }}
          <span class="row__carname"></span>
        </div>
      </div>
      <!-- /* */ -->
      <div class="item__row item__row__border item__foto">
        <div class="item__number__car row__stockname">
          Loading
          <div class="row__count">{{ container.image_download.length }}</div>
        </div>
        <div class="item__number__car row__stockaction" @click="image_download_visible = !image_download_visible">
          {{ image_download_visible ? 'Collapse' : 'Expand' }}
        </div>
      </div>
      <div v-show="image_download_visible">
        <div class="cars__car">
          <template v-for="(image_download, image_download_index ) in container.image_download">
            <div :key="`image_download-`+image_download_index" class="item__car__car">
              <div class="item__car__image__car">
                <a :href="`/download`+ image_download">
                  <img :src="image_download" :alt="container.name">
                </a>
              </div>
            </div>
          </template>
        </div>
        <div class="item__row__d item__row__download">
          <template v-if="container.image_download.length">
            <a :href="`/download/`+ container.name+  '/image_download'">
               Download all
            </a>
          </template>
          <template v-else>
             Download all
          </template> 
          <div class="row__count row__count__mod">({{ container.image_download.length }})</div>
        </div>
      </div>
      <div class="item__row item__row__border item__foto">
        <div class="item__number__car row__stockname">
          Unloading
          <div class="row__count">{{ container.image_upload.length }}</div>
        </div>
        <div class="item__number__car row__stockaction" @click="image_upload_visible = !image_upload_visible">
          {{ image_upload_visible ? 'Collapse' : 'Expand' }}
        </div>
      </div>
      <div v-show="image_upload_visible">
        <div class="cars__car">
          <template v-for="(image_upload, image_upload_index ) in container.image_upload">
            <div :key="`image_upload-`+image_upload_index" class="item__car__car">
              <div class="item__car__image__car">
                <a :href="`/download`+ image_upload">
                  <img :src="image_upload" :alt="container.name">
                </a>                
              </div>
            </div>
          </template>
        </div>
        <div class="item__row item__row__download" >
          <template v-if="container.image_upload.length">
            <a :href="`/download/`+ container.name+  '/image_upload'">
              Download all
            </a>
          </template>
          <template v-else>
            Download all
          </template> 
          <div class="row__count row__count__mod">({{ container.image_upload.length }})</div>
        </div> 
      </div>
      <form @submit.prevent="submit">
        <div class="item__form">
          <div class="input__container">
            <label class="form-label">CONTAINER</label>
            <input v-model.lazy.trim="form.container" :disabled="form.vin ? true : false" class="form-input" :class="{ error__input: error_msg.container }">
            <div v-if="error_msg.container" class="error">{{ error_msg.container[0] }}</div>
          </div>
          <div class="input__container">
            <label class="form-label">VIN</label>
            <input v-model.lazy.trim="form.vin" :disabled="form.container ? true : false" class="form-input" :class="{ error__input: error_msg.vin }">
            <label class="form-label-comment">Full or 6 digit enter</label>
            <div v-if="error_msg.vin" class="error">{{ error_msg.vin[0] }}</div>
          </div>
          <button :disabled="loading" class="item__button" type="submit">
            SEARCH
            <div v-if="loading" class="btn-spinner mr-2" />
          </button>
          <div v-if="error_msg.error_empty" class="error">{{ error_msg.error_empty[0] }}</div>
        </div>
      </form>
      <div class="help__text">
        Search car photos by VIN number or container number
      </div>
    </div>
  </div>
</template>

<script>
export default {
    metaInfo: { title: 'Search container' },
    props:{
        container: Object,
        car: Object,
    },
    data() {
        return {
            form: {
                container: null,
                vin: null,
            },
            loading: false,
            error_msg: {},
            image_download_visible: true,
            image_upload_visible: false,
        }
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
    methods: {
        submit() {
            this.sending = true
            this.$inertia.post('/search', this.form).then(() => this.sending = false)
        },
    },
}
</script>
<style src="../../../css/front/car.css">
</style>
