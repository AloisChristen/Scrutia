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
        {{ project.performance }}
      </div>
      <button type="button" class="btn-block-option">
        <i
          v-bind:class="[
            { fa: project.is_favorite },
            { 'fa-star': project.is_favorite },
            { si: !project.is_favorite },
            { 'si-star': !project.is_favorite },
          ]"
        />
      </button>
    </template>
    <p class="custom-font-size">{{ shortedDescription }}</p>
    <address v-show="isProjectInitiative">
      <a href="#" class="custom-font-size">{{ project.author }}</a
      ><em class="custom-font-size"
        >, le {{ new Date(project.created_at).toLocaleDateString('fr') }}</em
      ><br />
      <b-badge
        v-for="tag in project.tags"
        v-bind:key="tag.title"
        :variant="getNextColor()"
        >{{ tag.title }}</b-badge
      >
    </address>
    <address v-show="!isProjectInitiative" class="custom-font-size">
      <i class="fa fa-thumbs-up custom-font-size" />
      {{ project.likes_count }} personnes soutiennent déjà l'idée
    </address>
  </base-block>
</template>
<script lang="ts">
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
      console.log(this.project.tags)
      const today = new Date()
      if (
        86400000 <
        today.getTime() - new Date(this.project.created_at).getTime()
      )
        return true
      return false
    },
    shortedTitle() {
      if (this.project.title === null) return ''
      if (this.project.title.length > 40)
        return this.project.title.substring(0, 37) + '...'
      else return this.project.title
    },
    shortedDescription() {
      if (this.project.last_description === null) return ''
      if (this.project.last_description.length > 200)
        return this.project.last_description.substring(0, 197) + '...'
      else return this.project.last_description
    },
  },
}
</script>

<style scoped>
.custom-font-size {
  font-size: 12px;
}
</style>
