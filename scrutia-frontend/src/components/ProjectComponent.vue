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
      <div
        v-show="isNew && project.isProjectInitiative"
        class="block-options-item text-primary-light custom-font-size"
      >
        Nouveau !
      </div>
      <div class="block-options-item text-success custom-font-size">
        {{ augmentation }}
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
    <p class="custom-font-size">{{ shortedDescription }}</p>
    <address v-show="project.isProjectInitiative">
      <a href="#" class="custom-font-size">Jose Wagner</a
      ><em class="custom-font-size">, le 12 avril 2022</em><br />
      <b-badge :variant="getNextColor()">Pandas</b-badge>
      <b-badge :variant="getNextColor()">Environnement</b-badge>
      <b-badge :variant="getNextColor()">Planète</b-badge>
      <b-badge :variant="getNextColor()">Animaux</b-badge>
      <b-badge :variant="getNextColor()">Asie</b-badge>
    </address>
    <address v-show="!project.isProjectInitiative" class="custom-font-size">
      <i class="fa fa-thumbs-up custom-font-size" /> {{ likes }} personnes
      soutiennent déjà l'idée
    </address>
  </base-block>
</template>
<script>
const COLOR_VARIANTS = ['primary', 'success', 'info', 'warning', 'danger']
let currentColor = 0

export default {
  name: 'ProjectComponent',
  props: {
    project: {
      type: Object,
      description: 'The project to display',
    },
    reducedDisplay: {
      type: Boolean,
      description: 'If the component is in reduced stated',
    },
    isProjectInitiative: {
      type: Boolean,
      description: 'If the project is in initiative state',
    },
  },
  methods: {
    getNextColor() {
      const color = COLOR_VARIANTS[currentColor]
      currentColor =
        currentColor >= COLOR_VARIANTS.length ? 0 : currentColor + 1
      return color
    },
  },
  computed: {
    isNew() {
      return Math.random() < 0.5
    },
    augmentation() {
      return '+' + Math.round(Math.random() * 100) + '%'
    },
    likes() {
      return Math.round(Math.random() * 1000)
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

<style scoped>
.custom-font-size {
  font-size: 12px;
}
</style>
