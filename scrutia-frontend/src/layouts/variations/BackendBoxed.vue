<template>
  <base-layout :layout-classes="layoutClasses">
    <template #header>
      <div class="content-header">
        <div class="d-flex align-items-center">
          <router-link
            to="/"
            class="font-w600 font-size-h5 tracking-wider text-dual mr-3"
          >
            {{ $store.getters.appName }}
          </router-link>
        </div>
        <div class="d-flex align-items-center">
          <base-layout-modifier
            action="headerSearchOn"
            variant="dual"
            size="sm"
            class="d-sm-none"
          >
            <i class="fa fa-search"></i>
          </base-layout-modifier>
          <b-form
            @submit.stop.prevent="onSubmit"
            class="d-none d-sm-inline-block"
          >
            <b-input-group size="sm">
              <b-form-input
                id="searchInput"
                placeholder="Rechercher..."
                v-model="baseSearchTerm"
                class="form-control-alt"
              ></b-form-input>
              <b-input-group-append>
                <span class="input-group-text bg-body border-0">
                  <i class="fa fa-search"></i>
                </span>
              </b-input-group-append>
            </b-input-group>
          </b-form>
          <user-profile-component></user-profile-component>
        </div>
      </div>
      <div
        id="page-header-search"
        class="overlay-header bg-white"
        :class="{ show: $store.state.settings.headerSearch }"
        @keydown.esc="() => $store.commit('headerSearch', { mode: 'off' })"
      >
        <div class="content-header">
          <b-form @submit.stop.prevent="onSubmit" class="w-100">
            <b-input-group>
              <b-form-input
                placeholder="Search or hit ESC.."
                v-model="baseSearchTerm"
              ></b-form-input>
              <b-input-group-append>
                <base-layout-modifier
                  action="headerSearchOff"
                  variant="alt-danger"
                >
                  <i class="fa fa-fw fa-times-circle"></i>
                </base-layout-modifier>
              </b-input-group-append>
            </b-input-group>
          </b-form>
        </div>
      </div>
      <div
        id="page-header-loader"
        class="overlay-header bg-primary-lighter"
        :class="{ show: $store.state.settings.headerLoader }"
      >
        <div class="content-header">
          <div class="w-100 text-center">
            <i class="fa fa-fw fa-circle-notch fa-spin text-primary"></i>
          </div>
        </div>
      </div>
    </template>
    <template #content>
      <div class="bg-white">
        <div class="content py-3">
          <div class="d-lg-none">
            <b-button
              variant="alt-secondary"
              block
              class="d-flex justify-content-between align-items-center"
              v-toggle-class="{
                targetId: 'horizontal-navigation',
                class: 'd-none',
              }"
            >
              Menu
              <i class="fa fa-bars"></i>
            </b-button>
          </div>
          <div
            id="horizontal-navigation"
            class="d-none d-lg-block mt-2 mt-lg-0"
          >
            <base-navigation
              :nodes="navigation"
              horizontal
              horizontal-hover
            ></base-navigation>
          </div>
        </div>
      </div>
    </template>
  </base-layout>
</template>

<script>
import BaseLayout from '../Base'
import { Navigation } from '../../router/navigation'
import UserProfileComponent from '../../components/UserProfil/UserProfileComponent.vue'

export default {
  name: 'LayoutBackend',
  components: {
    BaseLayout,
    UserProfileComponent,
  },
  data() {
    return {
      // Override and set custom CSS classes to the container of each base layout element
      layoutClasses: {
        sideOverlay: '',
        sidebar: '',
        header: '',
        footer: '',
      },
      navigation: Navigation,
      baseSearchTerm: '',
      notifications: [],
    }
  },
  methods: {
    onSubmit() {
      this.$router.push('/search?question=' + this.baseSearchTerm)
    },
    eventHeaderSearch(event) {
      // When ESCAPE key is hit close the header search section
      if (event.which === 27) {
        event.preventDefault()
        this.$store.commit('headerSearch', { mode: 'off' })
      }
    },
    isConnected: function () {
      return this.$store.getters.isConnected
    },
  },
  computed: {},
  mounted() {
    document.addEventListener('keydown', this.eventHeaderSearch)
  },
  destroyed() {
    document.removeEventListener('keydown', this.eventHeaderSearch)
  },
  created() {
    // Set default elements for this layout
    this.$store.commit('setLayout', {
      header: true,
    })

    // Set various template options
    this.$store.commit('headerStyle', { mode: 'dark' })
    this.$store.commit('mainContent', { mode: 'boxed' })
  },
}
</script>
