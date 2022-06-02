<template>
  <base-block
    :title="shortedTitle"
    header-bg
    rounded
    tag="a"
    link-pop
    style="cursor: auto"
  >
    <template #options>
      <div @click="() => {}">
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
        <button type="button" class="btn-block-option" @click="openProject">
          <i class="si si-eye" />
        </button>
      </div>
    </template>
    <div @click="openProject" style="cursor: pointer">
      <p class="custom-font-size">{{ shortedDescription }}</p>
      <address v-show="isProjectInitiative">
        <a class="custom-font-size">{{ project.author }}</a
        ><em class="custom-font-size">, le {{ getFormatedDate() }}</em
        ><br />
        <b-badge
          style="margin-right: 5px"
          v-for="tag in project.tags"
          v-bind:key="tag.title"
          :variant="getNextColor()"
          >{{ tag.title }}</b-badge
        >
      </address>
      <address v-show="!isProjectInitiative" class="custom-font-size">
        <i class="fa fa-thumbs-up custom-font-size" />
        {{ nblikes }} personnes soutiennent déjà l'idée
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
      this.$router.replace({
        path: `/project/${this.project.id}#`,
        replace: true,
      })
    },
    async likeProject() {
      switch (this.$data.like) {
        case DISLIKE:
          likeProject(this.project.id, NO_LIKE)
            .then(() => {
              this.$data.like = NO_LIKE
            })
            .catch((error) => {
              this.handleError(error)
            })
          break
        case NO_LIKE:
          likeProject(this.project.id, LIKE)
            .then(() => {
              this.$data.like = LIKE
              this.$data.nblikes += 1
            })
            .catch((error) => {
              this.handleError(error)
            })
          break
        case LIKE:
          likeProject(this.project.id, DISLIKE)
            .then(() => {
              this.$data.like = LIKE
              this.$data.nblikes += 1
            })
            .catch((error) => {
              this.handleError(error)
            })
          break
      }
    },
    async handleError(error: Response) {
      // TODO : Check if it works
      const body = await error.json()
      if (error.status === 403 && body.errors[0].reputation !== undefined) {
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
