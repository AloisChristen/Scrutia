<template>
  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="12">
      <base-block
        rounded
        btn-option-content
        ref="baseBlockDiscussionComponent"
      >
        <template #title>
          <div style="display: flex;">
            <div style="display: flex; flex-direction: column; width: 50px; align-items: center; margin-right: 16px">
              <i v-if="dataIsUpvoted" class="fa fa-angle-up mr-1"></i>
              <i v-else class="fa fa-angle-up mr-1" v-on:click="upvote()" style="color: lightgray"></i>
              <div>{{ dataLikeCount }}</div>
              <i v-if="dataIsDownvoted" class="fa fa-angle-down mr-1"></i>
              <i v-else class="fa fa-angle-down mr-1" v-on:click="downvote()" style="color: lightgray"></i>
            </div>
            <div style="display: flex; flex-direction: column; align-self: center">
              <div class="Lead">{{title}}</div>
              <div style="display: flex">
                <div style="font-size: xx-small; margin-right: 16px">le mardi 14 juin</div>
                <div style="font-size: xx-small;">Alfred dupont</div>
              </div>
            </div>
          </div>
        </template>

        <!-- content below -->

        <div style="display: flex; flex-direction: column" >
          <div style="display: flex; margin-left: 65px; flex-direction: column">
            <div style="margin-bottom: 16px">
              <i class="fa fa-fw fa-thumbs-up mr-1"></i>
              <span style="font-weight: bold">Alfred dupoint:</span>
              Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper
              etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras
              hac ac ad massa, fusce ante convallis ante urna molestie vulputate
              bibendum tempus ante justo</div>

            <div style="margin-bottom: 16px">
              <i class="fa fa-fw fa-thumbs-up mr-1" style="color: lightgray"></i>
              <span style="font-weight: bold">Alfred dupoint:</span> Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper
              etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras
              hac ac ad massa, fusce ante convallis ante urna molestie vulputate
              bibendum tempus ante justo</div>
            <div style="margin-bottom: 16px"><span style="font-weight: bold">Alfred dupoint:</span> Potenti elit lectus augue eget iaculis vitae etiam, ullamcorper
              etiam bibendum ad feugiat magna accumsan dolor, nibh molestie cras
              hac ac ad massa, fusce ante convallis ante urna molestie vulputate
              bibendum tempus ante justo</div>
            <div v-if="showLink" style="margin-left: auto">
              <router-link :to="{ name: 'ideaDiscussion', params: { project_id: projectId, discussion_id: discussionId }}">Voir le fil de discussion -></router-link>
            </div>
            <div v-if="completeView" style="width: 100%">
              <b-form @submit.prevent class="mb-5">
                <b-form-group label="Votre réponse" label-for="response">
                  <b-form-input id="response" type="text" placeholder="Votre réponse" v-model="dataResponse"></b-form-input>
                </b-form-group>
                <b-form-group>
                  <b-button type="submit" variant="primary" style="float: right" v-on:click="repondre()">Répondre</b-button>
                </b-form-group>
              </b-form>
            </div>
          </div>
        </div>
        <!-- content end -->
      </base-block>
    </b-col>
  </b-row>
</template>

<script lang="ts">

export default {
  name: "ProjectDiscussion",
  props: {
    projectId: {
      type: Number
    },
    discussionId: {
      type: Number
    },
    title: {
      type: String
    },
    text: {
      type: String
    },
    likeCount: {
      type: Number,
    },
    comments: {
      type: Array
    },
    displayAllMode: {
      type: Boolean
    },
    closed: {
      type: Boolean,
      default: false
    },
    showLink: {
      type: Boolean,
      default: true
    },
    completeView: {
      type: Boolean,
      default: false,
    },
    isUpvoted: {
      type: Boolean,
      default: false,
    },
    isDownvoted: {
      type: Boolean,
      default: false,
    },
    answers: {
      type: Array
    }
  },
  mounted() {
    if(this.closed) {
      this.$refs.baseBlockDiscussionComponent.contentHide()
    }
  },
  data() {
    return {
      dataIsUpvoted: this.isUpvoted,
      dataIsDownvoted: this.isDownvoted,
      dataLikeCount: this.likeCount,
      dataResponse: "",
    }
  },
  methods: {
    upvote() {
      console.log("upvoting");
      this.dataIsUpvoted = true;
      this.dataIsDownvoted = false;
      this.dataLikeCount = this.dataLikeCount+1;
    },
    downvote() {
      console.log("downvoting");
      this.dataIsUpvoted = false;
      this.dataIsDownvoted = true;
      this.dataLikeCount = this.dataLikeCount-1;
    },
    repondre(){
      console.log("dataResponse", this.dataResponse);
    }
  }

}

</script>

<style scoped>

</style>
