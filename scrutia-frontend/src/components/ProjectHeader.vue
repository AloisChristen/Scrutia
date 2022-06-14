<template>
<div>
  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="12">
      <div style="display: flex">
        <h1 class="font-w400">{{ data_project.title }}</h1>
        <div v-if="ideaActionActivated" style="margin-right: 0; margin-left: auto; display: flex; flex-direction: column">
          <div v-on:click="like_current()">
            <div v-if="data_project.user_vote === 1">
              <div style="display: flex">
                <i class="fa fa-fw fa-thumbs-up mr-1"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                ></i>
                <div v-if="data_project.upvotes === 0">
                  Soyez le premier à soutenir
                </div>
                <div v-if="data_project.upvotes === 1">
                  Vous êtes le premier à soutenir le projet
                </div>
                <div v-else>
                  {{ data_project.upvotes }} personnes soutiennent le projet
                </div>
              </div>
            </div> <!-- like -->
            <div v-else-if="data_project.user_vote === 0">
              <div style="display: flex">
                <i
                  v-bind:class="{'scrutia-clickable': isLoggedIn}"
                  class="fa fa-fw fa-thumbs-up mr-1"
                  style="color: lightgray"
                ></i>
                <div v-if="data_project.upvotes === 0">
                  Soyez le premier à soutenir
                </div>
                <div v-else-if="data_project.upvotes === 1">
                  {{ data_project.upvotes }} personne soutient le projet
                </div>
                <div v-else>
                  {{ data_project.upvotes }} personnes soutiennent le projet
                </div>
              </div>
            </div> <!-- neutral -->
            <div v-else>
              <div style="display: flex">
                <i
                  class="fa fa-fw fa-thumbs-down mr-1"
                  v-bind:class="{'scrutia-clickable': isLoggedIn}"
                ></i>
                <div v-if="data_project.upvotes === 0">
                  Soyez le premier à soutenir
                </div>
                <div v-else-if="data_project.upvotes === 1">
                  {{ data_project.upvotes }} personne soutient le projet
                </div>
                <div v-else>
                  {{ data_project.upvotes }} personnes soutiennent le projet
                </div>
              </div>
            </div> <!-- for dislike -->
          </div>
          <b-button v-if="canBePromotedAndIsOk"
                    block
                    v-b-tooltip.hover.nofade.left="'Il faut 50 upvotes pour pouvoir promouvoir'"
                    :disabled="data_project.upvotes < 50"
                    v-on:click="promote" style="margin-right: 0; margin-left: auto">
            promouvoir le projet
          </b-button>
        </div>
      </div>
    </b-col>
  </b-row>

  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="12">
      <b-badge v-for="tag in data_project.tags" :key="tag.id" :variant="getNextColor()" style="padding: 5px; margin: 2px" >
        {{ tag.title }}
      </b-badge>
    </b-col>
    <b-col cols="12">
      <p>
        {{data_project.last_description}}
      </p>
    </b-col>
  </b-row>
</div>
</template>

<script lang="ts">
import {likeProject, promoteProject} from "@/api/services/ProjectsService";

export default {
  name: "ProjectHeader",
  props: {
    projectId: {
      type: Number
    },
    ideaActionActivated: {
      type: Boolean,
      default: false,
    },
    canBePromoted: {
      type: Boolean,
      default: false
    },
    project: {
      type: Object,
    }
  },
  data() {
    return {
      data_project: this.project,
      canBePromotedAndIsOk: this.canBePromoted,
      isLoggedIn: this.$store.getters.isConnected
    }
  },
  methods: {
    getNextColor() {
      const colors = [
        'primary',
        'secondary',
        'success',
        'info',
        'warning',
        'danger',
        'dark',
      ]
      const index = Math.floor(Math.random() * colors.length)
      return colors[index]
    },

    //- --- -- idea actions -- -- - - -- -
    async like_current() {
      //do nothing if user is not logged
      if (!this.isLoggedIn) {
        return
      }
      const current = this.project.user_vote;
      if(current == 1){ // current=like -> new =dislike
        this.data_project.user_vote = -1;
        this.data_project.upvotes -= 1;
      } else if(current == -1) { // current = neg -> neutral
        this.data_project.user_vote = 0;
      } else { // current neutral -> like
        this.data_project.user_vote = 1;
        this.data_project.upvotes += 1;
      }

      const response: Response = await likeProject(this.projectId, this.data_project.user_vote)
      if(!response.ok){
        // TODO CZR: checlk if user is logged -> redirect.
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite, impossible de soutenir le projet",
          showConfirmButton: true,
        })
      }
      this.$forceUpdate();
    },
    async promote() {
      const response: Response = await promoteProject(this.projectId);
      if(!response.ok){
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite, impossible de promouvoir le projet",
          showConfirmButton: true,
        })
      }
      this.$forceUpdate();
    }
  }
}
</script>

<style scoped>
  margin-right-15 {
    margin-right: 15px;
  }
  .scrutia-clickable {
    cursor: pointer;
  }
</style>

