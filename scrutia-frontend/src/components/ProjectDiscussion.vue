
<template>
  <b-row style="padding-left: 0" no-gutters>
    <b-col cols="12">
      <base-block
        rounded
        btn-option-content
        ref="baseBlockDiscussionComponent"
      >
        <template v-if="!modeAffichageVersion" #title> <!-- pour la page idea -->
          <div style="display: flex;">
            <div style="display: flex; flex-direction: column; width: 50px; align-items: center; margin-right: 16px">
              <i v-if="questionData.user_vote === 1" class="fa fa-angle-up mr-1"
              ></i>
              <i v-else class="fa fa-angle-up mr-1"
                 v-on:click="voteQuestion(true)" style="color: lightgray"
                 v-bind:class="{'scrutia-clickable': isLoggedIn}"
              ></i>
              <div>{{ questionData.upvotes}}</div>
              <i v-if="questionData.user_vote === -1" class="fa fa-angle-down mr-1"></i>
              <i v-else class="fa fa-angle-down mr-1"
                 v-bind:class="{'scrutia-clickable': isLoggedIn}"
                 v-on:click="voteQuestion(false)" style="color: lightgray"></i>
            </div>
            <div style="display: flex; flex-direction: column; align-self: center">
              <div class="Lead">{{questionData.title}}</div>
              <div style="display: flex">
                <div style="font-size: xx-small; margin-right: 16px">{{getFormatedDate(questionData.created_at)}}</div>
                <div style="font-size: xx-small;">{{questionData.user.username}}</div>
              </div>
            </div>
          </div>
        </template>

        <!-- titre pour mode version -->
        <template v-else #title>
          <div style="display: flex">

            <div>
              <div style="display: flex; flex-direction: column; width: 50px; align-items: center; margin-right: 16px">
                <i v-if="versionData.user_vote === 1"
                   class="fa fa-angle-up mr-1"></i>
                <i v-else class="fa fa-angle-up mr-1"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="voteQuestion(true)" style="color: lightgray"></i>
                <div>{{ versionData.upvotes - versionData.downvotes}}</div>
                <i v-if="versionData.user_vote === -1" class="fa fa-angle-down mr-1"></i>
                <i v-else class="fa fa-angle-down mr-1"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="voteQuestion(false)" style="color: lightgray"></i>
              </div>
            </div>

            <div style="display: flex; flex-direction: column; font-size: small;">
              <div style="font-size: x-small">Le {{getFormatedDate(versionData.created_at)}}, Révision {{ versionData.number }}</div>
              <div>{{ versionData.description }}</div>
            </div>
          </div>

        </template>

        <!-- content below -->

        <div style="display: flex; flex-direction: column" v-if="isLoaded">
          <div style="display: flex; margin-left: 65px; flex-direction: column">

            <!-- answers display -->
            <div v-if="!modeAffichageVersion">
              <div style="margin-bottom: 16px" v-for="a in questionData.answers" :key="a.id">
                <i v-if="a.user_vote === 1" class="fa fa-fw fa-thumbs-up mr-1"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="likeAnswer(a.id, -1)"></i>
                <i v-else-if="a.user_vote === -1" class="fa fa-fw fa-thumbs-down mr-1"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="likeAnswer(a.id, 0)"></i>
                <i v-else class="fa fa-fw fa-thumbs-up mr-1" style="color: lightgray"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="likeAnswer(a.id, 1)"></i>
                <span style="font-weight: bold">{{a.user.username}}:</span>
                {{a.description}}
              </div>
            </div>
            <div v-else>
              <div style="margin-bottom: 16px" v-for="a in versionData.questions" :key="a.id">
                <i v-if="a.user_vote === 1" class="fa fa-fw fa-thumbs-up mr-1"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="likeAnswer(a.id, -1)"></i>
                <i v-else-if="a.user_vote === -1" class="fa fa-fw fa-thumbs-down mr-1"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="likeAnswer(a.id, 0)"></i>
                <i v-else class="fa fa-fw fa-thumbs-up mr-1" style="color: lightgray"
                   v-bind:class="{'scrutia-clickable': isLoggedIn}"
                   v-on:click="likeAnswer(a.id, 1)"></i>
                <span style="font-weight: bold">{{a.user.username}}:</span>
                {{a.description}}
              </div>
            </div>

            <div v-if="showLink" style="margin-left: auto">
              <router-link :to="{ name: 'ideaDiscussion', params: { project_id: projectId, discussion_id: discussionId }}">Voir le fil de discussion -></router-link>
            </div>
            <div v-if="canReply" style="width: 100%">
              <b-form @submit.prevent class="mb-5">
                <b-form-group label="Votre mot à dire" label-for="response">
                  <b-form-input id="response" type="text" placeholder="Votre mot à dire" v-model="inputUserReply" autocomplete="off"></b-form-input>
                </b-form-group>
                <b-form-group>
                  <b-button type="submit" variant="primary" style="float: right" v-on:click="repondreQuestion()">Envoyer</b-button>
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
import {AnswerStoreDTO, LikeDTO, QuestionStoreDTO} from "@/typings/scrutia-types";
import {addQuestion, likeQuestion} from "@/api/services/QuestionsService";
import {addAnswer, likeAnswer} from "@/api/services/AnswersService";
import {likeVersion} from "@/api/services/VersionsService";

