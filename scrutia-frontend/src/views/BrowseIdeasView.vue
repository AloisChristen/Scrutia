<template>
  <div>
    <div class="content">
      <h1 class="content-heading">Parcourir les idées</h1>
      <p>
        Laissez vous convaincre et apportez votre soutient aux bonnes idées.
      </p>
      <b-spinner
        variant="primary"
        label="Loading..."
        v-show="isLoading || isLoadingTags"
      ></b-spinner>
      <b-row>
        <b-col cols="8">
          <p v-show="!isLoading && !isLoadingTags && ideas.length === 0">
            Aucune idée correspondant aux filtres n'existe...
          </p>
          <b-row v-show="ideas.length > 0">
            <b-col
              sm="12"
              md="6"
              xl="6"
              v-for="idea in ideas"
              v-bind:key="idea.id"
              v-show="!isLoading && !isLoadingTags"
            >
              <project-component
                v-bind:project="idea"
                :reducedDisplay="true"
                :isProjectInitiative="true"
              />
            </b-col>
          </b-row>
        </b-col>
        <b-col cols="4">
          <base-block
            rounded
            title="Filtres"
            content-full
            header-class="bg-muted"
            themed
          >
            <p>
              <b-form>
                <b-form-group
                  label="Contient le texte"
                  label-for="contains-text"
                  class="text-center"
                >
                  <b-form-input
                    id="contains-text"
                    placeholder="Votre recherche..."
                    v-model="searchText"
                  ></b-form-input>
                </b-form-group>
                <b-form-group label="Tags" label-for="tags" class="text-center">
                  <v-select
                    id="tags"
                    size="lg"
                    multiple
                    v-model="tags"
                    :options="options"
                    placeholder="Définissez des tags..."
                  ></v-select>
                </b-form-group>
                <b-form-group
                  label="Date de publication"
                  label-for="publication-date"
                  class="text-center"
                >
                  <b-button-group>
                    <b-button
                      v-for="(item, index) in datesRanges"
                      :key="item"
                      @click="filterByDate(index)"
                      :variant="
                        index === currentRange ? 'primary' : 'outline-primary'
                      "
                      >{{ item }}</b-button
                    >
                  </b-button-group>
                </b-form-group>
                <b-row>
                  <b-col md="6" xl="6" class="text-center">
                    <b-button variant="alt-success" @click="search" block>
                      <i class="fa fa-search mr-1"></i> Rechercher
                    </b-button>
                  </b-col>
                  <b-col md="6" xl="6" class="text-center">
                    <b-button variant="alt-warning" @click="onClear" block>
                      <i class="fa fa-trash mr-1"></i> Réinitialiser
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>
            </p>
          </base-block>
        </b-col>
        <b-col cols="8">
          <b-pagination
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            v-show="!isLoading && !isLoadingTags"
            align="right"
          ></b-pagination>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<style lang="scss">
@import '~vue-select/src/scss/vue-select';
@import './src/assets/scss/vendor/vue-select';
</style>

<script lang="ts">
import ProjectComponent from '../components/ProjectComponent.vue'
import VueSelect from 'vue-select'
import { getTags } from '@/api/services/TagsService'
import { ProjectPaginationDTO, TagDTO } from '@/typings/scrutia-types'
import { getIdeas } from '@/api/services/ProjectsService'

export default {
  name: 'BrowseIdeaView',
  data() {
    return {
      ideas: [],
      searchText: '',
      isLoading: true,
      isLoadingTags: true,
      datesRanges: ['Tout', '-24h', '-48h', '-1 semaine'],
      currentRange: 0,
      rows: 30,
      perPage: 3,
      currentPage: 1,
      options: [],
      tags: [],
    }
  },
  methods: {
    async loadTags() {
      this.isLoadingTags = true
      const response: Response = await getTags()
      if (response.ok) {
        const tags = await response.json()
        this.$data.options = tags.map((tag: TagDTO) => tag.title)
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des tags",
          showConfirmButton: true,
        })
      }
      this.isLoadingTags = false
    },
    async loadIdeas() {
      this.isLoading = true
      const response: Response = await getIdeas()
      if (response.ok) {
        const projectsPagingation: ProjectPaginationDTO = await response.json()
        this.ideas = projectsPagingation.data
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des projets",
          showConfirmButton: true,
        })
      }
      this.isLoading = false
    },
    search() {},
    onClear() {
      if (
        this.$data.searchText !== '' ||
        this.$data.currentRange !== 0 ||
        this.$data.tags.length !== 0
      ) {
        this.$data.searchText = ''
        this.$data.currentRange = 0
        this.$data.tags = []
        this.loadIdeas()
      }
    },
    filterByDate(range: number) {
      this.currentRange = range
    },
  },
  components: {
    ProjectComponent,
    'v-select': VueSelect,
  },
  async created() {
    this.loadTags()
    this.loadIdeas()
  },
}
</script>
