<template>
  <!-- Page Content -->
  <div class="hero-static">
    <div class="content">
      <b-row class="justify-content-center">
        <b-col md="8" lg="6" xl="4">
          <!-- Reminder Block -->
          <base-block
            rounded
            themed
            header-class="bg-primary-dark"
            class="mb-0"
            title="Mot de passe oublié"
          >
            <template #options>
              <router-link
                to="/auth/signin"
                class="btn-block-option"
                v-b-tooltip.hover.nofade.left="'Sign In'"
              >
                <i class="fa fa-sign-in-alt"></i>
              </router-link>
            </template>
            <div class="p-sm-3 px-lg-4 py-lg-5">
              <h1 class="h2 mb-1">{{ $store.getters.appName }}</h1>
              <p class="text-muted">
                Veuillez fournir votre email pour que nous puissions vous
                envoyez votre nouveau mot de passe.
              </p>

              <!-- Reminder Form -->
              <b-form @submit.stop.prevent="onSubmit">
                <div class="form-group py-3">
                  <b-form-input
                    size="lg"
                    class="form-control-alt"
                    id="reminder"
                    name="reminder"
                    placeholder="Email"
                    v-model="$v.form.reminder.$model"
                    :state="!$v.form.reminder.$error && null"
                    aria-describedby="reminder-feedback"
                  ></b-form-input>
                  <b-form-invalid-feedback id="reminder-feedback">
                    Veuillez fournir votre adresse email
                  </b-form-invalid-feedback>
                </div>
                <b-row class="form-group">
                  <b-col md="6" xl="6">
                    <b-button type="submit" variant="alt-primary" block>
                      <i class="fa fa-fw fa-envelope mr-1"></i> Envoyez-moi un
                      mail
                    </b-button>
                  </b-col>
                </b-row>
              </b-form>
              <!-- END Reminder Form -->
            </div>
          </base-block>
          <!-- END Reminder Block -->
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

<script>
// Vuelidate, for more info and examples you can check out https://github.com/vuelidate/vuelidate
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'

export default {
  name: 'ReminderView',
  mixins: [validationMixin],
  data() {
    return {
      form: {
        reminder: null,
      },
    }
  },
  validations: {
    form: {
      reminder: {
        required,
        minLength: minLength(3),
      },
    },
  },
  methods: {
    onSubmit() {
      this.$v.form.$touch()

      if (this.$v.form.$anyError) {
        return
      }

      // Form submit logic
      this.$router.push('/backend/pages/auth/all')
    },
  },
}
</script>
