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
        <b-col cols="7">
          <div
            v-for="index in 3"
            :key="index"
            v-show="!isLoading && !isLoadingTags"
          >
            <project-component
              v-bind:project="{
                title: 'Sauver les pandas en Asie',
                description:
                  'Description de mon projet. Ce texte peut parfois être super long. Ce texte peut parfois être super long. Ce texte peut parfois être super long.',
                isProjectInitiative: false,
              }"
              :reducedDisplay="true"
            />
          </div>
        </b-col>
        <b-col cols="5">
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
                    @keydown.enter="filterByText"
                    @blur="(e) => filterByText(e.target.value())"
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
                    v-on:input="filterByTags"
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
              </b-form>
            </p>
          </base-block>
        </b-col>
        <b-col cols="7">
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
import { getProjects } from '@/api/services/ProjectsService'

export default {
  name: 'BrowseIdeaView',
  data() {
    return {
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
    },
    filterByText(text: string) {
      console.log(text)
      console.log('Filter by text')
    },
    filterByTags() {
      console.log('Filter by tags')
    },
    filterByDate(range: number) {
      this.currentRange = range
      console.log('Filter by date')
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
