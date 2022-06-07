<template>
  <div>
    <div class="content">

      <ProjectHeader
        :project-id="$route.params.project_id"
        title="Sauver les pandas du feu"
        description="Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte. Il n'a pas fait que survivre cinq siècles, mais s'est aussi adapté à la bureautique informatique, sans que son contenu n'en soit modifié. Il a été popularisé dans les années 1960 grâce à la vente de feuilles Letraset contenant des passages du Lorem Ipsum, et, plus récemment, par son inclusion dans des applications de mise en page de texte, comme Aldus PageMaker."
        :tagList="['climat', 'energies', 'écologie']"
        :canBePromoted="projectCanBePromoted"
        ideaActionActivated
      ></ProjectHeader>

      <ProjectDiscussion
                         :project-id="$route.params.project_id"
                         :key="discussion.id"
                         :title="discussion.title"
                         :text="discussion.text"
                         :answers="discussion.answers"
                         :showLink="false"
                         completeView
      />

    </div>
  </div>
</template>

<script lang="ts">
import ProjectHeader from "@/components/ProjectHeader.vue";
import ProjectDiscussion from "@/components/ProjectDiscussion.vue";
import {getProjectDetails} from "@/api/services/ProjectsService";
export default {
  name: "IdeaDetailsDiscussion",
  components: {
    ProjectHeader,ProjectDiscussion
  },
  data() {
    return {
      discussion:  {
        id: 1,
        title: 'pourquoi la terre se rechauffe ?',
        author: 'fred dupont',
        likesCurrent: 200,
        text: "This rule reports the elements which have v-for and do not have v-bind:key with exception to custom components.",
        answers: [
          {id: 1, author: "karl", text: "vraiment d'accord", isLiked: true},
          {id: 2, author: "Fred", text: "vraiment pas d'accord", isLiked: false},
          {id: 3, author: "Gilbert", text: "vraiment d'accord", isLiked: true},
        ]
      },
      isLoaded: false,
      title: "",
      description: "",
      tagList: ["climat", "environnement", "panda"],
      projectCanBePromoted: true
    }
  },
  async mounted() {
    const project_id_str = this.$route.params.project_id;
    const response: Response = await getProjectDetails(Number(project_id_str))
    if (response.ok) {
      const data = await response.json()
      console.log(data)
    }
    this.isLoaded = true
  }
}
</script>

<style scoped>

</style>
