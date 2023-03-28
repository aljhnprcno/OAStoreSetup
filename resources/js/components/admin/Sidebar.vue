<template>
  <aside class="wrapper__aside">
    <div><img src="https://obmontessori.edu.ph/assets/images/home_layout/OBMC Banner.png" class="img-fluid" alt="Logo">
    </div>
    <a :href="folder_name + '/home'">
      <div class="card-header">
        <i class="circle-bg-icon fa fa-tachometer"></i>
        <span>&nbsp;Home</span>
      </div>
    </a>
    <template>
      <div class="card">
        <template v-if="checkPermission('access rights')">
          <a :href="folder_name + '/access_rights'">
            <div class="card-header">
              <i class="circle-bg-icon fa fa-user"></i>
              <span>&nbsp;Access Rights</span>
            </div>
          </a>
        </template>
        <a data-toggle="collapse" href="#library" v-if="checkPermission('setup')">
          <div class="card-header">
            <i class="circle-bg-icon fa fa-cogs" aria-hidden="true"></i>
            <span>&nbsp;Setup</span>
          </div>
        </a>
        <div id="library" class="collapse">
          <template>
            <a :href="folder_name + '/id_printing/id_setup'">
              <div class="card-header" style="padding-left: 2rem;">
                <i class="circle-bg-icon fa fa-pencil-square-o" aria-hidden="true"></i>
                <span>&nbsp;ID setup</span>
              </div>
            </a>
          </template>
          <template>
            <a :href="folder_name + '/request_setup/request-setup'">
              <div class="card-header" style="padding-left: 2rem;">
                <i class="circle-bg-icon fa fa-envelope-o" aria-hidden="true"></i>
                <span>&nbsp;Request Setup</span>
              </div>
            </a>
          </template>
        </div>
      </div>
    </template>
    <a :href="folder_name + '/id_requests/request/lists'" v-if="checkPermission('request')">
      <div class="card-header">
        <i class="circle-bg-icon fa fa-print" aria-hidden="true"></i>
        <span>&nbsp;Requests</span>
      </div>
    </a>
      <a :href="folder_name + '/admin/store'" v-if="checkPermission('store setup')">
        <div class="card-header">
          <i class="circle-bg-icon fa fa-user"></i>
          <span>&nbsp;Store</span>
        </div>
      </a>
  </aside>
</template>

<script>
export default {
  data() {
    return {
      folder_name: '',
    }
  },
  created() {
    this.folder_name = this.$root.folder_name;
  },
  methods: {
    checkPermission(permission) {
      return this.$root.inArray(permission, this.$root.permissions);
    },
    isAdmin() {
      return this.$root.auth.info.user_type === "App\\Services\\Users\\User";
    },
  },
}
</script>
