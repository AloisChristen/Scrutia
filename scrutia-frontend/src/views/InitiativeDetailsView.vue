<template>
  <div class="content" v-if="isLoaded">
    <!-- initiativeDetails/:initiative_id -->
    <ProjectHeader
      :projectId="project.id"
      :project="project"
      idea-action-activated
    ></ProjectHeader>

    <!-- Block Tabs Default Style -->
    <b-tabs
      class="block"
      nav-class="nav-tabs-block"
      content-class="block-content"
      v-model="tabIndex"
    >
      <b-tab title="Révisions" active>
            <ProjectDiscussion v-for="v in project.versions"
                               :key="v.id"
                               :project-id="project.id"
                               :versionId="v.id"
                               modeAffichageVersion
                               :version="v"
                               style="margin-bottom: 16px"
                               :canReply="isLoggedIn"
                               :show-link="false"
                               :is-logged-in="isLoggedIn"
                               closed
            />

      </b-tab>
      <b-tab title="Fils de discussion" >
        <div v-for="version in project.versions" :key="version.number">
          <ProjectDiscussion v-for="d in version.questions"
                             :key="d.id"
                             :discussion-id="d.id"
                             :projectId="project.id"
                             :versionId="latestVersionId"
                             :question="d"
                             closed
                             :canReply="isLoggedIn"
                             :show-link="false"
                             :is-logged-in="isLoggedIn"
          />
        </div>
      </b-tab>
    </b-tabs>

    <b-row cols="12" v-if="tabIndex===0 && isLoggedIn && userIsAuthorOfProject"> <!-- revision de text -->
      <b-form @submit.prevent class="mb-12" style="width: 100%">
        <b-form-group
          label="Réviser le texte"
          label-for="contribution">
          <b-form-textarea id="contribution"
                           class="col-12"
                           placeholder="Réviser le texte"
                        v-model="inputContribution" autocomplete="off"></b-form-textarea>
        </b-form-group>
        <b-form-group>
          <b-button type="submit" variant="primary" style="float: right" v-on:click="traiterInputContribution()">Envoyer</b-button>
        </b-form-group>
      </b-form>
    </b-row>
    <b-row v-if="tabIndex===1 && isLoggedIn"> <!-- poser une question -->
      <b-form @submit.prevent class="mb-12" style="width: 100%">
        <b-form-group
          label="Poser une question"
          label-for="contribution">
          <b-form-textarea id="contribution"
                           class="col-12"
                           placeholder="Poser une question"
                           v-model="inputContribution" autocomplete="off"></b-form-textarea>
        </b-form-group>
        <b-form-group>
          <b-button type="submit" variant="primary" style="float: right" v-on:click="traiterInputContribution()">Envoyer</b-button>
        </b-form-group>
      </b-form>
    </b-row>

  </div>
</template>

<script lang="ts">
import { getProjectDetails } from '@/api/services/ProjectsService'
import ProjectHeader from '@/components/ProjectHeader.vue'
import ProjectDiscussion from '@/components/ProjectDiscussion.vue'

import router from '@/router'
import {addVersion} from "@/api/services/VersionsService";
import {QuestionStoreDTO, VersionStore} from "@/typings/scrutia-types";
import {addQuestion} from "@/api/services/QuestionsService";

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
      username: '',
      message: '',
      tabIndex: 0,
      inputContribution : "",
      userIsAuthorOfProject: true
    }
  },
  methods: {
    async traiterInputContribution() {
      console.log("tabIndex", this.tabIndex);

      let response: Response
      if(this.tabIndex === 0) { // revision de text
        let versionNew = {
          project_id: this.project.id,
          description: this.inputContribution,
        } as VersionStore;
        response = await addVersion(versionNew)
      } else { // question
        let question = {
          project_id: this.project.id,
          version_number: this.project.versions[this.project.versions.length -1].number, // last version
          title: this.inputContribution,
          description: this.inputContribution
        } as QuestionStoreDTO
        response = await addQuestion(question)
      }

      if (response.ok) {
        this.$swal({
          title: 'Merci',
          text: 'Votre question a été enregistrée',
          icon: 'success',
          confirmButtonText: 'Ok'
        })
      }
      else {
        console.log(await response.json())
        this.$swal({
          title: 'Erreur',
          text: "Une erreur est survenue lors de l'enregistrement",
          type: 'error',
          confirmButtonText: 'OK',
        })
      }
      //location.reload()

    },
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
        this.userIsAuthorOfProject = false
      } else {
        this.userIsAuthorOfProject = true
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
