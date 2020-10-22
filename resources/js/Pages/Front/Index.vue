<template>
  <div class="container__flex__home">
    <div class="item__home">
      <div class="item__tracking">
        Tracking
      </div>
<!--       <div class="item__title">
        Поиск фотографий автомобилей
      </div> -->
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
    </div>
    <div class="item__background " />
  </div>
</template>
<script>

export default {
    metaInfo: { title: 'Tracking' },

    data() {
        return {
            form: {
                container: null,
                car: null,
            },
            loading: false,
            error_msg: {},
        }
    },
    watch: {
        '$page.errors': function (val) {
            this.error_msg = {}
            const objectArray = Object.entries(val)
            objectArray.forEach(([key, value]) => {
                //this.error_msg[key].push(value)
                this.$set(this.error_msg, key, value)

            })
        },

    },
    methods: {
        submit() {
            this.sending = true
            this.$inertia.post('search', this.form).then(() => this.sending = false)
        },
    },
}
</script>
<style src="../../../css/front/index.css">
</style>
