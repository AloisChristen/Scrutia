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
                          :date="d."
                          :projectId="projectId"
                          :versionId="latestVersionId"
                          :likeCount="d.nb_upvotes - d.nb_downvotes"
                          :isUpvoted="d.user_vote == 1"
                          :isDownvoted="d.user_vote == -1"
                          :closed="index !== 0"
                          :answers="d.answers"
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
      this.questions = this.getFakeQuestion().questions

      if(this.getUsername() !== data.author) {
        this.projectCanBePromoted = false;
      }

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

    getFakeQuestion() {
      return {

        "questions": [
          {
            "id": 0,
            "title": "titre question 1",
            "user_id": "user_question1",
            "created_at": "2022-06-07T10:14:47.867Z",
            "updated_at": "2022-06-07T10:14:47.867Z",
            "nb_upvotes": 2,
            "nb_downvotes": 1,
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
          },
          {
            "id": 1,
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
          },
          {
            "id": 2,
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
        ]

        }
    }
  }
}

</script>
