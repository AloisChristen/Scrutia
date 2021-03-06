<template>
  <base-block
    :title="shortedTitle"
    header-bg
    rounded
    style="cursor: auto"
    link-shadow
  >
    <template #options>
      <div>
        <div
          v-show="isNew && isProjectInitiative"
          class="block-options-item text-primary-light custom-font-size"
        >
          Nouveau !
        </div>
        <div class="block-options-item text-success custom-font-size">
          {{ project.performance }}
        </div>
        <button
          type="button"
          class="btn-block-option"
          @click="likeProject"
          v-show="isUserConnected()"
        >
          <i
            v-bind:class="[
              { fa: like !== 0 },
              { 'fa-thumbs-up': like === 1 },
              { 'fa-thumbs-down': like === -1 },
              { si: like === 0 },
              { 'si-like': like === 0 },
            ]"
          />
        </button>
        <button
          type="button"
          class="btn-block-option"
          @click="addToFavorites"
          v-show="isUserConnected()"
        >
          <i
            v-bind:class="[
              { fa: isFavorite },
              { 'fa-star': isFavorite },
              { si: !isFavorite },
              { 'si-star': !isFavorite },
            ]"
          />
        </button>
      </div>
    </template>
    <div>
      <p class="custom-font-size" style="text-align: justify">
        {{ shortedDescription }}
      </p>
      <address v-show="isProjectInitiative"></address>
      <address class="custom-font-size">
        <div v-show="!isProjectInitiative">
          <i class="fa fa-thumbs-up custom-font-size" />
          {{ nblikes }} personnes aiment déjà
          {{ isProjectInitiative ? 'ce projet' : 'cette idée' }}...{{ ' ' }}
          <router-link :to="`/project/${project.id}`">
            <em>consulter le détail...</em>
          </router-link>
        </div>
        <div v-show="isProjectInitiative">
          <b-badge
            style="margin-right: 5px"
            v-for="tag in project.tags"
            v-bind:key="tag.title"
            :variant="getNextColor()"
            >{{ tag.title }}</b-badge
          ><br />
          <a class="custom-font-size">{{ project.author }}</a
          ><em class="custom-font-size">, le {{ getFormatedDate() }}... </em>
          <router-link :to="`/project/${project.id}`">
            <em>consulter le détail...</em>
          </router-link>
        </div>
      </address>
    </div>
  </base-block>
</template>
<script lang="ts">
import { addFavorite, deleteFavorite } from '@/api/services/FavoritesService'
import { likeProject } from '@/api/services/ProjectsService'
import { format } from 'date-fns'
import frenchLocale from 'date-fns/locale/fr'
const COLOR_VARIANTS = ['primary', 'success', 'info', 'warning', 'danger']
let currentColor = 0
const DISLIKE = -1
const LIKE = 1
const NO_LIKE = 0

export default {
  name: 'ProjectComponent',
  props: {
    project: {
      type: Object,
      description: 'The project to display',
    },
    reducedDisplay: {
      type: Boolean,
      description: 'If the component is in reduced stated',
    },
    isProjectInitiative: {
      type: Boolean,
      description: 'If the project is in initiative state',
    },
  },
  data() {
    return {
      isFavorite: false,
      like: this.project.user_vote,
      nblikes: this.project.likes_count,
    }
  },
  methods: {
    isUserConnected() {
      return this.$store.getters.isConnected
    },
    getFormatedDate() {
      if (this.project.created_at === undefined) return ''
      return format(new Date(this.project.created_at), 'dd LLLL yyyy', {
        locale: frenchLocale,
      })
    },
    getNextColor() {
      const color = COLOR_VARIANTS[currentColor]
      currentColor =
        currentColor >= COLOR_VARIANTS.length ? 0 : currentColor + 1
      return color
    },
    addToFavorites() {
      this.$data.isFavorite = !this.$data.isFavorite
      if (this.$data.isFavorite) addFavorite(this.project.id)
      else deleteFavorite(this.project.id)
    },
    openProject() {
      this.$router.push({
        path: `/project/${this.project.id}#`,
        replace: true,
      })
    },
    async likeProject() {
      let response: Response = new Response()
      switch (this.$data.like) {
        case DISLIKE:
          this.$data.like = NO_LIKE
          response = await likeProject(this.project.id, NO_LIKE)
          if (!response.ok) {
            this.$data.like = NO_LIKE
            this.handleError(response)
          }
          break
        case NO_LIKE:
          this.$data.like = LIKE
          this.$data.nblikes += 1
          response = await likeProject(this.project.id, LIKE)
          if (!response.ok) {
            this.$data.like = NO_LIKE
            this.$data.nblikes -= 1
            this.handleError(response)
          }
          break
        case LIKE:
          this.$data.like = DISLIKE
          this.$data.nblikes -= 1
          response = await likeProject(this.project.id, DISLIKE)
          if (!response.ok) {
            this.$data.nblikes += 1
            this.$data.like = NO_LIKE
            this.handleError(response)
          }
          break
      }
    },
    async handleError(response: Response) {
      const body = await response.json()
      if (response.status === 403 && body.errors.reputation !== undefined) {
        this.$swal({
          icon: 'error',
          title:
            "Vous n'avez pas la réputation nécessaire pour effectuer cette action",
          showConfirmButton: true,
        })
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors de l'action",
          showConfirmButton: true,
        })
      }
    },
  },
  created() {
    this.$data.isFavorite = this.project.is_favorite
  },
  computed: {
    isNew() {
      return (
        604800 <
        new Date().getTime() - new Date(this.project.created_at).getTime()
      )
    },
    shortedTitle() {
      if (this.project.title === null) return 'Aucun titre...'
      if (this.project.title.length > 40)
        return this.project.title.substring(0, 37) + '...'
      else return this.project.title
    },
    shortedDescription() {
      if (this.project.last_description === null) return 'Aucune description...'
      if (this.project.last_description.length > 200)
        return this.project.last_description.substring(0, 197) + '...'
      else return this.project.last_description
    },
  },
}
</script>

<style scoped>
.custom-font-size {
  font-size: 12px;
}
</style>
