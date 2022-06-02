<template>
  <b-spinner variant="primary" label="Loading..."></b-spinner>
  <!-- END Page Content -->
</template>

<script lang="ts">
import { logout } from '@/api/services/AuthService'
export default {
  beforeCreate() {
    logout().then(() => {
      this.$store.commit('disconnect')
      this.$router.push('/')
    })
  },
}
</script>
