<template>
  <div>
    <div class="content">
      <h1 class="content-heading">Parcourir les idées</h1>
      <p>...</p>
      <b-row>
        <b-col cols="8">
          <idea
            v-bind:project="{
              title: 'Sauver les pandas en Asie',
              description:
                'Description de mon projet. Ce texte peut parfois être super long. Ce texte peut parfois être super long. Ce texte peut parfois être super long.',
            }"
            :evenIndex="index % 2 == 0"
          />
        </b-col>
        <b-col cols="4">
          <base-block rounded>
            <p>
              <code>cols="3"</code>
            </p>
          </base-block>
        </b-col>
      </b-row>
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
import Idea from '../../components/Idea.vue'

export default {
  components: {
    Idea,
  },
}
</script>
