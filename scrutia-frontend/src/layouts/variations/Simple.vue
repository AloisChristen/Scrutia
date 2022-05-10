<template>
  <base-layout></base-layout>
</template>

<script>
import BaseLayout from '../Base'

export default {
  name: 'LayoutSimple',
  components: {
    BaseLayout,
  },
  created() {
    this.$store.commit('setLayout', {
      header: false,
    })
    this.$store.commit('mainContent', { mode: 'full' })
  },
}
</script>
