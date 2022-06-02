<template>
  <div>
    <div class="content">
      <h1 class="content-heading">Recherche</h1>
      <b-spinner
        variant="primary"
        label="Loading..."
        v-show="isLoading"
      ></b-spinner>
      <p v-show="!isLoading && projects.length === 0">
        Aucune idée ou projet correspondant aux filtres n'existe...
      </p>
      <b-row v-show="projects.length > 0">
        <b-col
          sm="12"
          md="6"
          xl="4"
          v-for="project in projects"
          v-bind:key="project.id"
          v-show="!isLoading"
        >
          <project-component
            v-bind:project="project"
            :reducedDisplay="true"
            :isProjectInitiative="true"
          />
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script lang="ts">
import ProjectComponent from '../components/ProjectComponent.vue'
import { Component, Vue } from 'vue-property-decorator'
import { getProjectsWithFilters } from '@/api/services/ProjectsService'
import { ProjectPaginationDTO } from '@/typings/scrutia-types'

@Component({
  name: 'SearchView',
  data() {
    return {
      projects: [],
      isLoading: true,
    }
  },
  components: {
    ProjectComponent,
  },
  async created() {
    const url = new URL(window.location.href)
    const search = url.searchParams.get('question')
    this.isLoading = true
    const response: Response = await getProjectsWithFilters(
      null,
      search,
      null,
      null,
      null,
      1
    )
    if (response.ok) {
      const projectsPagingation: ProjectPaginationDTO = await response.json()
      this.projects = projectsPagingation.data
    } else {
      this.$swal({
        icon: 'error',
        title:
          "Une erreur s'est produite lors du chargement des projets et idées",
        showConfirmButton: true,
      })
    }
    this.isLoading = false
  },
})
export default class SearchView extends Vue {}
</script>
