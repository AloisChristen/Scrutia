<template>
  <div>
    <div class="content">

      <ProjectHeader
        title="sauver les pandas du feu"
        :tagList="['d', 'asd', 'env']"
        ideaActionActivated
        canBePromoted
        ></ProjectHeader>

      <ProjectDiscussion/>
      <ProjectDiscussion/>
      <ProjectDiscussion/>
      <ProjectDiscussion/>

    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator'
import ProjectHeader from "@/components/ProjectHeader.vue";
import ProjectDiscussion from "@/components/ProjectDiscussion.vue";
@Component({
  name: 'IdeaDetailsView',
  components: {ProjectHeader, ProjectDiscussion},
})
export default class IdeaDetailsView extends Vue {}
</script>
