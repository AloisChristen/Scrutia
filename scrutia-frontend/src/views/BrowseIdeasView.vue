<template>
  <div>
    <div class="content">
      <h1 class="content-heading">Parcourir les idées</h1>
      <p>
        Laissez vous convaincre et apportez votre soutient aux bonnes idées.
      </p>
      <p></p>
      <b-row>
        <b-col cols="7">
          <div v-for="index in 3" :key="index">
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
                    v-model="vSelectOptionsMultipleSelected"
                    :options="vSelectOptionsMultiple"
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
                      v-for="item in datesRanges"
                      :key="item"
                      variant="outline-primary"
                      @click="filterByDate"
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

<script>
import ProjectComponent from '../components/ProjectComponent.vue'
import VueSelect from 'vue-select'

export default {
  name: 'BrowseIdeaView',
  data() {
    return {
      datesRanges: ['Tout', '-24h', '-48h', '-1 semaine'],
      rows: 30,
      perPage: 3,
      currentPage: 1,
      vSelectOptionsMultiple: [
        'HTML',
        'CSS',
        'JavaScript',
        'PHP',
        'MySQL',
        'Ruby',
        'Angular',
        'React',
        'Vue.js',
      ],
      vSelectOptionsMultipleSelected: null,
    }
  },
  methods: {
    filterByText(text) {
      console.log(text)
      console.log('Filter by text')
    },
    filterByTags() {
      console.log('Filter by tags')
    },
    filterByDate() {
      console.log('Filter by date')
    },
  },
  components: {
    ProjectComponent,
    'v-select': VueSelect,
  },
}
</script>
