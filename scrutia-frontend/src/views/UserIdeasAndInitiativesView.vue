<template>
  <div>
    <div class="content">
      <h2 class="content-heading">Vos idées et initiatives</h2>
      <b-row>
        <b-spinner
          variant="primary"
          label="Loading..."
          v-show="isLoading"
        ></b-spinner>
        <b-col sm="12" md="12" xl="12" v-show="projects.length > 0">
          <b-pagination
            v-model="current_page"
            :total-rows="nb_total_projects"
            :per-page="per_page"
            v-show="!isLoading"
            align="center"
            @change="(newValue) => changePage(newValue)"
          ></b-pagination>
        </b-col>
        <b-col sm="12" md="12" xl="12">
          <p v-show="!isLoading && projects.length === 0">
            Vous n'avez actuellement pas d'initia ni d'idées.
            <br />
            <router-link :to="`/browseIdeas`">
              <em>Regarder les initiatives des autres</em>
            </router-link>
            <br />
            <router-link :to="`/addIdea`">
              <em>Créer ma propre idée</em>
            </router-link>
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
                :isProjectInitiative="project.status !== 'idee'"
              />
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="12" md="12" xl="12" v-show="projects.length > 0">
          <b-pagination
            v-model="current_page"
            :total-rows="nb_total_projects"
            :per-page="per_page"
            v-show="!isLoading"
            align="center"
            @change="(page) => loadFavorites(page)"
          ></b-pagination>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script lang="ts">
import ProjectComponent from '../components/ProjectComponent.vue'
import { getFavorites } from '@/api/services/FavoritesService'
import { Component, Vue } from 'vue-property-decorator'

@Component({
  name: 'UserIdeasAndInitiativesView',
  components: {
    ProjectComponent,
  },
  data() {
    return {
      isLoading: true,
      projects: [],
      nb_total_projects: 0,
      per_page: 15,
      current_page: 0,
      last_page_url: '',
      next_page_url: '',
      prev_page_url: '',
    }
  },
  async created() {
    if (this.checkUser()) {
      this.loadFavorites()
    }
  },
  methods: {
    checkUser: function () {
      if (this.$store.getters.isConnected) {
        return true
      } else {
        this.$router.push('/auth/signin')
        return false
      }
    },
    loadFavorites: async function (page: number = 1) {
      this.$data.isLoading = true
      let resp = await getFavorites(page)
      if (resp.ok) {
        let body = await resp.json()
        console.log(body)
        this.$data.favorites = body.data
        this.$data.nb_total_favorites = body.total
        this.$data.per_page = body.per_page
        this.$data.current_page = body.current_page
        this.$data.last_page_url = body.last_page_url
        this.$data.next_page_url = body.next_page_url
        this.$data.prev_page_url = body.prev_page_url
        this.$data.isLoading = false
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement de vos favoris",
          showConfirmButton: true,
        })
      }
    },
  },
})
export default class UserIdeasAndInitiativesView extends Vue {}
</script>
