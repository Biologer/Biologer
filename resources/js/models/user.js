import _intersection from 'lodash/intersection'

export default class User {
  constructor(data) {
    for (let attribute in data) {
      this[attribute] = data[attribute];
    }
  }

  hasRole(roles) {
    if (!Array.isArray(roles)) {
      roles = [roles]
    }

    return !!_intersection(this.roles.map(role => role.name), roles).length;
  }
}
