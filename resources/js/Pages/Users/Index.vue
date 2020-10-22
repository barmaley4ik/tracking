<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">Пользователи</h1>
    <div class="mb-6 flex justify-between items-center">
      <search-filter v-model="form.search" class="w-full max-w-md mr-4" @reset="reset">
        <label class="block text-gray-700">Роли:</label>
        <select v-model="form.role" class="mt-1 w-full form-select">
          <option :value="null" />
          <option value="user">Сотрудник</option>
          <option value="owner">Администратор</option>
        </select>
        <label class="mt-4 block text-gray-700">Удаленные:</label>
        <select v-model="form.trashed" class="mt-1 w-full form-select">
          <option :value="null" />
          <option value="with">С удаленными</option>
          <option value="only">Только удаленные</option>
        </select>
      </search-filter>
      <inertia-link class="btn-indigo" href="/admin/users/create">
        <span>Создать</span>
      </inertia-link>
    </div>
    <div class="bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left text-sm font-bold uppercase">
          <th class="px-6 pt-6 pb-4">Пользователь</th>
          <th class="px-6 pt-6 pb-4">Email</th>
          <th class="px-6 pt-6 pb-4" colspan="2">Роль</th>
        </tr>
        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="'/admin/users/edit/' +user.id">
              {{ user.name }}
              <icon v-if="user.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-red-600 ml-2" />
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="'/admin/users/edit/' +user.id" tabindex="-1">
              {{ user.email }}
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="'/admin/users/edit/' +user.id" tabindex="-1">
              {{ user.owner ? 'Администратор' : 'Пользователь' }}
            </inertia-link>
          </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="'/admin/users/edit/' +user.id" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="users.length === 0">
          <td class="border-t px-6 py-4" colspan="4">Ничего не найдено.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import SearchFilter from '@/Shared/SearchFilter'
import throttle from 'lodash/throttle'

export default {
  metaInfo: { title: 'Пользователи' },
  layout: Layout,
  components: {
    Icon,
    SearchFilter,
  },
  props: {
    users: Array,
    filters: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        role: this.filters.role,
        trashed: this.filters.trashed,
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
        this.$inertia.replace(Object.keys(query).length ? ('/admin/users' + queryString) : '?remember=forget')
      }, 150),
      deep: true,
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>
