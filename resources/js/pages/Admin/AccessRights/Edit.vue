<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="Manage Permission"></Navbar>
            <section class="main-section">
                <template v-if="!initialLoad">
                    <template v-if="permission !== 'not_found'">
                        <div class="clearfix">
                            <el-page-header @back="goBack" :content="title" class="float-left" />
                            <el-button @click="dialogVisible = true" icon="el-icon-plus" type="primary" class="float-right" size="medium"> Add Employee</el-button>
                        </div>
                        <el-table
                            class="mt-2"
                            v-loading="loading"
                            element-loading-text="Loading Employees..."
                            :data="employees"
                            style="width: 100%"
                            size="small">
                            <el-table-column
                                type="index"
                                align="center"
                                width="75">
                                <template slot="header">
                                    <span class="text-muted">#</span>
                                </template>
                                <template slot-scope="scope">
                                    <el-avatar :size="25" src="https://empty" @error="errorHandler">
                                        <img src="/assets/img/default-picture.png"/>
                                    </el-avatar>
                                </template>
                            </el-table-column>
                            <el-table-column
                                align="center"
                                prop="name"
                                width="150">
                                <template slot="header">
                                    <span class="text-muted">EMPLOYEE ID</span>
                                </template>
                                <template slot-scope="scope">
                                    <span class="text-primary">{{ checkString(scope.row.employee_id) }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                align="center"
                                prop="name"
                                min-width="150">
                                <template slot="header">
                                    <span class="text-muted">Firstname</span>
                                </template>
                                <template slot-scope="scope">
                                    <span>{{ checkString(scope.row.firstname) }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                align="center"
                                prop="name"
                                min-width="150">
                                <template slot="header">
                                    <span class="text-muted">Middlename</span>
                                </template>
                                <template slot-scope="scope">
                                    <span>{{ checkString(scope.row.middlename) }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                align="center"
                                prop="name"
                                min-width="150">
                                <template slot="header">
                                    <span class="text-muted">Lastname</span>
                                </template>
                                <template slot-scope="scope">
                                    <span>{{ checkString(scope.row.lastname) }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                align="center"
                                prop="branch_name"
                                width="150"
                                label="">
                                <template slot="header">
                                    <span class="text-muted">BRANCH</span>
                                </template>
                                <template slot-scope="scope">
                                    <span>{{ checkString(scope.row.branch_name) }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column
                                align="center"
                                width="150">
                                <template slot="header">
                                    <span class="text-muted">ACTIONS</span>
                                </template>
                                <template slot-scope="scope">
                                    <el-button type="danger" size="small" icon="el-icon-close" @click="revokePermission(scope.row.uuid)"> Revoke</el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </template>
                    <template v-else>
                        permission not found
                    </template>
                </template>

                <el-dialog title="Add Employee" :visible.sync="dialogVisible">
                    <el-select
                        @change="user_ids = []"
                        v-model="branch_code"
                        filterable
                        placeholder="Select branch"
                        style="width: 100%;"
                        class="mb-3">
                        <el-option
                            v-for="branch in branches"
                            :key="branch.code"
                            :label="branch.name"
                            :value="branch.code">
                        </el-option>
                    </el-select>
                    <el-select
                        :disabled="branch_code === ''"
                        v-model="user_ids"
                        multiple
                        filterable
                        remote
                        reserve-keyword
                        placeholder="Search Employess"
                        :remote-method="findEmployees"
                        style="width: 100%; display: block;"
                        :loading="search_loading">
                        <el-option
                            v-for="user in users_filter"
                            :key="user.id"
                            :label="user.full_name"
                            :value="user.id">
                        </el-option>
                    </el-select>
                    <span slot="footer" class="dialog-footer">
                        <el-button @click="dialogVisible = false">Cancel</el-button>
                        <el-button @click="submitEmployees()" icon="el-icon-check" type="primary"> Submit</el-button>
                    </span>
                </el-dialog>

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
                permission: {},
                employees: [],
                search_loading: false,
                users_filter: [],
                user_ids: [],
                branch_code: '',
                loading: false,
                title: '',
                dialogVisible: false,
                initialLoad: true,
                folder_name: this.$root.folder_name,
                branches:[
                            {
                                'code': 'gh',
                                'name': 'Greenhills',
                            },
                            {
                                'code': 'fv',
                                'name': 'Fairview',
                            },
                            {
                                'code': 'sa',
                                'name': 'Sta. Ana',
                            },
                            {
                                'code': 'lp',
                                'name': 'Las Piñas',
                            },
                            {
                                'code': 'an',
                                'name': 'Angeles',
                            },
                ],
            }
        },
        mounted() {
            this.getPermission();
        },
        methods: {
            goBack() {
                window.location.href = this.folder_name + '/access_rights';
            },
            getPermission() {
                const url = new URL(window.location.href);
                const permission = url.searchParams.get("permission");
                if (this.getUserType() === 'admin' || (this.getUserType() === 'employee') && $root.inArray('view access rights', $root.permissions)){
                    this.loading = true;
                    axios.post(this.folder_name + '/access_rights/get_permission', { slug: permission })
                        .then(res => {
                            this.permission = res.data.permission;
                            if (this.permission !== 'not_found'){
                                this.title = this.permission.name;
                            }
                            this.employees = res.data.employees;
                            this.loading = false;
                            this.initialLoad = false;
                        });
                }
            },
            revokePermission(uuid, branch_code) {
                this.$confirm("Revoke this employee's permission?", 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/access_rights/revoke_permission', { uuid: uuid, permission: this.permission })
                        .then(res => {
                            if (res.data.status === 'success'){
                                this.$swal("Success", "An employee's permission has been revoked.", "success");
                                this.getPermission();
                            }
                        })
                        .catch(err => {
                            this.$swal("Error", 'Something went wrong', "error")
                        });
                }).catch(() => {
                    //
                });
            },
            findEmployees(query) {
                if (query !== '') {
                    this.search_loading = true;
                    setTimeout(() => {
                        axios.post(this.folder_name + '/access_rights/find_employees', { name: query, branch: this.branch_code })
                            .then(res => {
                                this.users_filter = res.data;
                                this.search_loading = false;
                            });
                    }, 200);
                }
            },
            submitEmployees() {
                let data = {
                    user_ids: this.user_ids,
                    branch_code: this.branch_code,
                    permission: this.permission.id
                };
                axios.post(this.folder_name + '/access_rights/add_permission', data)
                    .then(res => {
                        this.users_filter = res.data;
                        this.user_ids = [];
                        this.branch_code = '';
                        this.dialogVisible = false;
                        this.getPermission();
                    });
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
            checkString(string) {
                return string === null || string === '' ? '-' : string;
            },
        }
    }
</script>
