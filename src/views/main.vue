<script lang="ts">
import { defineComponent, ref, onMounted, onUnmounted } from 'vue'

export default defineComponent({
  name: 'ViewMain',
  setup () {
    const msg = ref('hello')

    let interval: number

    onMounted(() => {
      interval = setInterval(() => {
        msg.value = Date.now().toString(16)
      }, 2_000)
    })

    onUnmounted(() => clearInterval(interval))

    return {
      msg
    }
  }
})
</script>

<template>
  <h1>{{ msg }}</h1>
</template>
