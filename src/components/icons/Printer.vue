<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'IconPrinter',
  props: {
    color: {
      type: String,
      default: '#fff'
    }
  }
})
</script>

<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    x="0px"
    y="0px"
    width="96"
    height="96"
    viewBox="0 0 172 172"
    style=" fill:#000000;"
  >
    <g
      fill="none"
      fill-rule="nonzero"
      stroke="none"
      stroke-width="1"
      stroke-linecap="butt"
      stroke-linejoin="miter"
      stroke-miterlimit="10"
      stroke-dasharray=""
      stroke-dashoffset="0"
      font-family="none"
      font-weight="none"
      font-size="none"
      text-anchor="none"
      style="mix-blend-mode: normal"
    >
      <path
        d="M0,172v-172h172v172z"
        fill="none"
      />
      <g :fill="color">
        <path d="M50.16667,14.33333c-3.956,0 -7.16667,3.21067 -7.16667,7.16667v14.33333c0,3.956 3.21067,7.16667 7.16667,7.16667h71.66667c3.956,0 7.16667,-3.21067 7.16667,-7.16667v-14.33333c0,-3.956 -3.21067,-7.16667 -7.16667,-7.16667zM35.83333,57.33333c-11.825,0 -21.5,9.675 -21.5,21.5v28.66667c0,7.91917 6.41417,14.33333 14.33333,14.33333h14.33333v14.33333c0,7.91917 6.41417,14.33333 14.33333,14.33333h57.33333c7.91917,0 14.33333,-6.41417 14.33333,-14.33333v-14.33333h14.33333c7.91917,0 14.33333,-6.41417 14.33333,-14.33333v-28.66667c0,-11.825 -9.675,-21.5 -21.5,-21.5zM136.16667,71.66667c4.3,0 7.16667,2.86667 7.16667,7.16667c0,4.3 -2.86667,7.16667 -7.16667,7.16667c-4.3,0 -7.16667,-2.86667 -7.16667,-7.16667c0,-4.3 2.86667,-7.16667 7.16667,-7.16667zM60.91667,100.33333h50.16667c1.978,0 3.58333,1.60533 3.58333,3.58333v28.66667c0,1.978 -1.60533,3.58333 -3.58333,3.58333h-50.16667c-1.978,0 -3.58333,-1.60533 -3.58333,-3.58333v-28.66667c0,-1.978 1.60533,-3.58333 3.58333,-3.58333z" />
      </g>
    </g>
  </svg>
</template>
