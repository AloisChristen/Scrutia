import {
  PAGE_HOME,
  PAGE_IDEAS,
  PAGE_BROWSE_IDEAS,
  PAGE_ADD_IDEA,
  PAGE_PROJECTS,
  PAGE_ABOUT,
} from '../strings'

export const Navigation = [
  {
    name: PAGE_HOME,
    to: '/home',
    icon: 'si si-home',
  },
  {
    name: 'Pages',
    heading: true,
  },
  {
    name: PAGE_IDEAS,
    icon: 'si si-bulb',
    sub: [
      {
        name: PAGE_BROWSE_IDEAS,
        to: '/browseIdeas',
      },
      {
        name: PAGE_ADD_IDEA,
        to: '/addIdea',
      },
    ],
  },
  {
    name: PAGE_PROJECTS,
    to: '/browseInitiatives',
    icon: 'si si-puzzle',
  },
  {
    name: PAGE_ABOUT,
    to: '/about',
    icon: 'si si-question',
  },
]
