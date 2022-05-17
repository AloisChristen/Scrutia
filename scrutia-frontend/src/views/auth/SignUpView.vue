<template>
  <!-- Page Content -->
  <div class="hero-static">
    <div class="content">
      <b-row class="justify-content-center">
        <b-col md="8" lg="6" xl="4">
          <!-- Sign Up Block -->
          <base-block
            rounded
            themed
            class="mb-0"
            header-class="bg-primary-dark"
            title="Create Account"
          >
            <template #options>
              <!-- Terms Modal -->
              <button
                type="button"
                class="btn-block-option font-size-sm"
                v-b-modal.one-signup-terms
              >
                Voir les Termes
              </button>
              <b-modal
                id="one-signup-terms"
                size="lg"
                body-class="p-0"
                hide-footer
                hide-header
              >
                <div
                  class="block block-rounded block-themed block-transparent mb-0"
                >
                  <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Termes &amp; Conditions</h3>
                    <div class="block-options">
                      <button
                        type="button"
                        class="btn-block-option"
                        @click="$bvModal.hide('one-signup-terms')"
                      >
                        <i class="fa fa-fw fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="block-content">
                    <p>
                      L'utilisateur s'engage à ne pas proférer des propos
                      inconvenants.
                    </p>
                  </div>
                  <div
                    class="block-content block-content-full text-right border-top"
                  >
                    <b-button
                      variant="alt-primary"
                      class="mr-1"
                      @click="$bvModal.hide('one-signup-terms')"
                      >Fermer</b-button
                    >
                    <b-button
                      variant="primary"
                      @click="$bvModal.hide('one-signup-terms')"
                      >J'accepte</b-button
                    >
                  </div>
                </div>
              </b-modal>
              <!-- END Terms Modal -->

              <router-link
                to="/auth/signin"
                class="btn-block-option"
                v-b-tooltip.hover.nofade.left="'Sign In'"
              >
                <i class="fa fa-sign-in-alt"></i>
              </router-link>
            </template>
            <div class="p-sm-3 px-lg-4 py-lg-5">
              <h1 class="h2 mb-1">OneUI</h1>
              <p class="text-muted">Veuillez remplir les champs suivants</p>

              <!-- Sign Up Form -->
              <b-form @submit.stop.prevent="onSubmit">
                <div class="py-3">
                  <div><h5>Informations de compte</h5></div>
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
                      Veuillez entrer votre nom d'utilisateur
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-group">
                    <b-form-input
                      type="email"
                      size="lg"
                      class="form-control-alt"
                      id="email"
                      name="email"
                      placeholder="Email"
                      v-model="$v.form.email.$model"
                      :state="!$v.form.email.$error && null"
                      aria-describedby="email-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback id="email-feedback">
                      Veuillez entrer votre email
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
                      Veuillez fournir un mot de passe
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-group">
                    <b-form-input
                      type="password"
                      size="lg"
                      class="form-control-alt"
                      id="password_confirmation"
                      name="password_confirmation"
                      placeholder="Confirmation du mot de passe"
                      v-model="$v.form.password_confirmation.$model"
                      :state="!$v.form.password_confirmation.$error && null"
                      aria-describedby="password_confirmation-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback
                      id="password_confirmation-feedback"
                    >
                      Veuillez confirmer votre mot de passe
                    </b-form-invalid-feedback>
                  </div>
                  <div><h5>Informations personnelles</h5></div>
                  <div class="form-group">
                    <b-form-input
                      size="lg"
                      class="form-control-alt"
                      id="firstname"
                      name="firstname"
                      placeholder="Prénom"
                      v-model="$v.form.firstname.$model"
                      :state="!$v.form.firstname.$error && null"
                      aria-describedby="firstname-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback id="firstname-feedback">
                      Veuillez entrer votre prénom
                    </b-form-invalid-feedback>
                  </div>
                  <div class="form-group">
                    <b-form-input
                      size="lg"
                      class="form-control-alt"
                      id="lastname"
                      name="lastname"
                      placeholder="Nom de famille"
                      v-model="$v.form.lastname.$model"
                      :state="!$v.form.lastname.$error && null"
                      aria-describedby="lastname-feedback"
                    ></b-form-input>
                    <b-form-invalid-feedback id="lastname-feedback">
                      Veuillez entrer votre nom de famille
                    </b-form-invalid-feedback>
                  </div>

                  <div class="form-group">
                    <b-form-checkbox
                      id="terms"
                      name="terms"
                      v-model="$v.form.terms.$model"
                      :state="!$v.form.terms.$error && null"
                      aria-describedby="terms-feedback"
                      >J'accepte les Termes &amp; Conditions</b-form-checkbox
                    >
                    <b-form-invalid-feedback
                      id="terms-feedback"
                      :state="
                        $v.form.terms.$dirty ? !$v.form.terms.$error : null
                      "
                    >
                      Vous devez accepter les termes et conditions
                    </b-form-invalid-feedback>
                  </div>
                </div>
                <b-row class="form-group">
                  <b-col md="6" xl="5">
                    <b-button type="submit" variant="alt-success" block>
                      <i class="fa fa-fw fa-plus mr-1"></i> S'inscrire
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>
              <!-- END Sign Up Form -->
            </div>
          </base-block>
          <!-- END Sign Up Block -->
        </b-col>
      </b-row>
    </div>
    <div class="content content-full font-size-sm text-muted text-center">
      <strong>{{
        $store.getters.appName + ' ' + $store.getters.appVersion
      }}</strong>
      &copy; {{ $store.getters.appCopyright }}
    </div>
  </div>
  <!-- END Page Content -->
</template>

<script lang="ts">
// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import { validationMixin } from 'vuelidate'
import { required, minLength, email, sameAs } from 'vuelidate/lib/validators'
import { RegisterAccountDTO } from '@/typings/scrutia-types'
import { register } from '@/api/services/AuthService'

export default {
  mixins: [validationMixin],
  data() {
    return {
      form: {
        username: null,
        firstname: null,
        lastname: null,
        email: null,
        password: null,
        password_confirmation: null,
      },
    }
  },
  validations: {
    form: {
      username: {
        required,
        minLength: minLength(3),
      },
      firstname: {
        required,
      },
      lastname: {
        required,
      },
      email: {
        required,
        email,
      },
      password: {
        required,
        minLength: minLength(5),
      },
      password_confirmation: {
        required,
        sameAsPassword: sameAs('password'),
      },
      terms: {
        sameAs: sameAs(() => true),
      },
    },
  },
  methods: {
    onSubmit() {
      this.$v.form.$touch()

      const account = this.form as RegisterAccountDTO
      if (this.$v.form.$anyError) {
        return
      }

      // Form submit logic

      // TODO threat case when not connected
      register(account).then((session) => {
        this.$store.commit('session', session)
        console.log(this.$store.getters.authToken)
        this.$router.push('/')
      })
    },
  },
}
</script>
