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
        <b-col sm="12" md="4" xl="4" v-for="index in 6" :key="index">
          <project-component
            v-bind:project="{
              title: 'Sauver les pandas en Asie',
              description:
                'Description de mon projet. Ce texte peut parfois être super long. Ce texte peut parfois être super long. Ce texte peut parfois être super long.',
              isProjectInitiative: false,
            }"
            :reducedDisplay="true"
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
        <b-col sm="12" md="6" xl="6" v-for="index in 4" :key="index">
          <project-component
            v-bind:project="{
              title: 'Sauver les pandas en Asie',
              description:
                'Description de mon projet. Ce texte peut parfois être super long. Ce texte peut parfois être super long. Ce texte peut parfois être super long.',
              isProjectInitiative: true,
            }"
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
    const response: Response = await getProjects()
    if (response.ok) {
      const projectsPagingation: ProjectPaginationDTO = await response.json()
      console.log(projectsPagingation)
    } else {
      this.$swal({
        icon: 'error',
        title: "Une erreur s'est produite lors du chargement des projets",
        showConfirmButton: true,
      })
    }
    this.isLoading = false
    this.isLoadingProjects = false
  },
})
export default class HomeView extends Vue {}
</script>
