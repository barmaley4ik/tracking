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
          {{ car.vin }}
          <span class="row__carname">{{ car.name }}</span>
        </div>
<!--         <div class="item__number__car number__mob">
          <div class="item__tracking__image track__mod">
            <inertia-link :href="`/search/container/`+container.name">
              {{ container.name }}
            </inertia-link>
            <div class="track__image image__mod" />
          </div>
          <span class="row__contname">Номер контейнера</span>
        </div> -->
      </div>
      <!-- /* */ -->
      <div class="item__row item__row__border item__foto">
        <div class="item__number__car row__stockname">
          Auction
          <div class="row__count">{{ car.image_auction.length }}</div>
        </div>
        <div class="item__number__car row__stockaction" @click="image_auction_visible = !image_auction_visible">
          {{ image_auction_visible ? 'Collapse' : 'Expand' }}
        </div>
      </div>
      <div v-show="image_auction_visible">
        <div class="cars__car">
          <template v-for="(image_auction, image_auction_index ) in car.image_auction">
            <div :key="`image_auction-`+image_auction_index" class="item__car__car">
              <div class="item__car__image__car">
                <a :href="`/download`+ image_auction">
                  <img :src="image_auction" :alt="car.name">
                </a>
              </div>
            </div>
          </template>
        </div>
        <div class="item__row__d item__row__download">
          <template v-if="car.image_auction.length">
            <a :href="`/download/`+ car.vin+  '/image_auction'">
              Download all
            </a>
          </template>
          <template v-else>
            Download all
          </template> 
          <div class="row__count row__count__mod">({{ car.image_auction.length }})</div>
        </div>
      </div>
      <div class="item__row item__row__border item__foto">
        <div class="item__number__car row__stockname">
          Pick Up
          <div class="row__count">{{ car.image_taken_auction.length }}</div>
        </div>
        <div class="item__number__car row__stockaction" @click="image_taken_auction_visible = !image_taken_auction_visible">
          {{ image_taken_auction_visible ? 'Collapse' : 'Expand' }}
        </div>
      </div>
      <div v-show="image_taken_auction_visible">
        <div class="cars__car">
          <template v-for="(image_taken_auction, image_taken_auction_index ) in car.image_taken_auction">
            <div :key="`image_taken_auction-`+image_taken_auction_index" class="item__car__car">
              <div class="item__car__image__car">
                <a :href="`/download`+ image_taken_auction">
                  <img :src="image_taken_auction" :alt="car.name">
                </a>                
              </div>
            </div>
          </template>
        </div>
        <div class="item__row item__row__download" >
          <template v-if="car.image_taken_auction.length">
            <a :href="`/download/`+ car.vin+  '/image_taken_auction'">
              Download all
            </a>
          </template>
          <template v-else>
            Download all
          </template> 
          <div class="row__count row__count__mod">({{ car.image_taken_auction.length }})</div>
        </div>
      </div>
      <div class="item__row item__row__border item__foto">
        <div class="item__number__car row__stockname">
          Shipper
          <div class="row__count">{{ car.image_in_stock.length }}</div>
        </div>
        <div class="item__number__car row__stockaction" @click="image_in_stock_visible = !image_in_stock_visible">
          {{ image_in_stock_visible ? 'Collapse' : 'Expand' }}
        </div>
      </div>
      <div v-show="image_in_stock_visible">
        <div class="cars__car">
          <template v-for="(image_in_stock, image_in_stock_index ) in car.image_in_stock">
            <div :key="`image_in_stock-`+image_in_stock_index" class="item__car__car">
              <div class="item__car__image__car">
                <a :href="`/download`+ image_in_stock">
                  <img :src="image_in_stock" :alt="car.name">
                </a>                
              </div>
            </div>
          </template>
        </div>
        <div class="item__row item__row__download">
          <template v-if="car.image_in_stock.length">
            <a :href="`/download/`+ car.vin+  '/image_in_stock'">
              Download all
            </a>
          </template>
          <template v-else>
            Download all
          </template> 
          <div class="row__count row__count__mod">({{ car.image_in_stock.length }})</div>
        </div>
      </div>
      <div class="item__row item__row__border item__foto">
        <div class="item__number__car row__stockname">
          Consignee
          <div class="row__count">{{ car.image_delivered.length }}</div>
        </div>
        <div class="item__number__car row__stockaction" @click="image_delivered_visible = !image_delivered_visible">
          {{ image_delivered_visible ? 'Collapse' : 'Expand' }}
        </div>
      </div>
      <div v-show="image_delivered_visible">
        <div class="cars__car">
          <template v-for="(image_delivered, image_delivered_index ) in car.image_delivered">
            <div :key="`image_delivered-`+image_delivered_index" class="item__car__car">
              <div class="item__car__image__car">
                <a :href="`/download`+ image_delivered">
                  <img :src="image_delivered" :alt="car.name">
                </a>                 
              </div>
            </div>
          </template>
        </div>
        <div class="item__row item__row__download">
          <template v-if="car.image_delivered.length">
            <a :href="`/download/`+ car.vin+  '/image_delivered'">
              Download all
            </a>
          </template>
          <template v-else>
            Download all
          </template> 
          <div class="row__count row__count__mod">({{ car.image_delivered.length }})</div>
        </div>
      </div>
      <div class="item__row item__row__border item__foto">
        <div class="item__number__car row__stockname">
          Out
          <div class="row__count">{{ car.image_left_to_client.length }}</div>
        </div>
        <div class="item__number__car row__stockaction" @click="image_left_to_client_visible = !image_left_to_client_visible">
          {{ image_left_to_client_visible ? 'Collapse' : 'Expand' }}
        </div>
      </div>
      <div v-show="image_left_to_client_visible">
        <div class="cars__car">
          <template v-for="(image_left_to_client, image_left_to_client_index ) in car.image_left_to_client">
            <div :key="`image_left_to_client-`+image_left_to_client_index" class="item__car__car">
              <div class="item__car__image__car">
                <a :href="`/download`+ image_left_to_client">
                  <img :src="image_left_to_client" :alt="car.name">
                </a> 
              </div>
            </div>
          </template>
        </div>
        <div class="item__row item__row__download">
          <template v-if="car.image_left_to_client.length">
            <a :href="`/download/`+ car.vin+  '/image_left_to_client'">
              Download all
            </a>
          </template>
          <template v-else>
            Download all
          </template>          
          <div class="row__count row__count__mod">({{ car.image_left_to_client.length }})</div>
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
    metaInfo: { title: 'Search car' },
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
            image_auction_visible: true,
            image_taken_auction_visible: false,
            image_in_stock_visible: false,
            image_delivered_visible: false,
            image_left_to_client_visible: false,
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
