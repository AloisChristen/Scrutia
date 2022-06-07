<template>
  <div class="content" v-if="isLoaded">
    <!-- initiativeDetails/:initiative_id -->
    <ProjectHeader
      :projectId="projectId"
      :title="title"
      :description="description"
      :tagList="tagList"
      :likesCount="likesCount"
      :canBePromoted="projectCanBePromoted"
      :isLiked="isLiked"
      :versionId="latestVersionId"
    ></ProjectHeader>

    <!-- Block Tabs Default Style -->
    <b-tabs
      class="block"
      nav-class="nav-tabs-block"
      content-class="block-content"
    >
      <b-tab title="Révisions" active>
        <!--
            <ProjectDiscussion v-for="(d, index) in projetDiscussions"
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
        -->
      </b-tab>
      <b-tab title="Fils de discussion" active>
        <!--
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
        -->
      </b-tab>
    </b-tabs>
  </div>
</template>

<script lang="ts">
import {getProjectDetails} from "@/api/services/ProjectsService";
import ProjectHeader from "@/components/ProjectHeader.vue";

import router from "@/router";

export default {
  name: 'initiativeDetails',
  components: {
    ProjectHeader
  },
  data() {
    return {
      initiative_id: this.$route.params.initiative_id,
      revisions: [],
      discussions: [],
      project: {},
      isLoaded: false,
    }
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
  },
  async mounted() {
    const response: Response = await getProjectDetails(Number(this.initiative_id));
    if (response.ok) {
      const data = await response.json();
      console.log(data);
      if(data.status === "idee"){
        await router.push({ name: 'IdeaDetails', params: { project_id: this.initiative_id } });
      }
      this.projectId = data.id;
      this.title = data.title;
      this.likesCount = data.upvotes;
      this.isLiked = data.user_vote === 1;
      this.description = data.last_description;
      this.tagList = data.tags;

      this.latestVersionId = data.latestVersionId;


      if(this.getUsername() !== data.author) {
        this.projectCanBePromoted = false;
      }

    }

    this.isLoaded = true
  },
}
</script>
