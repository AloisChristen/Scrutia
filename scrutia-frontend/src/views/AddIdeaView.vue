<template>
  <div class="content" style="margin-bottom: 30px">
    <b-row class="justify-content-center">
      <b-spinner
        variant="primary"
        label="Loading..."
        v-show="isLoading"
      ></b-spinner>
      <b-col md="8" lg="8" xl="8" v-show="!isLoading">
        <base-block
          rounded
          themed
          class="mb-0"
          header-class="bg-primary-dark"
          title="Ajout de l'idée"
        >
          <template #options>
            <i
              class="fa fa-question"
              v-b-tooltip.hover.bottom="
                'Saisissez un titre à votre idée et ajoutez une description complète qui présente l\'ensemble de vos motivations. Choisissez des tags pour permettre à votre idée d\'être facilement recherchée. Une fois celle-ci publiée, vous devrez atteindre un nombre de soutient s\'élevant à 50 pour pouvoir la convertir en projet d\'initiative.'
              "
            ></i>
          </template>
          <div class="p-sm-2 px-lg-2 py-lg-2">
            <h1 class="h4 mb-1 mt-1">Vous avez pris la bonne décision !</h1>
            <p class="text-muted">Remplissez le formulaire suivant</p>
            <b-form @submit.stop.prevent="onSubmit">
              <div class="py-3">
                <div class="form-group">
                  <b-form-input
                    size="lg"
                    id="title"
                    name="title"
                    placeholder="Choisissez un titre..."
                    v-model="$v.form.title.$model"
                    :state="!$v.form.title.$error && null"
                    aria-describedby="title-feedback"
                  ></b-form-input>
                  <b-form-invalid-feedback id="title-feedback">
                    Le titre doit au moins contenir 10 caractères
                  </b-form-invalid-feedback>
                </div>
                <div class="form-group">
                  <b-form-textarea
                    size="lg"
                    id="description"
                    name="description"
                    placeholder="Décrivez votre idée..."
                    v-model="$v.form.description.$model"
                    :state="!$v.form.description.$error && null"
                    aria-describedby="description-feedback"
                    rows="4"
                  ></b-form-textarea>
                  <b-form-invalid-feedback id="description-feedback">
                    La description doit au moins contenir 50 caractères
                  </b-form-invalid-feedback>
                </div>
                <div class="form-group">
                  <v-select
                    id="tags"
                    size="lg"
                    class="form-control-alt"
                    taggable
                    multiple
                    v-model="$v.form.tags.$model"
                    :options="options"
                    placeholder="Définissez des tags..."
                  >
                  </v-select>
                </div>
              </div>
              <b-row class="form-group">
                <b-col md="6" xl="6">
                  <b-button
                    class="mb-4"
                    type="submit"
                    variant="alt-success"
                    block
                    :disabled="
                      this.form.title === '' ||
                      this.form.description === '' ||
                      this.form.tags.length === 0 ||
                      $v.form.$anyError
                    "
                  >
                    <i class="fa fa-lightbulb mr-1"></i> Publier votre idée
                  </b-button>
                </b-col>
                <b-col md="6" xl="6">
                  <b-button variant="alt-warning" block @click="onClear">
                    <i class="fa fa-trash mr-1"></i> Réinitialiser
                  </b-button>
                </b-col>
              </b-row>
            </b-form>
          </div>
        </base-block>
      </b-col>
    </b-row>
  </div>
</template>

<style lang="scss">
@import '~vue-select/src/scss/vue-select';
@import './src/assets/scss/vendor/vue-select';
</style>

<script lang="ts">
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'
import VueSelect from 'vue-select'
import { ProjectStore } from '@/typings/scrutia-types'
import { addProject } from '@/api/services/ProjectsService'
import { getTags } from '@/api/services/TagsService'
import { TagDTO } from '@/typings/scrutia-types'

export default {
  name: 'AddIdeaView',
  components: {
    'v-select': VueSelect,
  },
  mixins: [validationMixin],
  data() {
    return {
      form: {
        title: null,
        description: null,
        tags: [],
      },
      options: [],
      isLoading: true,
    }
  },
  async created() {
    if (!this.isUserConnected()) this.$router.push('/auth/signin')
    const response: Response = await getTags()
    if (response.ok) {
      const tags = await response.json()
      this.$data.options = tags.map((tag: TagDTO) => tag.title)
    } else {
      this.$swal({
        icon: 'error',
        title: "Une erreur s'est produite lors du chargement des tags",
        showConfirmButton: true,
      })
    }
    this.isLoading = false
  },
  validations: {
    form: {
      title: {
        required,
        minLength: minLength(10),
      },
      description: {
        required,
        minLength: minLength(50),
      },
      tags: {},
    },
  },
  methods: {
    isUserConnected() {
      return this.$store.getters.isConnected
    },
    onClear() {
      this.form.title = ''
      this.form.description = ''
      this.form.tags = []
      this.$v.$reset()
    },
    async onSubmit() {
      this.$v.form.$touch()
      if (this.$v.form.$anyError) {
        return
      }

      this.isLoading = true

      const tags = this.form.tags.map((tag: string) => {
        return { title: tag }
      })

      const ideaToAdd: ProjectStore = {
        title: this.form.title,
        description: this.form.description,
        tags: tags,
      }

      const response: Response = await addProject(ideaToAdd)
      if (response.ok) {
        this.$swal({
          icon: 'success',
          title: 'Votre idée a été enregistrée',
          showConfirmButton: false,
          timer: 1500,
        })
        this.$router.push('/')
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors de l'enregistrement",
          showConfirmButton: true,
        })
      }
      this.isLoading = false
    },
  },
}
</script>
