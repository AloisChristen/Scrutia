<template>
  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="12">
      <base-block
        rounded
        :title=title
        btn-option-content
      >

        <div>
          <div class="flex-column">
            <div>arrow up</div>
            <div>likecount</div>
            <div>arrow down</div>
          </div>
          <p>{{text}}</p>
        </div>
      </base-block>
    </b-col>
  </b-row>
</template>

<script lang="ts">

export default {
  name: "ProjectDiscussion",
  props: {
    title: {
      type: String
    },
    text: {
      type: String
    }
  }
}

</script>

<style scoped>

</style>
