<template>
  <div>
    <div class="content">
      <h1 class="content-heading">Parcourir les idées et projets</h1>
      <p>
        Laissez vous convaincre et apportez votre soutient aux bonnes idées.
      </p>
      <b-row>
        <b-col sm="12" md="6" xl="4">
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
                  label="Type"
                  label-for="type-choice"
                  class="text-center"
                >
                  <v-select
                    id="tags"
                    size="lg"
                    multiple
                    v-model="types"
                    :options="typesOptions"
                    placeholder="Choisissez un / des types..."
                    v-on:input="search"
                  ></v-select>
                </b-form-group>
                <b-form-group
                  label="Contient le texte"
                  label-for="contains-text"
                  class="text-center"
                >
                  <b-form-input
                    id="contains-text"
                    placeholder="Votre recherche..."
                    v-model="searchText"
                    @keyup.enter="search"
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
                    v-on:input="search"
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
                    <b-button
                      class="mb-4"
                      variant="alt-success"
                      @click="search"
                      block
                      :disabled="types.length == 0"
                    >
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
        <b-spinner
          variant="primary"
          label="Loading..."
          v-show="isLoading || isLoadingTags"
        ></b-spinner>
        <b-col sm="12" md="6" xl="8">
          <p v-show="!isLoading && !isLoadingTags && ideas.length === 0">
            Aucune idée correspondant aux filtres n'existe...
          </p>
          <b-row v-show="ideas.length > 0">
            <b-col
              sm="12"
              md="12"
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
        <b-col sm="12" md="12" xl="12">
          <b-pagination
            v-model="current_page"
            :total-rows="total"
            :per-page="per_page"
            v-show="!isLoading && !isLoadingTags"
            align="right"
            @change="(newValue) => changePage(newValue)"
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
import { getProjectsWithFilters } from '@/api/services/ProjectsService'
import { subDays } from 'date-fns'

const all_types = ['Idées', "Projets d'initiative"]

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
      options: [],
      typesOptions: all_types,
      tags: [],
      types: all_types,
      current_page: 1,
      last_page_url: '',
      next_page_url: '',
      prev_page_url: '',
      total: 0,
      per_page: 0,
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
    async loadIdeas(
      types: string[] | null,
      text: string | null,
      startDate: Date | null,
      endDate: Date | null,
      tags: string[] | null,
      page: number = 1
    ) {
      this.isLoading = true
      const response: Response = await getProjectsWithFilters(
        types,
        text,
        startDate,
        endDate,
        tags,
        page
      )
      if (response.ok) {
        const projectsPagingation: ProjectPaginationDTO = await response.json()
        this.ideas = projectsPagingation.data
        this.current_page = projectsPagingation.current_page
        this.last_page_url = projectsPagingation.last_page_url
        this.next_page_url = projectsPagingation.next_page_url
        this.prev_page_url = projectsPagingation.prev_page_url
        this.per_page = projectsPagingation.per_page
        this.total = projectsPagingation.total
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des projets",
          showConfirmButton: true,
        })
      }
      this.isLoading = false
    },
    search() {
      let startDate = null
      switch (this.currentRange) {
        case 1:
          startDate = subDays(new Date(), 1)
          break
        case 2:
          startDate = subDays(new Date(), 2)
          break
        case 3:
          startDate = subDays(new Date(), 7)
          break
      }
      this.loadIdeas(
        this.$data.types,
        this.$data.searchText,
        startDate,
        null,
        this.$data.tags
      )
    },
    onClear() {
      if (
        this.$data.types.length !== all_types.length ||
        this.$data.searchText !== '' ||
        this.$data.currentRange !== 0 ||
        this.$data.tags.length !== 0
      ) {
        this.$data.types = all_types
        this.$data.searchText = ''
        this.$data.currentRange = 0
        this.$data.tags = []
        this.loadIdeas(null, null, null, null, null)
      }
    },
    filterByDate(range: number) {
      this.currentRange = range
      this.search()
    },
    changePage(newValue: number) {
      this.loadIdeas(null, null, null, null, null, newValue)
    },
  },
  components: {
    ProjectComponent,
    'v-select': VueSelect,
  },
  async created() {
    const url = new URL(window.location.href)
    const search = url.searchParams.get('question')
    this.$data.searchText = search
    let type = url.searchParams.get('type')
    if (type !== 'ideas' && type !== 'initiatives') type = null
    else if (type === 'ideas') this.$data.types = [all_types[0]]
    else this.$data.types = [all_types[1]]
    this.loadTags()
    this.search()
  },
}
</script>
