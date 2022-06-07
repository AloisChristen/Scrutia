<template>
  <div v-if="isLoaded">
    <div class="content">
      <!-- http://localhost:8080/project/1 -->
      <ProjectHeader
        :projectId="projectId"
        :title="title"
        :description="description"
        :tagList="tagList"
        :likesCount="likesCount"
        :canBePromoted="projectCanBePromoted"
        :isLiked="isLiked"
        :versionId="latestVersionId"
        ideaActionActivated
      ></ProjectHeader>

      <ProjectDiscussion v-for="(d, index) in questions"
                          :key="d.id"
                          :discussion-id="d.id"
                          :title="d.title"
                          :date="d.created_at"
                          :projectId="projectId"
                          :versionId="latestVersionId"
                          :likeCount="d.nb_upvotes - d.nb_downvotes"
                          :isUpvoted="d.user_vote == 1"
                          :isDownvoted="d.user_vote == -1"
                          :closed="index !== 0"
                          :answers="d.answers"
                          :author="d.user_id"
                          onlyThreeAnswer
      />

      <div v-if="questions.length === 0">
        Il n'y a pas de question pour le moment...
      </div>

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
      questions: [],
      isLoaded: false,
      title: "",
      likesCount: 0,
      description: "",
      tagList: [],
      projectCanBePromoted: true,
      projectId: 0,
      isLiked: false,
      latestVersionId: 0,
    }
  },
  async mounted() {
    const project_id_str = this.$route.params.project_id;
    const response: Response = await getProjectDetails(Number(project_id_str));
    if (response.ok) {
      const data = await response.json();
      console.log(data);
      if(data.status !== "idee"){
        await router.push({ name: 'initiativeDetails', params: { initiative_id: data.id } });
      }
      this.projectId = data.id;
      this.title = data.title;
      this.likesCount = data.upvotes;
      this.isLiked = data.user_vote === 1;
      this.description = data.last_description;
      this.tagList = data.tags;

      this.latestVersionId = data.latestVersionId;

      this.questions = data.versions[0].questions;

      if(this.getUsername() !== data.author) {
        this.projectCanBePromoted = false;
      }

    }
    else {
      // redirect to 404 page
      router.push({ name: '404' });
    }
    this.isLoaded = true;
  },
  methods: {
    getUsername: function () {
      let user = this.$store.getters.currentUser
      if (user == undefined) {
        return 'No user'
      } else {
        return user.username
      }
    },
  }
}

</script>