export default {
  name: "ProjectDiscussion",
  props: {
    projectId: {
      type: Number
    },
    discussionId: {
      type: Number
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
    canReply: {
      type: Boolean,
      default: false,
    },
    onlyThreeAnswer: {
      type: Boolean,
      default: false,
    },
    userForReply: {
      type: String,
      default: ''
    },
    modeAffichageVersion: {
      type: Boolean,
      default: false,
    },
    version: {
      type: Object
    },
    versionId: {
      type: Number,
    },
    question: {
      type: Object,
    },
    isLoggedIn: {
      type: Boolean,
      default: false
    }
  },
  mounted() {
    if(this.closed) {
      this.$refs.baseBlockDiscussionComponent.contentHide()
    }
    if(this.onlyThreeAnswer){
      this.questionData.answers = this.questionData.answers.filter((a: any, idx: number) => idx < 3);
    }
    this.isLoaded = true;
  },
  data() {
    return {
      inputUserReply: "",
      dataAnswers: this.answers,
      isLoaded: false,
      versionData: this.version,
      questionData: this.question,
    }
  },
  methods: {
    async voteQuestion(isUpvote: boolean) {
      if(this.modeAffichageVersion) {
        if(isUpvote){
          if(this.versionData.user_vote == 1){ // dont send request if already have voted
            return
          }
          this.versionData.upvotes += 1
          this.versionData.user_vote = 1
        } else { // is a downvote
          if(this.versionData.user_vote == -1){ // dont send request if already have voted
            return
          }
          if(this.versionData.user_vote == 1) {
            this.versionData.upvotes -= 1
          }
          this.versionData.user_vote = -1
        }
      }
      else {
        if(isUpvote){
          if(this.questionData.user_vote == 1){ // dont send request if already have voted
            return
          }
          this.questionData.upvotes += 1
          this.questionData.user_vote = 1
        } else { // is a downvote
          if(this.questionData.user_vote == -1){ // dont send request if already have voted
            return
          }
          if(this.questionData.user_vote == 1) {
            this.questionData.upvotes -= 1
          }
          this.questionData.user_vote = -1
        }
      }

      let response: Response

      if(this.modeAffichageVersion){
        let req = {
          value: this.versionData.user_vote,
        } as LikeDTO;
        response = await likeVersion(this.version.id, req)
      } else {
        let req = {
          value: this.questionData.user_vote,
        } as LikeDTO;

        response = await likeQuestion(this.question.id, req)
      }

      if(!response.ok){
        this.$swal({
          title: 'Erreur',
          text: 'Une erreur est survenue lors de l\'enregistrement de votre vote',
          icon: 'error',
          confirmButtonText: 'Ok'
        })
      }
      else {
        this.$swal({
          title: 'Merci',
          text: 'Votre vote a été enregistré',
          icon: 'success',
          confirmButtonText: 'Ok'
        })
      }
    },
    async likeAnswer(answerId: number, value: number){
      let response: Response
      if(this.modeAffichageVersion){
        response = await likeQuestion(answerId, {value})
      } else {
        response = await likeAnswer(answerId, {value})
      }
        if(response.ok){
          this.$swal({
            title: 'Merci',
            text: 'Votre vote a été enregistré',
            icon: 'success',
            confirmButtonText: 'Ok'
          })
          location.reload();
        }
        else {
          this.$swal({
            title: 'Erreur',
            text: 'Une erreur est survenue lors de l\'enregistrement de votre vote',
            icon: 'error',
            confirmButtonText: 'Ok'
          })
        }
    },
    async repondreQuestion() {
      let response: Response
      if(this.modeAffichageVersion){
        let req = {
          project_id: this.projectId,
          version_number: this.versionData.number,
          title:  this.inputUserReply,
          description:  this.inputUserReply,
        } as QuestionStoreDTO;
        response = await addQuestion(req)
      } else {
        let req = {
          question_id: this.questionData.id,
          title: this.inputUserReply,
          description: this.inputUserReply,
        } as AnswerStoreDTO;
        response = await addAnswer(req);
      }

      if(!response.ok){
        this.$swal({
          title: 'Erreur',
          text: "Une erreur est survenue lors de l'enregistrement de votre mot à dire, il faudrait gagner de la réputation ou vous avez déjà posé trop de questions pour aujourd'hui.",
          icon: 'error',
          confirmButtonText: 'Ok'
        })
      } else {
        this.$swal({
          title: 'Merci',
          text: 'Votre mot à dire a été enregistré',
          icon: 'success',
          confirmButtonText: 'Ok'
        });
        location.reload()
      }
      this.inputUserReply = ""; // reset field value
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
  .scrutia-clickable {
    cursor: pointer;
  }
</style>
