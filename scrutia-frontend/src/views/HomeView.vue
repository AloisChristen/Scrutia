<template>
  <div>
    <div class="content">
      <h2 class="content-heading">Idées les plus tendances...</h2>

      <b-row>
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
          href="javascript:void(0)"
          style="float: right; margin-bottom: 10px; margin-top: -10px"
          >et 125 autres...</b-link
        >
      </p>
      <h2 class="content-heading">Projets d'initiative les plus actifs...</h2>
      <b-row>
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
          href="javascript:void(0)"
          style="float: right; margin-bottom: 10px; margin-top: -10px"
          >et 28 autres...</b-link
        >
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import ProjectComponent from '../components/ProjectComponent.vue'
import { Component, Vue } from 'vue-property-decorator'
import { getIdeas } from '../../api/services/ProjectsService'

@Component({
  name: 'HomeView',
  components: {
    ProjectComponent,
  },
  async created() {
    getIdeas()
  },
})
export default class HomeView extends Vue {}
</script>
