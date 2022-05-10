<template>
  <div>
    <div class="content">
      <h1 class="content-heading">Parcourir les idées</h1>
      <p>
        Laissez vous convaincre et apportez votre soutient aux bonnes idées.
      </p>
      <p></p>
      <b-row>
        <b-col cols="8">
          <idea
            v-bind:idea="{
              title: 'Sauver les pandas en Asie',
              description:
                'Description de mon projet. Ce texte peut parfois être super long. Ce texte peut parfois être super long. Ce texte peut parfois être super long.',
              isProjectInitiative: false,
            }"
            :reducedDisplay="false"
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

<script>
import Idea from '../../components/Project.vue'

export default {
  components: {
    Idea,
  },
}
</script>
