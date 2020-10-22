<template>
  <div>
    <div>
      <inertia-link class="flex items-center group py-3" href="/admin/containers">
        <icon name="view-column" class="w-4 h-4 mr-2" :class="isUrl('admin/containers') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('admin/containers') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Containers</div>
      </inertia-link>
    </div>
    <div>
      <inertia-link class="flex items-center group py-3" href="/admin/cars">
        <icon name="travel-car" class="w-4 h-4 mr-2" :class="isUrl('admin/cars') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('admin/cars') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Cars</div>
      </inertia-link>
    </div>    
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
export default {
  components: {
    Icon,
  },
  props: {
    url: String,
  },
  data() {
    return {
      showMenu: false,
    }
  },
  methods: {
    isUrl(...urls) {
      if (urls[0] === '') {
        return this.url === ''
      }

      return urls.filter(url => this.url.startsWith(url)).length
    },
    hideDropdownMenus() {
      this.showMenu = false
    },
  },
}
</script>
