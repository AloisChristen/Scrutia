<template>
  <base-block
    :title="shortedTitle"
    :fx-rotate-right="evenIndex"
    :fx-rotate-left="!evenIndex"
    rounded
    tag="a"
    href="javascript:void(0)"
    link-pop
  >
    <template #options>
      <div class="block-options-item text-success">{{ augmentation }}</div>
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
    <p>{{ shortedDescription }}</p>
    <address>
      <i class="fa fa-thumbs-up" /> {{ likes }} personnes soutiennent déjà
      l'idée
    </address>
  </base-block>
</template>

<style>
.block-content {
  padding-top: 0px;
}
</style>

<script>
export default {
  name: 'ReducedIdea',
  props: {
    project: {
      type: Object,
      description: 'The idea to display',
    },
    evenIndex: {
      type: Boolean,
      description: 'If the idea index is even',
    },
  },
  computed: {
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
.block-title,
.block-header,
.block-content {
  font-size: 12px;
}
.block-content {
  padding-top: 20px;
}
</style>
