<template>
  <div>
    <div class="content">
      <h2 class="content-heading">Idées les plus tendances...</h2>
      <b-spinner
        variant="primary"
        label="Loading..."
        v-show="$store.getters.isLoadingIdeas"
      ></b-spinner>
      <b-row v-show="!$store.getters.isLoadingIdeas">
        <b-col
          sm="12"
          md="4"
          xl="4"
          v-for="idea in $store.state.trendings.ideas"
          v-bind:key="idea.id"
        >
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
          href="/browseIdeas?type=ideas"
          style="float: right; margin-bottom: 10px; margin-top: -10px"
          v-show="!$store.getters.isLoadingIdeas"
          >parmi {{ $store.getters.nbIdeas }} idées...</b-link
        >
      </p>
      <h2 class="content-heading">Projets d'initiative les plus actifs...</h2>
      <b-spinner
        variant="primary"
        label="Loading..."
        v-show="$store.getters.isLoadingProjects"
      ></b-spinner>
      <b-row v-show="!$store.getters.isLoadingProjects">
        <b-col
          sm="12"
          md="6"
          xl="6"
          v-for="project in $store.state.trendings.projects"
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
          href="browseIdeas?type=initiatives"
          style="float: right; margin-bottom: 10px; margin-top: -10px"
          v-show="!$store.getters.isLoadingProjects"
          >parmi {{ $store.getters.nbProjects }} projets...</b-link
        >
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import ProjectComponent from '../components/ProjectComponent.vue'
import { Component, Vue } from 'vue-property-decorator'

@Component({
  name: 'HomeView',
  components: {
    ProjectComponent,
  },
  async created() {
    this.loadIdeas()
    this.loadProjects()
  },
  methods: {
    loadIdeas: async function () {
      if (this.$store.getters.nbIdeas === 0) this.$store.commit('loadIdeas')
    },
    loadProjects: async function () {
      if (this.$store.getters.nbProjects === 0)
        this.$store.commit('loadProjects')
    },
  },
})
export default class HomeView extends Vue {}
</script>
