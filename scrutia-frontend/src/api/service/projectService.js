import { api } from "../api";

class ProjectService {

  constructor(api) {
    this.api = api
  }


}

let projectService = new ProjectService(api);

export { userService }
