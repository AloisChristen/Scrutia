<template>
  <b-row style="padding-left: 0" no-gutters >
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
                <div style="font-size: xx-small; margin-right: 16px">{{getFormatedDate(date)}}</div>
                <div style="font-size: xx-small;">{{author}}</div>
              </div>
            </div>
          </div>
        </template>

        <!-- content below -->

        <div style="display: flex; flex-direction: column" v-if="isLoaded">
          <div style="display: flex; margin-left: 65px; flex-direction: column">

            <!-- answers display -->
            <div style="margin-bottom: 16px" v-for="a in dataAnswers" :key="a.id">
              <i v-if="a.user_vote === 1" class="fa fa-fw fa-thumbs-up mr-1" v-on:click="likeAnswer(a.id, 0)"></i>
              <i v-else class="fa fa-fw fa-thumbs-up mr-1" style="color: lightgray" v-on:click="likeAnswer(a.id, 1)"></i>
              <span style="font-weight: bold">{{a.user_id}}:</span>
              {{a.description}}
            </div>


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

import {format} from "date-fns";
import frenchLocale from "date-fns/locale/fr";

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
    },
    date: {
      type: String
    },
    author: {
      type: String
    },
    onlyThreeAnswer: {
      type: Boolean,
      default: false,
    }
  },
  mounted() {
    if(this.closed) {
      this.$refs.baseBlockDiscussionComponent.contentHide()
    }
    if(this.onlyThreeAnswer){
      this.dataAnswers = this.dataAnswers.filter((a: any, idx: number) => idx < 3);
    }
    this.isLoaded = true;
  },
  data() {
    return {
      dataIsUpvoted: this.isUpvoted,
      dataIsDownvoted: this.isDownvoted,
      dataLikeCount: this.likeCount,
      dataResponse: "",
      dataAnswers: this.answers,
      isLoaded: false,
    }
  },
  methods: {
    upvote() {
      if(this.dataIsUpvoted) {
        this.dataIsUpvoted = false
        this.dataLikeCount--
      } else {
        this.dataIsUpvoted = true
        this.dataLikeCount++
      }
    },
    downvote() {
      if(this.dataIsDownvoted) {
        this.dataIsDownvoted = false
        this.dataLikeCount++
      } else {
        this.dataIsDownvoted = true
        this.dataLikeCount--
      }
    },
    repondre(){
      console.log("dataResponse", this.dataResponse);
    },
    likeAnswer(answerId: number, value: number){
      console.log("answerId", answerId, value)
    },
    getFormatedDate(date: string) {
      return format(new Date(date), 'dd LLLL yyyy', {
        locale: frenchLocale,
      })
    },
  }

}

</script>

<style scoped>

</style>
