<template><div></div></template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'

@Component({
  name: 'SearchView',
  async created() {
    const url = new URL(window.location.href)
    const search = url.searchParams.get('question')
    this.$router.replace(`/browseIdeas?question=${search}`)
  },
})
export default class SearchView extends Vue {}
</script>
