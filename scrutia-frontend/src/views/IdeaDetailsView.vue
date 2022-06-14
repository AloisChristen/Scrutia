<template>
  <div v-if="isLoaded">
    <div class="content">
      <!-- http://localhost:8080/project/1 -->
      <ProjectHeader
        :projectId="projectId"
        :canBePromoted="projectCanBePromoted"
        :project="projet_data"
        ideaActionActivated
      ></ProjectHeader>

      <ProjectDiscussion v-for="(d, index) in questions"
                          :key="d.id"
                          :discussion-id="d.id"
                          :projectId="projectId"
                          :versionId="latestVersionId"
                          :closed="index !== 0"
                          :question="d"
                          onlyThreeAnswer
                          :is-logged-in="isLoggedIn"
      />

      <div v-if="questions.length === 0">
        Il n'y a pas de question pour le moment...
      </div>

      <div v-if="canAskQuestion" style="width: 100%">
        <b-form @submit.prevent class="mb-5">
          <b-form-group label="Votre question" label-for="response">
            <b-form-input id="response" type="text" placeholder="Votre question" v-model="inputQuestion" autocomplete="off"></b-form-input>
          </b-form-group>
          <b-form-group>
            <b-button type="submit" variant="primary" style="float: right" v-on:click="creerQuestion()">Envoyer</b-button>
          </b-form-group>
        </b-form>
      </div>

    </div>
  </div>
</template>

<script lang="ts">
import ProjectHeader from "@/components/ProjectHeader.vue";
import ProjectDiscussion from "@/components/ProjectDiscussion.vue";
import {getProjectDetails} from "@/api/services/ProjectsService";
import router from "@/router";
import {addQuestion} from "@/api/services/QuestionsService";
import {QuestionStoreDTO} from "@/typings/scrutia-types";
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
      projet_data: {},
      canAskQuestion: false,
      inputQuestion: "",
      isLoggedIn: false,
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
      this.latestVersionId = data.versions[0].id;
      this.questions = data.versions[0].questions;

      this.projet_data = data;

      if(this.getUsername() !== data.author) {
        this.projectCanBePromoted = false;
      }
      if(this.getUsername() != 'No user'){
        this.canAskQuestion = true;
        this.isLoggedIn = true;
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
    async creerQuestion() {
      let req = {
        project_id: this.projet_data.id,
        version_number: this.projet_data.versions[0].number, // always first version as idea
        title: this.inputQuestion,
        description: this.inputQuestion,
      } as QuestionStoreDTO;
      const response: Response = await addQuestion(req);
      if(!response.ok){
        this.$swal({
          title: 'Erreur',
          text: "Une erreur est survenue lors de l'enregistrement de votre question, vous avez déjà epuisé votre quota de questions pour aurjourd'hui ou il faudrait gagner de la réputation",
          icon: 'error',
          confirmButtonText: 'Ok'
        })
        console.log(await response.json())
      } else {
        this.$swal({
          title: 'Merci',
          text: 'Votre question a été enregistrée',
          icon: 'success',
          confirmButtonText: 'Ok'
        })
        location.reload()
      }

    }
  }
}

</script>
