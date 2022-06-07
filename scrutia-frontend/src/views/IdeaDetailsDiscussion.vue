<template>
  <div>
    <div class="content" v-if="isLoaded">
      <!-- http://localhost:8080/project/1/discussion/0 -->
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

      <ProjectDiscussion
                         :key="question.id"
                         :discussion-id="question.id"
                         :title="question.title"
                         :date="question.created_at"
                         :projectId="projectId"
                         :versionId="latestVersionId"
                         :likeCount="question.nb_upvotes - question.nb_downvotes"
                         :isUpvoted="question.user_vote == 1"
                         :isDownvoted="question.user_vote == -1"
                         :answers="question.answers"
                         :author="question.user_id"
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
export default {
  name: "IdeaDetailsDiscussion",
  components: {
    ProjectHeader,ProjectDiscussion
  },
  data() {
    return {
      projectId: 0,
      title: "",
      description: "",
      tagList: [],
      likesCount: 0,
      projectCanBePromoted: false,
      isLiked: false,
      latestVersionId: 0,
      question: {},
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
      console.log(data)
      this.projectId = data.id;
      this.title = data.title;
      this.likesCount = data.upvotes;
      this.isLiked = data.user_vote === 1;
      this.description = data.last_description;
      this.tagList = data.tags;

      this.latestVersionId = data.latestVersionId;
      console.log(data.questions)
      if(data.questions != undefined || data.questions.length !== 0){
        let questions = data.versions[0].questions;
        this.question = questions.filter((x: { id: number; }) => x.id === Number(question_id_str))[0]
      } else {
        this.question = this.getFakeQuestion();
      }
      this.username = this.getUsername();
      if(this.username !== 'No user'){
        this.isLoggedIn = true;
      }
    }
    this.isLoaded = true
  },
  methods: {
    getFakeQuestion() {
      return {
        "id": 0,
        "title": "string",
        "user_id": "string",
        "created_at": "2022-06-07T10:14:47.867Z",
        "updated_at": "2022-06-07T10:14:47.867Z",
        "nb_upvotes": 0,
        "nb_downvotes": 0,
        "user_vote": -1,
        "answers": [
        {
          "id": 0,
          "description": "string",
          "user_id": "string",
          "nb_upvotes": 0,
          "nb_downvotes": 0,
          "user_vote": -1,
          "created_at": "2022-06-07T10:14:47.867Z",
          "updated_at": "2022-06-07T10:14:47.867Z"
        },
        {
          "id": 1,
          "description": "string",
          "user_id": "string",
          "nb_upvotes": 0,
          "nb_downvotes": 0,
          "user_vote": -1,
          "created_at": "2022-06-07T10:14:47.867Z",
          "updated_at": "2022-06-07T10:14:47.867Z"
        }
      ]
      }
    },
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
