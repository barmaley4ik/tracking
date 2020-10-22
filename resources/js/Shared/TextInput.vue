<template>
  <div>
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <input :id="id" ref="input" v-bind="$attrs" class="form-input" :class="{ error: er.length }" :type="type" :value="value" @input="$emit('input', $event.target.value)">
    <div v-if="er.length" class="form-error">{{ er[0] }}</div>
  </div>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        return `text-input-${this._uid}`
      },
    },
    upperCase: { type: Boolean, default: false },
    type: {
      type: String,
      default: 'text',
    },
    value: [String, Number],
    label: String,
    errors: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {  
      er: [],
    }
  },
  watch: {
    /*     value(v) {
      if (this.upperCase && v) {
        this.$emit('input', v.toUpperCase()) 
      }
    }, */
    errors: function (val) {
      this.er= []
      if (val[0] !==undefined) {  
        this.er.push(val[0])
      }
    },    
  },  
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
  },
}
</script>
