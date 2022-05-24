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
                <p>tag 1 tag 2</p>
                <b-badge :variant="getNextColor()">Pandas</b-badge>
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

    <div class="block-rounded block">
      <!----><!---->
      <ul class="nav nav-tabs nav-tabs-block" role="tablist">
        <li class="nav-item">
          <button
            class="nav-link active"
            id="btabs-animated-fade-home-tab"
            data-bs-toggle="tab"
            data-bs-target="#btabs-animated-fade-home"
            role="tab"
            aria-controls="btabs-animated-fade-home"
            aria-selected="true"
          >
            Révisions
          </button>
        </li>

        <li class="nav-item">
          <button
            class="nav-link"
            id="btabs-animated-fade-profile-tab"
            data-bs-toggle="tab"
            data-bs-target="#btabs-animated-fade-profile"
            role="tab"
            aria-controls="btabs-animated-fade-profile"
            aria-selected="false"
          >
            Fils de discussions
          </button>
        </li>
      </ul>
      <div class="block-content tab-content overflow-hidden container">
        <div
          class="tab-pane fade active show"
          id="btabs-animated-fade-home"
          role="tabpanel"
          aria-labelledby="btabs-animated-fade-home-tab"
        >
          <div>
            <div class="block-rounded block">
              <div class="block-header block-header-default">
                <h3 class="block-title">
                  Title
                  <small>Subtitle</small>
                </h3>
                <div class="block-options space-x-1">
                  <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                  </button>
                  <button type="button" class="btn-block-option">
                    <i class="si si-size-fullscreen"></i>
                  </button>
                  <button type="button" class="btn-block-option">
                    <i class="si si-pin"></i></button
                  ><button type="button" class="btn-block-option">
                    <i class="si si-arrow-up"></i></button
                  ><button type="button" class="btn-block-option">
                    <i class="si si-close"></i>
                  </button>
                </div>
              </div>
              <div class="block-content">
                <!---->
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
        </div>
        <div
          class="tab-pane fade"
          id="btabs-animated-fade-profile"
          role="tabpanel"
          aria-labelledby="btabs-animated-fade-profile-tab"
        >
          <div>
            <div class="block-rounded block">
              <div class="block-header block-header-default">
                <h3 class="block-title">
                  Title
                  <small>Subtitle</small>
                </h3>
                <div class="block-options space-x-1">
                  <button type="button" class="btn-block-option">
                    <i class="si si-refresh"></i>
                  </button>
                  <button type="button" class="btn-block-option">
                    <i class="si si-size-fullscreen"></i>
                  </button>
                  <button type="button" class="btn-block-option">
                    <i class="si si-pin"></i></button
                  ><button type="button" class="btn-block-option">
                    <i class="si si-arrow-up"></i></button
                  ><button type="button" class="btn-block-option">
                    <i class="si si-close"></i>
                  </button>
                </div>
              </div>
              <div class="block-content">
                <!---->
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
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { getTags } from '@/api/services/TagsService'
import { TagDTO } from '@/typings/scrutia-types'

export default {
  name: 'BrowseIdeaView',
  data() {},
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
    this.loadIdeas()
  },
}
</script>
