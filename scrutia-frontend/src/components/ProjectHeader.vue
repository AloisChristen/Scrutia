<template>
<div>
  <b-row style="padding-left: 0" no-gutters>
    <b-col v-if="displayPicture">
      <img
        src="https://via.placeholder.com/120x120"
        alt="..."
        class="img-fluid"
      />
    </b-col>
    <b-col cols="12">
      <div style="display: flex">
        <h1 class="font-w400">{{ title }}</h1>
        <div v-if="ideaActionActivated" style="margin-right: 0; margin-left: auto; display: flex; flex-direction: column">
          <div v-on:click="like_current()">
            <div v-if="data_project_liked">
              <div style="display: flex">
                <i
                  class="fa fa-fw fa-thumbs-up mr-1"
                ></i>
                <div v-if="data_project_liked_count === 0">
                  Soyez le premier à soutenir
                </div>
                <div v-if="data_project_liked_count === 1">
                  Vous êtes le premier à soutenir le projet
                </div>
                <div v-else>
                  {{ data_project_liked_count }} personnes soutiennent le projet
                </div>
              </div>
            </div>
            <div v-else>
              <div style="display: flex">
                <i
                  class="fa fa-fw fa-thumbs-up mr-1"
                  style="color: lightgray"
                ></i>
                <div v-if="data_project_liked_count === 0">
                  Soyez le premier à soutenir
                </div>
                <div v-else-if="data_project_liked_count === 1">
                  {{ data_project_liked_count }} personne soutient le projet
                </div>
                <div v-else>
                  {{ data_project_liked_count }} personnes soutiennent le projet
                </div>
              </div>
            </div>
          </div>
          <b-button v-if="canBePromoted" v-on:click="promote" style="margin-right: 0; margin-left: auto">
            promouvoir le projet
          </b-button>
        </div>
      </div>
    </b-col>
  </b-row>

  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="12">
      <b-badge v-for="tag in tagList" :key="tag.id" :variant="getNextColor()" style="padding: 5px; margin: 2px" >
        {{ tag.title }}
      </b-badge>
    </b-col>
    <b-col cols="12">
      <p>
        {{description}}
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
    title: {
      type: String,
      default: 'Ceci est un titre'
    },
    tagList: {
      type: Array,
      required: true
    },
    description: {
      type: String,
      default: "Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in         sit varius lorem sit metus mi."
    },
    displayPicture: {
      type: Boolean,
      default: false,
    },
    ideaActionActivated: {
      type: Boolean,
      default: false,
    },
    isLiked: {
      type: Boolean,
      default: false
    },
    likesCount: {
      type: Number,
      default: 311,
    },
    canBePromoted: {
      type: Boolean,
      default: false
    },
    versionId: {
      type: Number,
    }

  },
  data() {
    return {
      data_project_liked: this.isLiked,
      data_project_liked_count: this.likesCount
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
      console.log("liking project ", this.projectId);

      const response: Response = await likeProject(this.projectId, 1)
      if(!response.ok){
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite, impossible de soutenir le projet",
          showConfirmButton: true,
        })
        return
      }
      if (this.data_project_liked) {
        this.data_project_liked_count = this.data_project_liked_count - 1;
      } else {
        this.data_project_liked_count = this.data_project_liked_count + 1;
      }
      this.data_project_liked = !this.data_project_liked;
    },
    async promote() {
      console.log("promoting....");
      const response: Response = await promoteProject(this.projectId);
      if(!response.ok){
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite, impossible de promouvoir le projet",
          showConfirmButton: true,
        })
        return
      }
    }
  }
}
</script>

<style scoped>
  margin-right-15 {
    margin-right: 15px;
  }
</style>

