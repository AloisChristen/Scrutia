export const Navigation = [
  {
    name: "Accueil",
    to: '/home',
    icon: 'si si-home',
  },
  {
    name: 'Pages',
    heading: true,
  },
  {
    name: "Urne à idées",
    icon: 'si si-bulb',
    sub: [
      {
        name: "Parcourir les idées",
        to: '/browseIdeas',
      },
      {
        name: "Ajouter une idée",
        to: '/addIdea',
      },
    ],
  },
  {
    name: "Projets d'initiatives",
    to: '/browseInitiatives',
    icon: 'si si-puzzle',
  },
  {
    name: "À propos",
    to: '/about',
    icon: 'si si-question',
  },
]
