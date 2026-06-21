import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'

Vue.use(VueRouter)

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/Login/index.vue'),
    meta: { title: '登录', requiresAuth: false }
  },
  {
    path: '/',
    redirect: '/dashboard'
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('@/views/Dashboard/index.vue'),
    meta: { title: '数据概览', requiresAuth: true }
  },
  {
    path: '/roles',
    name: 'RoleManagement',
    component: () => import('@/views/Role/Index.vue'),
    meta: { title: '角色管理', requiresAuth: true, permission: 'role.view' }
  },
  {
    path: '/system/users',
    name: 'UserManagement',
    component: () => import('@/views/System/Users.vue'),
    meta: { title: '用户管理', requiresAuth: true, permission: 'user.view' }
  },
  {
    path: '*',
    name: 'NotFound',
    component: () => import('@/views/NotFound/index.vue'),
    meta: { title: '页面不存在' }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: import.meta.env.VITE_APP_BASE_URL || '/',
  routes
})

router.beforeEach((to, from, next) => {
  document.title = to.meta.title ? `${to.meta.title} - 电商订单库存后台` : '电商订单库存后台'

  const token = localStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    next({ path: '/login', query: { redirect: to.fullPath } })
    return
  }

  if (to.meta.permission && token) {
    const permissions = store.getters.permissions
    const hasPermission = permissions.includes(to.meta.permission) || permissions.includes('*')
    if (!hasPermission) {
      next({ path: '/404' })
      return
    }
  }

  next()
})

export default router
