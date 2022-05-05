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
                placeholder="Search.."
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
          <b-dropdown
            size="sm"
            variant="dual"
            class="d-inline-block ml-2"
            menu-class="p-0 border-0 font-size-sm"
            right
            no-caret
            ref="oneDropdownBoxedUser"
          >
            <template #button-content>
              <div class="d-flex align-items-center">
                <img
                  class="rounded-circle"
                  src="img/avatars/avatar10.jpg"
                  alt="Header Avatar"
                  style="width: 21px"
                />
                <span class="d-none d-sm-inline-block ml-2">Adam</span>
                <i
                  class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"
                ></i>
              </div>
            </template>
            <li @click="$refs.oneDropdownBoxedUser.hide(true)">
              <div class="p-3 text-center bg-primary-dark rounded-top">
                <img
                  class="img-avatar img-avatar48 img-avatar-thumb"
                  src="img/avatars/avatar10.jpg"
                  alt="Avatar"
                />
                <p class="mt-2 mb-0 text-white font-w500">Adam Smith</p>
                <p class="mb-0 text-white-50 font-size-sm">Web Developer</p>
              </div>
              <div class="p-2">
                <router-link
                  class="dropdown-item d-flex align-items-center justify-content-between"
                  to="/favorites"
                >
                  <span class="font-size-sm font-w500">Favoris</span>
                  <span class="badge badge-pill badge-primary ml-2">3</span>
                </router-link>
                <router-link
                  class="dropdown-item d-flex align-items-center justify-content-between"
                  to="/userIdeasAndInitiatives"
                >
                  <span class="font-size-sm font-w500"
                    >Idées et initiatives</span
                  >
                  <span class="badge badge-pill badge-primary ml-2">1</span>
                </router-link>
                <div role="separator" class="dropdown-divider"></div>
                <router-link
                  class="dropdown-item d-flex align-items-center justify-content-between"
                  to="/userProfile"
                >
                  <span class="font-size-sm font-w500">Profil</span>
                </router-link>
                <router-link
                  class="dropdown-item d-flex align-items-center justify-content-between"
                  to="/auth/signin"
                >
                  <span class="font-size-sm font-w500">Se déconnecter</span>
                </router-link>
              </div>
            </li>
          </b-dropdown>
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

export default {
  name: 'LayoutBackend',
  components: {
    BaseLayout,
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
      this.$router.push('/backend-boxed/search?' + this.baseSearchTerm)
    },
    eventHeaderSearch(event) {
      // When ESCAPE key is hit close the header search section
      if (event.which === 27) {
        event.preventDefault()
        this.$store.commit('headerSearch', { mode: 'off' })
      }
    },
  },
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
