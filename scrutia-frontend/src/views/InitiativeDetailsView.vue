<template>
  <div class="content" v-if="isLoaded">
    <!-- initiativeDetails/:initiative_id -->
    <ProjectHeader
      :projectId="project.id"
      :title="project.title"
      :description="project.last_description"
      :tagList="project.tags"
      :likesCount="project.upvotes - project.downvotes"
      :isLiked="project.user_vote === 1"
    ></ProjectHeader>

    <!-- Block Tabs Default Style -->
    <b-tabs
      class="block"
      nav-class="nav-tabs-block"
      content-class="block-content"
    >
      <b-tab title="Révisions" active>
            <ProjectDiscussion v-for="(v, index) in project.versions"
                               :key="v.id"
                               :project-id="project.id"
                               :versionId="v.id"
                               :text="v.description"
                               :likeCount="v.upvotes - v.downvotes"
                               :isUpvoted="v.user_vote === 1"
                               :isDownvoted="v.user_vote === -1"
                               :closed="index !== 0"
                               modeRevision
                               :show-link="false"
                               :version="v"
                               style="margin-bottom: 16px"
                               :canReply="userCanPostQuestion"
                               :userForReply="username"
            />

      </b-tab>
      <b-tab title="Fils de discussion" active>
        <ProjectDiscussion v-for="(d, index) in project.questions"
                           :key="d.id"
                           :discussion-id="d.id"
                           :project-id="initiative_id"
                           :versionId="latestVersionId"
                           :title="d.title"
                           :text="d.text"
                           :likeCount="d.nb_upvotes - d.nb_downvotes"
                           :isUpvoted="d.user_vote === 1"
                           :isDownvoted="d.user_vote === -1"
                           :closed="index !== 0"
                           :canReply="isLoggedIn"
                           :userForReply="username"
        />
      </b-tab>
    </b-tabs>
    <b-row>
      <textarea
        class="col-12"
        :value="message"
        @input="message = $event.target.value"
        rows="10"
        cols="50"
      ></textarea>
      <input
        type="button"
        class="btn btn-primary"
        value="Réviser le texte"
        @click="postMessage"
      />

    </b-row>
  </div>
</template>

<script lang="ts">
import { getProjectDetails } from '@/api/services/ProjectsService'
import ProjectHeader from '@/components/ProjectHeader.vue'
import ProjectDiscussion from '@/components/ProjectDiscussion.vue'

import router from '@/router'

export default {
  name: 'initiativeDetails',
  components: {
    ProjectHeader,
    ProjectDiscussion,
  },
  data() {
    return {
      initiative_id: Number(this.$route.params.initiative_id),
      project: {},
      isLoaded: false,
      userCanPostQuestion: false,
      latestVersionId: 0,
      isLoggedIn: false,
      username: ''
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
    const response: Response = await getProjectDetails(this.initiative_id);
    if (response.ok) {
      const data = await response.json()
      if (data.status === 'idee') {
        await router.push({
          name: 'IdeaDetails',
          params: { project_id: this.initiative_id },
        })
      }
      this.project = data
      console.log('project', data)

      if (this.getUsername() !== data.author) {
        this.projectCanBePromoted = false
      }
      if(this.getUsername() !=='No user'){
        this.userCanPostQuestion = true;
        this.isLoggedIn = true;
        this.username = this.getUsername();
      }

      let latestVersionId = 0
      for(let v of this.project.versions){
        if(v.id > latestVersionId) {
          latestVersionId = v.id;
        }
      }
      this.latestVersionId = latestVersionId;
    }

    this.isLoaded = true
  },
}
</script>
