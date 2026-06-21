import store from '@/store'

const permission = {
  install(Vue) {
    Vue.directive('permission', {
      inserted(el, binding) {
        const { value } = binding
        const permissions = store.getters.permissions

        if (value && value.length > 0) {
          const permissionList = typeof value === 'string' ? [value] : value
          const hasPermission = permissionList.some(p =>
            permissions.includes(p) || permissions.includes('*')
          )

          if (!hasPermission) {
            el.parentNode && el.parentNode.removeChild(el)
          }
        }
      }
    })

    Vue.prototype.$hasPermission = function(permission) {
      const permissions = store.getters.permissions
      if (Array.isArray(permission)) {
        return permission.some(p => permissions.includes(p) || permissions.includes('*'))
      }
      return permissions.includes(permission) || permissions.includes('*')
    }
  }
}

export default permission
