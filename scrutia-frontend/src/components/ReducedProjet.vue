<template>
  <base-block :title="project.title" subtitle="" header-bg>
    <p>{{ project.description }}</p>
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
}
</script>
