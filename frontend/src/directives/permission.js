import store from '@/store'

function checkPermission(el, binding) {
  const { value } = binding
  const permissions = store.getters.permissions

  if (value && value.length > 0) {
    const permissionList = typeof value === 'string' ? [value] : value
    const hasPermission = permissionList.some(p =>
      permissions.includes(p) || permissions.includes('*')
    )

    if (!hasPermission) {
      if (el.parentNode) {
        el.parentNode.removeChild(el)
      } else {
        el.style.display = 'none'
      }
    } else {
      el.style.display = ''
    }
  }
}

const permission = {
  install(Vue) {
    Vue.directive('permission', {
      inserted(el, binding) {
        checkPermission(el, binding)
      },
      componentUpdated(el, binding) {
        checkPermission(el, binding)
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
