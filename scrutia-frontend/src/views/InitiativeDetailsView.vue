<template>
  <div class="content">
    <ProjectHeader
      :projectId="projectId"
      :title="title"
      :description="description"
      :tagList="tagList"
      :likesCount="likesCount"
      :canBePromoted="projectCanBePromoted"
      :isLiked="isLiked"
      :versionId="latestVersionId"
    ></ProjectHeader>

    <!-- Block Tabs Default Style -->
    <b-tabs
      class="block"
      nav-class="nav-tabs-block"
      content-class="block-content"
    >
      <b-tab title="Révisions" active>
            <ProjectDiscussion v-for="(d, index) in projetDiscussions"
                               :key="d.id"
                               :discussion-id="d.id"
                               :project-id="$route.params.project_id"
                               :versionId="latestVersionId"
                               :title="d.title"
                               :text="d.text"
                               :likeCount="d.likesCurrent"
                               :isUpvoted="d.isUpvoted"
                               :isDownvoted="d.isDownvoted"
                               :closed="index !== 0"
            />

      </b-tab>
      <b-tab title="Fils de discussion" active>
        <ProjectDiscussion v-for="(d, index) in discussions"
                           :key="d.id"
                           :discussion-id="d.id"
                           :project-id="$route.params.project_id"
                           :versionId="latestVersionId"
                           :title="d.title"
                           :text="d.text"
                           :likeCount="d.likesCurrent"
                           :isUpvoted="d.isUpvoted"
                           :isDownvoted="d.isDownvoted"
                           :closed="index !== 0"
        />
      </b-tab>
    </b-tabs>
  </div>
</template>

<script lang="ts">
export default {
  name: 'initiativeDetails',
  data() {
    return {
      revisions: [],
      discussions: [],
      project: {},
    }
  },
  methods: {

  },
  components: {},
  async mounted() {
  },
}
</script>
