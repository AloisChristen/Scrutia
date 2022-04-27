<template>
  <base-block
    :title="shortedTitle"
    header-bg
    rounded
    tag="a"
    href="javascript:void(0)"
    link-pop
  >
    <template #options>
      <div v-show="isNew" class="block-options-item text-success">
        Nouveau !
      </div>
      <button type="button" class="btn-block-option">
        <i
          v-bind:class="[
            { fa: isFavorite },
            { 'fa-star': isFavorite },
            { si: !isFavorite },
            { 'si-star': !isFavorite },
          ]"
        />
      </button>
    </template>
    <p style="padding-top: 20px">{{ shortedDescription }}</p>
    <address>
      <a href="#">Jose Wagner</a><em>, le 12 avril 2022</em><br />
      <b-badge>Pandas</b-badge>
      <b-badge variant="primary">Environnement</b-badge>
      <b-badge variant="success">Planète</b-badge>
      <b-badge variant="info">Animaux</b-badge>
      <b-badge variant="warning">Asie</b-badge>
    </address>
  </base-block>
</template>

<script>
export default {
  name: 'ReducedProjet',
  props: {
    project: {
      type: Object,
      description: 'The project to display',
    },
  },
  methods: {},
  computed: {
    isNew() {
      return Math.random() < 0.5
    },
    isFavorite() {
      return Math.random() < 0.5
    },
    shortedTitle() {
      const title = this.project.title
      if (title.length > 40) return title.substring(0, 37) + '...'
      else return title
    },
    shortedDescription() {
      const description = this.project.description
      if (description.length > 200) return description.substring(0, 197) + '...'
      else return description
    },
  },
}
</script>
