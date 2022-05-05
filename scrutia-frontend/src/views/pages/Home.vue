<template>
  <div>
    <div class="content">
      <h2 class="content-heading">Idées les plus tendances...</h2>

      <b-row>
        <b-col sm="12" md="4" xl="4" v-for="index in 6" :key="index">
          <idea
            v-bind:project="{
              title: 'Sauver les pandas en Asie',
              description:
                'Description de mon projet. Ce texte peut parfois être super long. Ce texte peut parfois être super long. Ce texte peut parfois être super long.',
            }"
            :evenIndex="index % 2 == 0"
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
          <projet
            v-bind:project="{
              title: 'Sauver les pandas en Asie',
              description:
                'Description de mon projet. Ce texte peut parfois être super long. Ce texte peut parfois être super long. Ce texte peut parfois être super long.',
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

<style>
.block-title,
.block-header,
.block-content {
  font-size: 12px;
}
</style>

<script>
import Projet from '../../components/Project.vue'
import Idea from '../../components/Idea.vue'

export default {
  components: {
    Projet,
    Idea,
  },
}
</script>
