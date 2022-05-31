<template>
<div>
  <b-row style="padding-left: 0" no-gutters>
    <b-col>
      <img
        v-if="displayPicture"
        src="https://via.placeholder.com/120x120"
        alt="..."
        class="img-fluid"
      />
    </b-col>
    <b-col cols="8">
      <base-block rounded class="mb-0" transparent>
        <h1 class="font-w400">{{ title }}</h1>
      </base-block>
    </b-col>
    <b-col cols="4" class="mb-0" v-if="ideaActionActivated">
      <!-- transparent -->
      <base-block rounded transparent>
        <div v-on:click="like_current()">
          <div v-if="isLiked">
            <i class="fa fa-fw fa-thumbs-up mr-1"></i>
            {{  likesCount }}
          </div>
          <div v-else>
            <i
              class="fa fa-fw fa-thumbs-up mr-1"
              style="color: lightgray"
            ></i>
            {{ likesCount }}
          </div>
        </div>
      </base-block>
      <b-button v-if="canBePromoted" v-on:click="promote">
        promouvoire le projet
      </b-button>
    </b-col>
  </b-row>

  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="12">
      <b-badge v-for="tag in tagList" :key="tag.id" :variant="getNextColor()" >
        {{ tag }}
      </b-badge>
    </b-col>
    <b-col cols="12">
      <p>
        {{description}}
      </p>
    </b-col>
  </b-row>
  <div class="block-rounded block-transparent block col-xl-10">
    <div class="block-header">
    </div>
    <div class="block-content" style="padding: 10px 0px 0px 0px">
      <div class="container">
        <div class="row">
          <div class="block-content-left col-xl-2">

            <img
              v-if="displayPicture"
              src="https://via.placeholder.com/120x120"
              alt="..."
              class="img-fluid"
            />


          </div>

        </div>
      </div>

      <!---->
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: "ProjectHeader",
  props: {
    title: {
      type: String,
      default: 'Ceci est un titre'
    },
    tagList: {
      type: Array,
      required: true
    },
    description: {
      type: String,
      default: "Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in         sit varius lorem sit metus mi."
    },
    displayPicture: {
      type: Boolean,
      default: false,
    },
    ideaActionActivated: {
      type: Boolean,
      default: false,
    },
    isLiked: {
      type: Boolean,
      default: false
    },
    likesCount: {
      type: Number,
      default: 311,
    },
    canBePromoted: {
      type: Boolean,
      default: false
    }

  },
  methods: {
    getNextColor() {
      const colors = [
        'primary',
        'secondary',
        'success',
        'info',
        'warning',
        'danger',
        'dark',
      ]
      const index = Math.floor(Math.random() * colors.length)
      return colors[index]
    },

    //- --- -- idea actions -- -- - - -- -
    like_current(){
      console.log("liking...-")
    },
    promote(){
      console.log("promoting....")
    }





  }
}
</script>

<style scoped>
  margin-right-15 {
    margin-right: 15px;
  }
</style>
