<template>
  <div>
    <div class="content">
      <h2 class="content-heading">Idées les plus tendances...</h2>
      <b-spinner
        variant="primary"
        label="Loading..."
        v-show="isLoading"
      ></b-spinner>
      <b-row v-show="!isLoading">
        <b-col sm="12" md="4" xl="4" v-for="idea in ideas" v-bind:key="idea.id">
          <project-component
            v-bind:project="idea"
            :reducedDisplay="true"
            :isProjectInitiative="false"
          />
        </b-col>
      </b-row>
      <p>
        <b-link
          class="link-fx"
          href="/browseIdeas"
          style="float: right; margin-bottom: 10px; margin-top: -10px"
          v-show="!isLoading"
          >et de nombreuses autres...</b-link
        >
      </p>
      <h2 class="content-heading">Projets d'initiative les plus actifs...</h2>
      <b-spinner
        variant="primary"
        label="Loading..."
        v-show="isLoadingProjects"
      ></b-spinner>
      <b-row v-show="!isLoadingProjects">
        <b-col
          sm="12"
          md="6"
          xl="6"
          v-for="project in projects"
          v-bind:key="project.id"
        >
          <project-component
            v-bind:project="project"
            :reducedDisplay="true"
            :isProjectInitiative="true"
          />
        </b-col>
      </b-row>
      <p>
        <b-link
          class="link-fx"
          href="browseInitiatives"
          style="float: right; margin-bottom: 10px; margin-top: -10px"
          v-show="!isLoadingProjects"
          >et de nombreux autres...</b-link
        >
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import ProjectComponent from '../components/ProjectComponent.vue'
import { Component, Vue } from 'vue-property-decorator'
import { getProjects } from '@/api/services/ProjectsService'
import { getIdeas } from '@/api/services/ProjectsService'
import { ProjectPaginationDTO } from '@/typings/scrutia-types'

@Component({
  name: 'HomeView',
  components: {
    ProjectComponent,
  },
  data() {
    return {
      nbIdeas: 0,
      nbProjects: 0,
      ideas: [],
      projects: [],
      isLoading: true,
      isLoadingProjects: true,
    }
  },
  async created() {
    this.loadIdeas()
    this.loadProjects()
  },
  methods: {
    loadIdeas: async function () {
      this.$data.isLoading = true
      const response: Response = await getIdeas()
      if (response.ok) {
        const projectsPagingation: ProjectPaginationDTO = await response.json()
        for (let i = 0; i < projectsPagingation.data.length && i < 6; i++)
          this.$data.ideas.push(projectsPagingation.data[i])
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des idées",
          showConfirmButton: true,
        })
      }
      this.$data.isLoading = false
    },
    loadProjects: async function () {
      this.$data.isLoadingProjects = true
      const response: Response = await getProjects()
      if (response.ok) {
        const projectsPagingation: ProjectPaginationDTO = await response.json()
        for (let i = 0; i < projectsPagingation.data.length && i < 4; i++)
          this.$data.projects.push(projectsPagingation.data[i])
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des projets",
          showConfirmButton: true,
        })
      }
      this.$data.isLoadingProjects = false
    },
  },
})
export default class HomeView extends Vue {}
</script>
