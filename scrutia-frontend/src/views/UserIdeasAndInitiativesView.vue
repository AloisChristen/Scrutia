<template>
  <div>
    <div class="content">
      <h2 class="content-heading">Vos idées et initiatives</h2>
      <b-row>
        <b-spinner
          variant="primary"
          label="Loading..."
          v-show="project.isLoading"
        ></b-spinner>
        <b-col sm="12" md="12" xl="12">
          <p v-show="!project.isLoading && project.items.length === 0">
            Vous n'avez actuellement ni projets ni idées.
            <br />
            <router-link :to="`/browseIdeas`">
              <em>Regarder les initiatives des autres</em>
            </router-link>
            <br />
            <router-link :to="`/addIdea`">
              <em>Créer ma propre initiative</em>
            </router-link>
          </p>
          <b-row v-show="project.items.length > 0">
            <b-col
              sm="12"
              md="6"
              xl="4"
              v-for="item in project.items"
              v-bind:key="item.id"
              v-show="!project.isLoading"
            >
              <project-component
                v-bind:project="item"
                :reducedDisplay="true"
                :isProjectInitiative="item.status !== 'idee'"
              />
            </b-col>
          </b-row>
        </b-col>
        <b-col sm="12" md="12" xl="12" v-show="project.items.length > 0">
          <b-pagination
            v-model="project.current_page"
            :total-rows="project.nb_total"
            :per-page="project.per_page"
            v-show="!project.isLoading"
            align="center"
            @change="(newValue) => changePage(newValue)"
          ></b-pagination>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script lang="ts">
import ProjectComponent from '../components/ProjectComponent.vue'
import { userProjects, userQuestions } from '@/api/services/UsersService'
import { Component, Vue } from 'vue-property-decorator'

@Component({
  name: 'UserIdeasAndInitiativesView',
  components: {
    ProjectComponent,
  },
  data() {
    return {
      project: {
        isLoading: true,
        items: [],
        nb_total: 0,
        per_page: 15,
        current_page: 0,
        last_page_url: '',
        next_page_url: '',
        prev_page_url: '',
      },
      question: {
        isLoading: true,
        items: [],
        nb_total: 0,
        per_page: 15,
        current_page: 0,
        last_page_url: '',
        next_page_url: '',
        prev_page_url: '',
      },
    }
  },
  async created() {
    if (this.checkUser()) {
      this.loadProjects()
      this.loadQuestions()
    }
  },
  methods: {
    checkUser: function () {
      if (this.$store.getters.isConnected) {
        return true
      } else {
        this.$router.push('/auth/signin')
        return false
      }
    },
    loadProjects: async function (page: number = 1) {
      this.$data.isLoading = true
      console.log(page)
      let resp = await userProjects()
      if (resp.ok) {
        let body = await resp.json()
        this.$data.project.items = body.data
        this.$data.project.nb_total = body.total
        this.$data.project.per_page = body.per_page
        this.$data.project.current_page = body.current_page
        this.$data.project.last_page_url = body.last_page_url
        this.$data.project.next_page_url = body.next_page_url
        this.$data.project.prev_page_url = body.prev_page_url
        this.$data.project.isLoading = false
      } else {
        this.$swal({
          icon: 'error',
          title: "Une erreur s'est produite lors du chargement de vos projets",
          showConfirmButton: true,
        })
        this.$data.project.isLoading = false
      }
    },
    loadQuestions: async function (page: number = 1) {
      this.$data.isLoading = true
      console.log(page)
      let resp = await userQuestions()
      if (resp.ok) {
        let body = await resp.json()
        console.log(body.data)
        this.$data.question.items = body.data
        this.$data.question.nb_total = body.total
        this.$data.question.per_page = body.per_page
        this.$data.question.current_page = body.current_page
        this.$data.question.last_page_url = body.last_page_url
        this.$data.question.next_page_url = body.next_page_url
        this.$data.question.prev_page_url = body.prev_page_url
        this.$data.question.isLoading = false
      } else {
        this.$swal({
          icon: 'error',
          title:
            "Une erreur s'est produite lors du chargement de vos questions",
          showConfirmButton: true,
        })
        this.$data.question.isLoading = false
      }
    },
  },
})
export default class UserIdeasAndInitiativesView extends Vue {}
</script>
