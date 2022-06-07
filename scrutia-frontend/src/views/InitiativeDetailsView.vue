<template>
  <div class="content">
    <div>
      <div class="block-rounded block-transparent block col-xl-10">
        <div class="block-header">
          <h3 class="block-title">Ceci est un titre <small>Subtitle</small></h3>
          <!---->
        </div>
        <div class="block-content" style="padding: 10px 0px 0px 0px">
          <div class="container">
            <div class="row">
              <div class="block-content-left col-xl-2">
                <img
                  src="https://via.placeholder.com/120x120"
                  alt="..."
                  class="img-fluid"
                />
                <b-badge :variant="getNextColor()">PandasTag</b-badge>
              </div>

              <div class="block-content-right col-xl-10">
                <p>
                  Dolor posuere proin blandit accumsan senectus netus nullam
                  curae, ornare laoreet adipiscing luctus mauris adipiscing
                  pretium eget fermentum, tristique lobortis est ut metus
                  lobortis tortor tincidunt himenaeos habitant quis dictumst
                  proin odio sagittis purus mi, nec taciti vestibulum quis in
                  sit varius lorem sit metus mi.
                </p>
              </div>
            </div>
          </div>

          <!---->
        </div>
      </div>
    </div>

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
import { getTags } from '@/api/services/TagsService'
//import { getIdeas } from '@/api/services/ProjectService'
import { TagDTO } from '@/typings/scrutia-types'

export default {
  name: 'initiativeDetails',
  data() {return {
    tags: [],
    latestVersionId: 0,
    discussions: [],
    projetDiscussions: [],
  }},
  methods: {
    async loadTags() {
      const response: Response = await getTags()
      if (response.ok) {
        const tags = await response.json()
        this.$data.options = tags.map((tag: TagDTO) => tag.title)
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des tags",
          showConfirmButton: true,
        })
      }
      this.isLoadingTags = false
    },
    async loadIdeas() {
      /*const response: Response = await getIdeas()
      if (response.ok) {
        const ideas = await response.json()
        this.$data.ideas = ideas
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement des idées",
          showConfirmButton: true,
        })
      }
      this.isLoadingIdeas = false*/
    },
    getNextColor() {
      const colors = [
        'primary',
        'secondary',
        'success',
        'info',
        'warning',
        'danger',
        'light',
        'dark',
      ]
      const index = Math.floor(Math.random() * colors.length)
      return colors[index]
    },
  },
  components: {},
  async created() {
    this.loadTags()
    //this.loadIdeas()
  },
}
</script>
