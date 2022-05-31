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

<script>
import ProjectHeader from "@/components/ProjectHeader.vue";
import ProjectDiscussion from "@/components/ProjectDiscussion.vue";
export default {
  name: "IdeaDetailsView",
  components: {
    ProjectHeader,ProjectDiscussion
  },
  data() {
    return {
      discussions: [
        {title: 'pourquoi la terre se rechauffe ?', author: 'fred dupont', likesCurrent: 200}
      ]
    }
  },

}

</script>
