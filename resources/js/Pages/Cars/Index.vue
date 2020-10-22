<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">CARS</h1>
    <div class="mb-6 flex justify-between items-center">

      <div class="flex items-center">
        <div class="flex w-full bg-white shadow rounded">
          <input v-model="form.search" class="relative w-full px-6 py-3 rounded-r focus:shadow-outline" autocomplete="off" type="text" name="search" placeholder="Search..." @input="$emit('input', $event.target.value)">
        </div>
        <button class="ml-3 text-sm text-gray-500 hover:text-gray-700 focus:text-indigo-500" type="button" @click="reset">Reset</button>
      </div>
      <inertia-link class="btn-indigo" href="/admin/cars/create">
        <span>Create</span>
      </inertia-link>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left text-sm font-bold uppercase">
          <th class="px-6 pt-6 pb-4">#</th>
          <th class="px-6 pt-6 pb-4">Car</th>
          <th class="px-6 pt-6 pb-4">Vin</th>
          <th class="px-6 pt-6 pb-4">Creator, date</th>
          <th class="px-6 pt-6 pb-4">Editor, date</th>
          <th class="px-6 pt-6 pb-4" />
        </tr>
        <tr v-for="(car, index) in cars.data" :key="car.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t px-6 py-4 w-2">
            {{ index +1 }}
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="'/admin/cars/edit/' +car.id">
              {{ car.name }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="'/admin/cars/edit/' +car.id">
              {{ car.vin }}
            </inertia-link>
          </td>          
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="'/admin/cars/edit/' +car.id" tabindex="-1">
              {{ car.creator }} - {{ car.created_at }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="'/admin/cars/edit/' +car.id" tabindex="-1">
              {{ car.updater }} - {{ car.updated_at }}
            </inertia-link>
          </td>
          <td class="flex pt-3 justify-end border-t">
            <button
              class="px-4"
              @click.prevent="showModal(car.id)"
            >
              <icon name="trash" class="block w-6 h-6 fill-red-600" />
            </button>
          </td>
        </tr>
        <tr v-if="cars.length === 0">
          <td class="border-t px-6 py-4" colspan="8">Nothing found.</td>
        </tr>
      </table>
    </div>
    <pagination :links="cars.links" />
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import Pagination from '@/Shared/Pagination'
import throttle from 'lodash/throttle'

export default {
  metaInfo: { title: 'Cars' },
  layout: Layout,
  components: {
    Icon,
    Pagination,
  },
  props: {
    cars: Object,
    filters: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
      },
    }
  },
  watch: {
    form: {
      handler: throttle(function() {
        let query = pickBy(this.form)
        let queryString = ''

        for (const [key, value] of Object.entries(query)) {
          if (queryString.length>0) {
            queryString += `&${key}=${value}`
          } else {
            queryString += `?${key}=${value}`
          }
        }
        this.$inertia.replace(Object.keys(query).length ? ('/admin/cars' + queryString) : '?remember=forget')
      }, 150),
      deep: true,
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    showModal(id){
      this.$swal({
        title: 'Do you really want to delete the entry?',
        type: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        showLoaderOnConfirm: false,
        animation: false,
      }).then((result) => {
        if(result.value) {
          this.$inertia.delete('/admin/cars/' +id)
        }
      })
    },
  },
}
</script>
<style>
.swal2-title {
  font-size: 1.4em;
  }
</style>
