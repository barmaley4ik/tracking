<template>
  <transition name="fade">
    <div
      v-if="showing"
      class="fixed inset-0 w-full h-screen flex items-center justify-center bg-semi-75"
      @click.self="close"
    >
      <div class="relative w-full max-w-2xl bg-white shadow-my rounded-lg p-8 bg-gray-100 z-100">
        <slot />
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    showing: {
      required: true,
      type: Boolean,
    },
  },
  watch: {
    showing(value) {
      if (value) {
        return document.querySelector('body').classList.add('overflow-hidden')
      }

      document.querySelector('body').classList.remove('overflow-hidden')
    },
  },
  methods: {
    close() {
      this.$emit('close')
    },
  },
}
</script>

<style scope>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.4s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>