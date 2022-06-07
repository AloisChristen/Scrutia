<template>
  <div>
    <div class="content">

      <ProjectHeader
        :project-id="$route.params.project_id"
        :title="title"
        :description="description"
        :tagList="tagList"
        :likesCount="likesCount"
        :canBePromoted="projectCanBePromoted"
        :isLiked="isLiked"
        ideaActionActivated
      ></ProjectHeader>

      <ProjectDiscussion v-for="(d, index) in discussions"
                          :key="d.id"
                          :discussion-id="d.id"
                          :project-id="$route.params.project_id"
                          :versionId="latestVersionId"
                          :title="d.title"
                          :text="d.text"
                          :likeCount="d.likesCurrent"
                          :isUpvoted="d.isUpvoted"
                          :isDownvoted="d.isDownvoted"
                          :closed="index !== 0"
      />

    </div>
  </div>
</template>

<script lang="ts">
import ProjectHeader from "@/components/ProjectHeader.vue";
import ProjectDiscussion from "@/components/ProjectDiscussion.vue";
import {getProjectDetails} from "@/api/services/ProjectsService";
import router from "@/router";
export default {
  name: "IdeaDetailsView",
  components: {
    ProjectHeader,ProjectDiscussion
  },
  data() {
    return {
      discussions: [
        {id: 1, title: 'pourquoi la terre se rechauffe ?', author: 'fred dupont', likesCurrent: 200, text: "This rule reports the elements which have v-for and do not have v-bind:key with exception to custom components.", isUpvoted: false, isDownvoted: false},
        {id: 2, title: 'pourquoi la terre se rechauffe ?', author: 'fred dupont', likesCurrent: 200, text: "This rule reports the elements which have v-for and do not have v-bind:key with exception to custom components.", isUpvoted: true, isDownvoted: false},
        {id: 3, title: 'pourquoi la terre se rechauffe ?', author: 'fred dupont', likesCurrent: 200, text: "This rule reports the elements which have v-for and do not have v-bind:key with exception to custom components.", isUpvoted: false, isDownvoted: true}
      ],
      isLoaded: false,
      title: "",
      likesCount: 0,
      description: "",
      tagList: [],
      projectCanBePromoted: true,
      isLiked: false,
      latestVersionId: 0,
    }
  },
  async mounted() {
    const project_id_str = this.$route.params.project_id;
    const response: Response = await getProjectDetails(Number(project_id_str))
    if (response.ok) {
      const data = await response.json()
      console.log(data)
      if(data.status !== "idee"){
        await router.push({ name: 'initiativeDetails', params: { initiative_id: data.id } })
      }
      this.title = data.title
      this.likesCount = data.upvotes
      this.isLiked = data.user_vote === 1
      this.description = data.last_description
      this.tagList = data.tags
      this.latestVersionId = data.latestVersionId
    }
    this.isLoaded = true
  }
}

</script>
