<template>
  <!-- Page Content -->
  <div class="hero-static">
    <div class="content">
      <b-row class="justify-content-center">
        <b-col md="8" lg="8" xl="6">
          <!-- Sign In Block -->
          <base-block
            rounded
            themed
            class="mb-0"
            header-class="bg-primary-dark"
            title="Connexion"
          >
            <template #options>
              <router-link
                to="/auth/reminder"
                class="btn-block-option font-size-sm"
                >Mot de passe oublié?</router-link
              >
              <router-link
                to="/auth/signup"
                class="btn-block-option"
                v-b-tooltip.hover.nofade.left="'New Account'"
              >
                <i class="fa fa-user-plus"></i>
              </router-link>
            </template>
            <div class="p-sm-3 px-lg-3 py-lg-3">
              <h1 class="h2 mb-1">{{ $store.getters.appName }}</h1>
              <p class="text-muted">Bienvenue, veuillez vous connecter.</p>

              <!-- Sign In Form -->
              <b-form @submit.stop.prevent="onSubmit">
                <div class="py-3">
                  <div class="form-group">
                    <b-form-input
                      size="lg"
                      class="form-control-alt"
                      id="username"
                      name="username"
                      placeholder="Nom d'utilisateur"
                      v-model="$v.form.username.$model"
                      :state="!$v.form.username.$error && null"
                      aria-describedby="username-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback id="username-feedback">
                      Doit être remplit
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-group">
                    <b-form-input
                      type="password"
                      size="lg"
                      class="form-control-alt"
                      id="password"
                      name="password"
                      placeholder="Mot de passe"
                      v-model="$v.form.password.$model"
                      :state="!$v.form.password.$error && null"
                      aria-describedby="password-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback id="password-feedback">
                      Doit être remplit
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <b-row class="form-group">
                  <b-col md="6" xl="6">
                    <b-button
                      class="mb-4"
                      type="submit"
                      variant="alt-primary"
                      block
                    >
                      <i class="fa fa-fw fa-sign-in-alt mr-1"></i> Se connecter
                    </b-button>
                  </b-col>
                  <b-col md="6" xl="6">
                    <b-button
                      variant="dual"
                      @click="$router.replace('/home')"
                      block
                    >
                      <i class="fa fa-undo mr-1"></i> Retour
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>

              <!-- END Sign In Form -->
            </div>
          </base-block>
          <!-- END Sign In Block -->
        </b-col>
      </b-row>
    </div>
    <div class="content content-full font-size-sm text-muted text-center">
      <strong>{{ $store.getters.appName }}</strong>
      &copy; {{ $store.getters.appCopyright }}
    </div>
  </div>
  <!-- END Page Content -->
</template>

<script lang="ts">
import { LoginDTO } from '@/typings/scrutia-types'
// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'
import { login } from '@/api/services/AuthService'

export default {
  name: 'SignInView',
  mixins: [validationMixin],
  data() {
    return {
      form: {
        username: null,
        password: null,
      },
    }
  },
  validations: {
    form: {
      username: {
        required,
        minLength: minLength(3),
      },
      password: {
        required,
        minLength: minLength(5),
      },
    },
  },
  methods: {
    onSubmit() {
      this.$v.form.$touch()

      const account = this.form as LoginDTO

      if (this.$v.form.$anyError) {
        return
      }

      // TODO threat case when not connected
      login(account).then(async (resp) => {
        let session: LoginDTO = (await resp.json()) as LoginDTO
        this.$store.commit('connect', session)
        this.$router.push('/')
      })
    },
  },
}
</script>
