<template>
  <div>
    <template v-if="isConnected()">
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
          <div class="d-flex align-items-center" id="userProfile">
            <img
              class="rounded-circle"
              :src="getAvatar()"
              alt="Header Avatar"
              style="width: 21px"
            />
            <span class="d-none d-sm-inline-block ml-2">
              {{ getUsername() }}
            </span>
            <i
              class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"
            ></i>
          </div>
        </template>
        <li @click="$refs.oneDropdownBoxedUser.hide(true)">
          <div class="p-3 text-center bg-primary-dark rounded-top">
            <img
              class="img-avatar img-avatar48 img-avatar-thumb"
              :src="getAvatar()"
              alt="Avatar"
            />
            <p class="mt-2 mb-0 text-white font-w500">
              {{ getUsername() }}
            </p>
          </div>
          <div class="p-2">
            <router-link
              class="dropdown-item d-flex align-items-center justify-content-between"
              to="/favorites"
            >
              <span class="font-size-sm font-w500">Mes favoris</span>
            </router-link>
            <router-link
              class="dropdown-item d-flex align-items-center justify-content-between"
              to="/userIdeasAndInitiatives"
            >
              <span class="font-size-sm font-w500"
                >Mes idées et initiatives</span
              >
            </router-link>
            <div role="separator" class="dropdown-divider"></div>
            <!-- <router-link
              class="dropdown-item d-flex align-items-center justify-content-between"
              to="/userProfile"
            >
              <span class="font-size-sm font-w500">Profil</span>
            </router-link> -->
            <router-link
              class="dropdown-item d-flex align-items-center justify-content-between"
              to="/auth/signin"
              @click.native="disconnect()"
            >
              <span class="font-size-sm font-w500">Se déconnecter</span>
            </router-link>
          </div>
        </li>
      </b-dropdown>
    </template>
    <template v-else>
      <b-button
        size="sm"
        variant="dual"
        class="d-inline-block ml-2"
        menu-class="p-0 border-0 font-size-sm"
        to="auth/signin"
        id="noUserProfile"
        block
      >
        <i class="fa fa-sign-in-alt mr-1"></i> Connexion
      </b-button>
    </template>
  </div>
</template>
<script lang="ts">
export default {
  name: 'UserProfileComponent',

  data() {
    return {}
  },
  methods: {
    isConnected: function () {
      return this.$store.getters.isConnected
    },
    toSignIn: function () {
      this.$router.push('/auth/signin')
    },
    disconnect: function () {
      this.$store.commit('disconnect')
    },
    getUsername: function () {
      let user = this.$store.getters.currentUser
      if (user == undefined) {
        return 'No user'
      } else {
        return user.username
      }
    },

    getAvatar: function () {
      return (
        'https://ui-avatars.com/api/?name=' + this.getUsername() + '&bold=true'
      )
    },
  },
}
</script>
