# Scrutia - Frontend :desktop_computer:

[![Linters](https://github.com/AloisChristen/Scrutia/actions/workflows/linter.yml/badge.svg)](https://github.com/AloisChristen/Scrutia/actions/workflows/linter.yml) [![dev_front_test](https://github.com/AloisChristen/Scrutia/actions/workflows/dev_front_test.yml/badge.svg)](https://github.com/AloisChristen/Scrutia/actions/workflows/dev_front_test.yml) [![main_front_test](https://github.com/AloisChristen/Scrutia/actions/workflows/main_front_test.yml/badge.svg)](https://github.com/AloisChristen/Scrutia/actions/workflows/main_front_test.yml)

## Setup development environment :building_construction:

**Install Node JS LTS**

Install LTS version available at https://nodejs.org/en/download/

**Visual Studio Code**

Install Visual Studio Code from https://code.visualstudio.com/

Add the following required extensions :

- [EditorConfig for VS Code](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig)
- [ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint)
- [Prettier - Code formatter](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)
- [Vetur](https://marketplace.visualstudio.com/items?itemName=octref.vetur)

**Install Yarn**

Install Yarn from [https://yarnpkg.com/](https://yarnpkg.com/)

Or via NPM : `npm install yarn -g`

**Install dependencies**

Use `yarn install` to get all the dependencies of the project

## Scripts available :runner:

Start the development server with hot reload enabled : `yarn serve`

Build the code for production : `yarn build`

Run unit tests with code coverage : `yarn test:unit`

Run integration tests : `yarn test:e2e`

Run linter to check code quality : `yarn lint`

Run the server that serve the built application : `yarn start`

> To run this command, you should have built the project before

## Technologies used :books:

Project uses : [Vue JS](https://vuejs.org/), [Vue Router](https://router.vuejs.org/), [Vuex](https://vuex.vuejs.org/), [OneUI with Bootstrap theme](https://demo.pixelcave.com/oneui/), [Eslint](https://eslint.org/), [Jest](https://jestjs.io/fr/), [Cypress](https://go.cypress.io/)
