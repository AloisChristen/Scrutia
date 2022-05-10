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
            <project
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
              <b-form @submit.stop.prevent="onSubmit">
                <b-form-group
                  label="Contient le texte"
                  label-for="contains-text"
                >
                  <b-form-input
                    id="contains-text"
                    placeholder="Votre recherche..."
                  ></b-form-input>
                </b-form-group>
                <b-form-group label="Tags" label-for="tags">
                  <v-select
                    id="tags"
                    size="lg"
                    multiple
                    v-model="vSelectOptionsMultipleSelected"
                    :options="vSelectOptionsMultiple"
                    placeholder="Définissez des tags..."
                  ></v-select>
                </b-form-group>
                <b-form-group
                  label="Date de publication"
                  label-for="publication-date"
                >
                  <b-button-group>
                    <b-button variant="outline-primary">Tout</b-button>
                    <b-button variant="outline-primary">-24h</b-button>
                    <b-button variant="outline-primary">-48h</b-button>
                    <b-button variant="outline-primary">-1 semaine</b-button>
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
import Project from '../../components/Project.vue'
import VueSelect from 'vue-select'

export default {
  data() {
    return {
      rows: 30,
      perPage: 10,
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
  components: {
    Project,
    'v-select': VueSelect,
  },
}
</script>
