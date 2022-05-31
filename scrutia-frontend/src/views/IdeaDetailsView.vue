<template>
  <div>
    <div class="content">

      <ProjectHeader
        title="sauver les pandas du feu"
        :tagList="['d', 'asd', 'env']"
        ideaActionActivated
        canBePromoted
        ></ProjectHeader>


    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import ProjectHeader from "@/components/ProjectHeader.vue";
@Component({
  name: 'IdeaDetailsView',
  components: {ProjectHeader},
})
export default class IdeaDetailsView extends Vue {}
</script>
