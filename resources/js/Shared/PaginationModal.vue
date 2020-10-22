<template>
  <div class="mt-6 -mb-1 flex flex-wrap">
    <template v-for="(link, key) in links">
      <div v-if="link.url === null" :key="key" class="mr-1 mb-1 px-4 py-3 text-sm border rounded text-gray-400" :class="{ 'ml-auto': link.label === 'Вперед' }">{{ link.label }}</div>
      <span v-else :key="key" class="mr-1 mb-1 px-4 py-3 text-sm border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500" :class="{ 'bg-white': link.active, 'ml-auto': link.label === 'Вперед' }" @click="onPage(link.label)">{{ link.label }}</span>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    links: Array,
    url: String,
  },

methods: {
  onPage(numberPage){
    console.log(numberPage +' url:' + this.url + `${this.form.first_name} ${this.form.last_name}`);
  } 
},
}
</script>
