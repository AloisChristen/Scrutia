<template>
  <div>
    <div class="content">
      <h2 class="content-heading">Vos idées et projets favoris</h2>
      <b-row>
        <b-spinner
          variant="primary"
          label="Loading..."
          v-show="isLoading"
        ></b-spinner>
        <b-col sm="12" md="12" xl="12">
          <b-pagination
            v-model="current_page"
            :total-rows="nb_total_favorites"
            :per-page="per_page"
            v-show="!isLoading"
            align="center"
            @change="(newValue) => changePage(newValue)"
          ></b-pagination>
        </b-col>
        <b-col sm="12" md="12" xl="12">
          <p v-show="!isLoading && favorites.length === 0">
            Vous n'avez actuellement pas de favoris.
          </p>
          <b-row v-show="favorites.length > 0">
            <b-col
              sm="12"
              md="6"
              xl="4"
              v-for="favorite in favorites"
              v-bind:key="favorite.id"
              v-show="!isLoading"
            >
              <project-component
                v-bind:project="favorite"
                :reducedDisplay="true"
                :isProjectInitiative="favorite.status !== 'idee'"
              />
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="12" md="12" xl="12">
          <b-pagination
            v-model="current_page"
            :total-rows="nb_total_favorites"
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
  name: 'FavoritesView',
  components: {
    ProjectComponent,
  },
  data() {
    return {
      isLoading: true,
      favorites: [],
      nb_total_favorites: 0,
      per_page: 15,
      current_page: 0,
      last_page_url: '',
      next_page_url: '',
      prev_page_url: '',
    }
  },
  async created() {
    this.loadFavorites()
  },
  methods: {
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
export default class FavoritesView extends Vue {}
</script>
