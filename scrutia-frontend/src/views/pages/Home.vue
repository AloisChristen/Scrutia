<template>
  <div>
    <div class="content">
      <h1 class="content-heading">Accueil</h1>
      <p>...</p>
      <b-row>
        <b-col md="6" xl="3">
          <reduced-projet
            v-bind:project="{
              title: 'Super titre',
              description: 'Description de mon projet',
            }"
          />
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import ReducedProjet from '../../components/ReducedProjet.vue'

export default {
  components: {
    ReducedProjet,
  },
}
</script>
