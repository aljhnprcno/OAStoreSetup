<template>
  <el-container style="height: 100%;">
    <Sidebar></Sidebar>
    <el-main class="wrapper__body">
      <Navbar title="Access Rights"></Navbar>
      <section class="main-section">
        <el-table :data="permissions" style="width: 100%" size="small" border>
          <el-table-column type="index" align="center" width="50">
          </el-table-column>
          <el-table-column align="center" prop="name" resizable :min-width="300" label="Permission Name">
            <template slot="header">
              <span style="color: black;">PERMISSION</span>
            </template>
          </el-table-column>
          <el-table-column align="center" :min-width="300" resizable>
            <template slot="header">
              <span style="color: black;">ACTIONS</span>
            </template>
            <template slot-scope="scope">
              <el-button type="primary" size="small" icon="el-icon-user-solid" @click="editPermission(scope.row.name)">
                Employees</el-button>
              <el-button type="danger" size="small" icon="el-icon-delete" @click="deletePermission(scope.row.id)">
                Delete</el-button>
            </template>
          </el-table-column>
        </el-table>
      </section>
    </el-main>
  </el-container>
</template>
<script>
import Navbar from './../../../components/admin/Navbar';
import Sidebar from './../../../components/admin/Sidebar';
export default {
  components: { Navbar, Sidebar },
  props: {
    status: String,
  },
  data() {
    return {
      permissions: [],
      folder_name: this.$root.folder_name,
    }
  },
  mounted() {
    this.getPermissions();
  },
  methods: {
    getPermissions() {
      if (this.getUserType() === 'admin' || (this.getUserType() === 'employee') && $root.inArray('view access rights', $root.permissions)) {
        axios.get(this.folder_name + '/access_rights/get_permissions')
          .then(res => {
            this.permissions = res.data.permissions;
          });
      }
    },
    deletePermission(id) {
      this.$confirm("Delete this permission?", 'Warning', {
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel',
        type: 'warning'
      }).then(() => {
        axios.post(this.folder_name + '/access_rights/delete_permission', { id: id })
          .then(res => {
            if (res.data.status === 'success') {
              this.$swal("Success", "A permission has been deleted.", "success");
              this.getPermissions();
            } else if (res.data.status === 'not_found') {
              this.$swal("Error", 'Permission not found', "error");
            }
          })
          .catch(err => {
            this.$swal("Error", 'Something went wrong', "error")
          });
      }).catch(() => {
        //
      });
    },
    editPermission(permission) {
      const slug = permission.replaceAll(' ', '_');
      window.location.href = this.folder_name + '/access_rights/edit?permission=' + slug;
    },
    getUserType() {
      let info = this.$root.auth.info.user_type;
      let type = '';
      if (info === "App\\Services\\Users\\User") {
        type = 'admin';
      } else if (info === "App\\Services\\Employees\\Employee") {
        type = 'employee';
      } else if (info = "App\\Services\\Students\\Student") {
        type = 'student';
      }
      return type;
    },
  }
}
</script>
