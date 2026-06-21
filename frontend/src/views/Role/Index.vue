<template>
  <div class="role-page">
    <div class="page-header">
      <div class="header-left">
        <h2 class="page-title">角色管理</h2>
        <p class="page-desc">管理三端角色与权限分配</p>
      </div>
      <div class="header-right">
        <el-select v-model="currentGuard" placeholder="选择端" style="width: 140px; margin-right: 12px" @change="handleGuardChange">
          <el-option label="平台端" value="platform" />
          <el-option label="供应商端" value="supplier" />
          <el-option label="经销商端" value="distributor" />
        </el-select>
        <el-button type="primary" @click="handleAdd">
          <i class="el-icon-plus"></i> 新建角色
        </el-button>
      </div>
    </div>

    <el-card class="table-card">
      <el-table :data="tableData" v-loading="loading" border stripe>
        <el-table-column type="index" label="序号" width="60" align="center" />
        <el-table-column prop="name" label="角色标识" width="180" />
        <el-table-column label="角色名称" width="160">
          <template slot-scope="scope">
            {{ scope.row.display_name || scope.row.name }}
          </template>
        </el-table-column>
        <el-table-column prop="guard_name" label="所属端" width="120" align="center">
          <template slot-scope="scope">
            <el-tag :type="guardTagTypes[scope.row.guard_name]" size="mini">
              {{ guardLabels[scope.row.guard_name] || scope.row.guard_name }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="权限数" width="100" align="center">
          <template slot-scope="scope">
            <el-tag size="mini">{{ (scope.row.permissions || []).length || 0 }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column label="权限概览" min-width="300">
          <template slot-scope="scope">
            <div class="permission-tags">
              <el-tag
                v-for="perm in (scope.row.permissions || []).slice(0, 5)"
                :key="perm.id || perm.name"
                size="mini"
                type="info"
                style="margin: 2px"
              >
                {{ perm.name }}
              </el-tag>
              <el-tag v-if="(scope.row.permissions || []).length > 5" size="mini" type="info" style="margin: 2px">
                +{{ scope.row.permissions.length - 5 }}
              </el-tag>
            </div>
          </template>
        </el-table-column>
        <el-table-column prop="created_at" label="创建时间" width="160" />
        <el-table-column label="操作" width="200" align="center" fixed="right">
          <template slot-scope="scope">
            <el-button type="primary" link size="mini" @click="handleEdit(scope.row)">编辑</el-button>
            <el-button type="primary" link size="mini" @click="handleViewPermissions(scope.row)">权限</el-button>
            <el-button type="danger" link size="mini" @click="handleDelete(scope.row)" :disabled="isSystemRole(scope.row)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </el-card>

    <el-dialog
      :visible.sync="dialogVisible"
      :title="isEdit ? '编辑角色' : '新建角色'"
      width="700px"
      append-to-body
      :close-on-click-modal="false"
      :close-on-press-escape="!submitLoading"
      :show-close="!submitLoading"
      :before-close="handleDialogClose"
    >
      <el-form :model="formData" :rules="formRules" ref="formRef" label-width="100px">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="角色标识" prop="name">
              <el-input v-model="formData.name" placeholder="如：platform-admin" :disabled="isEdit" />
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="角色名称" prop="display_name">
              <el-input v-model="formData.display_name" placeholder="如：平台管理员" />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row :gutter="20">
          <el-col :span="12">
            <el-form-item label="所属端" prop="guard_name">
              <el-select v-model="formData.guard_name" style="width: 100%" :disabled="isEdit">
                <el-option label="平台端" value="platform" />
                <el-option label="供应商端" value="supplier" />
                <el-option label="经销商端" value="distributor" />
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="权限分配" prop="permissions">
          <el-tree
            ref="permTree"
            :key="dialogTreeKey"
            :data="permissionTree"
            show-checkbox
            node-key="name"
            :props="{ label: 'label', children: 'children' }"
            style="max-height: 300px; overflow-y: auto; border: 1px solid #dcdfe6; border-radius: 4px; padding: 8px"
          />
        </el-form-item>
      </el-form>
      <template slot="footer">
        <el-button @click="dialogVisible = false" :disabled="submitLoading">取消</el-button>
        <el-button type="primary" :loading="submitLoading" @click="handleSubmit">确定</el-button>
      </template>
    </el-dialog>

    <el-drawer
      :visible.sync="permDrawerVisible"
      :title="'权限详情 - ' + ((currentRole && (currentRole.display_name || currentRole.name)) || '')"
      size="500px"
      append-to-body
    >
      <div v-if="currentRole" style="padding: 20px">
        <el-descriptions :column="1" border>
          <el-descriptions-item label="角色标识">{{ currentRole.name }}</el-descriptions-item>
          <el-descriptions-item label="角色名称">{{ currentRole.display_name || '-' }}</el-descriptions-item>
          <el-descriptions-item label="所属端">
            <el-tag :type="guardTagTypes[currentRole.guard_name]" size="mini">
              {{ guardLabels[currentRole.guard_name] || currentRole.guard_name }}
            </el-tag>
          </el-descriptions-item>
        </el-descriptions>

        <h4 style="margin: 16px 0 8px">权限列表</h4>
        <div v-for="(perms, group) in groupedPermissions" :key="group" style="margin-bottom: 12px">
          <div style="font-weight: 600; margin-bottom: 4px; color: #303133">{{ group }}</div>
          <el-tag v-for="perm in perms" :key="perm.name" size="mini" style="margin: 2px">{{ perm.name }}</el-tag>
        </div>
      </div>
    </el-drawer>
  </div>
</template>

<script>
import { getRoles, createRole, updateRole, deleteRole, getPermissions } from '@/api/role'

const systemRoles = ['platform', 'supplier', 'distributor', 'platform-admin', 'supplier-admin', 'distributor-admin']

const guardLabels = {
  platform: '平台端',
  supplier: '供应商端',
  distributor: '经销商端'
}

const guardTagTypes = {
  platform: '',
  supplier: 'success',
  distributor: 'warning'
}

export default {
  name: 'RoleManagement',
  data() {
    return {
      loading: false,
      submitLoading: false,
      tableData: [],
      allPermissions: [],
      currentGuard: 'platform',
      dialogVisible: false,
      permDrawerVisible: false,
      isEdit: false,
      currentRole: null,
      dialogTreeKey: 0,
      formData: {
        name: '',
        display_name: '',
        guard_name: 'platform',
        permissions: []
      },
      formRules: {
        name: [
          { required: true, message: '请输入角色标识', trigger: 'blur' },
          { pattern: /^[a-z0-9-]+$/, message: '仅允许小写字母、数字和连字符', trigger: 'blur' }
        ],
        display_name: [{ required: true, message: '请输入角色名称', trigger: 'blur' }],
        guard_name: [{ required: true, message: '请选择所属端', trigger: 'change' }]
      }
    }
  },
  computed: {
    guardLabels: () => guardLabels,
    guardTagTypes: () => guardTagTypes,
    permissionTree() {
      const guardName = this.formData.guard_name || this.currentGuard
      const grouped = {}
      this.allPermissions.forEach(perm => {
        if (perm.guard_name !== guardName) return
        const parts = perm.name.split('.')
        const group = parts[0] || 'other'
        if (!grouped[group]) grouped[group] = []
        grouped[group].push({
          name: perm.name,
          label: perm.display_name || perm.name
        })
      })

      return Object.keys(grouped).map(group => ({
        name: group,
        label: this.getGroupLabel(group),
        children: grouped[group]
      }))
    },
    groupedPermissions() {
      if (!this.currentRole?.permissions) return {}
      const grouped = {}
      this.currentRole.permissions.forEach(perm => {
        const parts = perm.name.split('.')
        const group = parts[0] || 'other'
        if (!grouped[group]) grouped[group] = []
        grouped[group].push(perm)
      })
      return grouped
    }
  },
  created() {
    this.fetchData()
    this.fetchPermissions()
  },
  methods: {
    getGroupLabel(group) {
      const labels = {
        role: '角色管理',
        user: '用户管理',
        order: '订单管理',
        product: '商品管理',
        inventory: '库存管理',
        refund: '退款管理',
        coupon: '优惠券管理',
        dashboard: '数据概览',
        system: '系统设置'
      }
      return labels[group] || group
    },
    isSystemRole(row) {
      return systemRoles.includes(row.name)
    },
    async fetchData() {
      this.loading = true
      try {
        const res = await getRoles({ guard_name: this.currentGuard })
        this.tableData = res.data?.roles || res.data || []
      } catch (e) {
        console.error(e)
      } finally {
        this.loading = false
      }
    },
    async fetchPermissions() {
      try {
        const res = await getPermissions()
        this.allPermissions = res.data?.permissions || res.data || []
      } catch (e) {
        console.error(e)
      }
    },
    handleGuardChange() {
      this.fetchData()
    },
    handleAdd() {
      this.isEdit = false
      this.formData = {
        name: '',
        display_name: '',
        guard_name: this.currentGuard,
        permissions: []
      }
      this.dialogTreeKey++
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$nextTick(() => {
          this.$refs.permTree?.setCheckedKeys([])
        })
      })
    },
    handleEdit(row) {
      this.isEdit = true
      this.formData = {
        id: row.id,
        name: row.name,
        display_name: row.display_name || row.name,
        guard_name: row.guard_name,
        permissions: (row.permissions || []).map(p => p.name)
      }
      this.dialogTreeKey++
      this.dialogVisible = true
      this.$nextTick(() => {
        this.$nextTick(() => {
          this.$refs.permTree?.setCheckedKeys(this.formData.permissions)
        })
      })
    },
    handleViewPermissions(row) {
      this.currentRole = row
      this.permDrawerVisible = true
    },
    handleDialogClose(done) {
      if (this.submitLoading) return
      done()
    },
    async handleDelete(row) {
      if (this.isSystemRole(row)) {
        this.$message.warning('系统角色不能删除')
        return
      }
      this.$confirm(`确定要删除角色"${row.display_name || row.name}"吗？`, '删除确认', {
        confirmButtonText: '删除',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(async () => {
        try {
          await deleteRole(row.id)
          this.$message.success('删除成功')
          this.fetchData()
        } catch (e) {
          console.error(e)
        }
      }).catch(() => {})
    },
    async handleSubmit() {
      this.$refs.formRef.validate(async valid => {
        if (!valid) return

        const checkedKeys = this.$refs.permTree?.getCheckedKeys() || []
        this.submitLoading = true
        try {
          const data = {
            ...this.formData,
            permissions: checkedKeys
          }

          if (this.isEdit) {
            await updateRole(this.formData.id, data)
            this.$message.success('更新成功')
          } else {
            await createRole(data)
            this.$message.success('创建成功')
          }
          this.dialogVisible = false
          this.fetchData()
        } catch (e) {
          console.error(e)
        } finally {
          this.submitLoading = false
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.role-page {
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;

    .header-left {
      .page-title {
        font-size: 20px;
        color: #303133;
        margin-bottom: 4px;
      }

      .page-desc {
        font-size: 14px;
        color: #909399;
      }
    }

    .header-right {
      display: flex;
      align-items: center;
    }
  }

  .permission-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 2px;
  }
}
</style>
