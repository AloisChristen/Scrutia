<template>
  <div>
    <div class="content" v-if="isLoaded">
      <!-- http://localhost:8080/project/1/discussion/0 -->
      <ProjectHeader
        :projectId="project.id"
        :canBePromoted="projectCanBePromoted"
        :project="project"
        ideaActionActivated
      ></ProjectHeader>

      <ProjectDiscussion
                         :discussion-id="question_current.id"
                         :question="question_current"
                         :projectId="project.id"
                         :can-reply="isLoggedIn"
                         :userForReply="username"
                         :showLink="false"
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
  name: "IdeaDetailsDiscussion",
  components: {
    ProjectHeader,
    ProjectDiscussion
  },
  data() {
    return {
      project: {},
      question_current: {},
      projectCanBePromoted: false,
      isLoaded: false,
      username: '',
      isLoggedIn: false,
    }
  },
  async mounted() {
    const project_id_str = this.$route.params.project_id;
    const question_id_str = this.$route.params.discussion_id;
    const response: Response = await getProjectDetails(Number(project_id_str))
    if (response.ok) {
      const data = await response.json()
      this.project = data;

      let allQuestions = this.project.versions[0].questions
      this.question_current = allQuestions.filter((x: { id: number; }) => x.id === Number(question_id_str))[0]

      this.username = this.getUsername();
      if(this.username !== 'No user'){
        this.isLoggedIn = true;
      }
    }
     else {
      // redirect to 404 page
      router.push({ name: '404' });
    }
    this.isLoaded = true
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

<style scoped>

</style>
