<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="Users"></Navbar>
            <el-container class="main-section" direction="vertical">
                <el-container style="margin-bottom: 1rem;">
                    <span class="search-label">Branch:</span>
                    <el-select v-model="branch" placeholder="Select a branch"
                        style="margin-right: 1rem;">
                        <el-option label="None" :value="null"></el-option>
                        <el-option
                            v-for="(item, index) in branches"
                            :key="index"
                            :label="item.text"
                            :value="item.id">
                        </el-option>
                    </el-select>
                    <el-button @click="getUsers(branch)" type="primary" icon="el-icon-search">Search</el-button>
                    <el-button @click="isAddUserVisible = true" type="primary" icon="el-icon-plus">Add User</el-button>
                </el-container>
                <el-card :body-style="{padding: 'unset'}">
                    <el-table :data="newUsers"
                        v-loading="isUsersLoading"
                        height="65vh"
                        style="width: 100%">
                        <el-table-column
                            prop="name"
                            label="Name">
                        </el-table-column>
                        <el-table-column
                            prop="email"
                            label="Email">
                        </el-table-column>
                        <el-table-column
                            width="100"
                            label="Branch">
                            <template slot-scope="scope">
                                {{ scope.row.branch || 'None' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            width="150"
                            label="Action">
                            <template slot-scope="scope">
                                <el-tooltip class="item" effect="dark" content="Edit Permissions" placement="left">
                                    <el-button @click="editPermissions(scope.row.id)" round
                                        size="mini" type="warning" icon="el-icon-edit">
                                    </el-button>
                                </el-tooltip>
                                <template v-if="scope.row.user_type == 'App\\Services\\Users\\User'">
                                    <el-tooltip class="item" effect="dark" content="Delete User" placement="left">
                                        <el-button @click="deleteUser(scope.row.id)" round
                                            size="mini" type="danger" icon="el-icon-delete">
                                        </el-button>
                                    </el-tooltip>
                                </template>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
            </el-container>
            <el-dialog title="Add User" :visible.sync="isAddUserVisible">
                <el-form :model="newUser" label-width="160px">
                    <el-form-item label="Firstname">
                        <el-input type="email" v-model="newUser.fname"></el-input>
                    </el-form-item>
                    <el-form-item label="Middlename (Optional)">
                        <el-input type="email" v-model="newUser.mname"></el-input>
                    </el-form-item>
                    <el-form-item label="Lastname">
                        <el-input type="email" v-model="newUser.lname"></el-input>
                    </el-form-item>
                    <el-form-item label="Suffix (Optional)">
                        <el-input type="email" v-model="newUser.ext_name"></el-input>
                    </el-form-item>
                    <el-form-item label="Email">
                        <el-input type="email" v-model="newUser.email"></el-input>
                    </el-form-item>
                    <el-form-item label="Password">
                        <el-input type="password" v-model="newUser.password" show-password></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="isAddUserVisible = false">Cancel</el-button>
                    <el-button type="primary" :loading="isAddUserLoading" @click="addUser()">Save</el-button>
                </span>
            </el-dialog>
            <el-dialog :visible.sync="isEditPermissionVisible" :title="'Edit Permissions: ' + currentUser.name">
                <el-form :model="newUser" label-width="120px">
                    <el-form-item label="Permissions">
                        <el-checkbox :indeterminate="permissions.isIndeterminate" v-model="permissions.isAllChecked" @change="handleCheckAllPermissions">Check all</el-checkbox>
                        <el-checkbox-group class="permissions-checkbox" v-model="currentUser.permissions" @change="handleCheckedPermissions">
                            <el-checkbox v-for="(permissions, index) in permissions.options" :label="permissions" :key="index">
                                {{ permissions }}
                            </el-checkbox>
                        </el-checkbox-group>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="isEditPermissionVisible = false">Cancel</el-button>
                    <el-button type="primary" :loading="isEditPermissionLoading" @click="savePermissions()">Save</el-button>
                </span>
            </el-dialog>
        </el-main>
    </el-container>
</template>

<script>
import Navbar from './../../components/admin/Navbar'
import Sidebar from './../../components/admin/Sidebar'
export default {
    components: { Navbar, Sidebar },
    data () {
        return {
            isAddUserVisible: false,
            isEditPermissionVisible: false,
            isAddUserLoading: false,
            isEditPermissionLoading: false,
            isEmployeesLoading: false,
            isUsersLoading: false,
            branch: null,
            employees: [],
            currentUser: {
                id: 0,
                name: '',
                permissions: []
            },
            permissions: {
                options: this.permissionOptions,
                isAllChecked: false,
                isIndeterminate: false
            },
            newUsers: this.users,
            newUser: {},
            folder_name: this.$root.folder_name,
        }
    },
    props: {
        permissionOptions: Array,
        branches: Array,
        users: Array
    },
    methods: {
        getEmployees(branch) {
            this.isEmployeesLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: this.folder_name + '/get_employees',
                data: {branch: branch}
            }).then(response => {
                this.isEmployeesLoading = false
                this.employees = response.data
            }).catch(error => {
                this.isEmployeesLoading = false
                this.$root.defaultError()
            })
        },
        getUsers(branch = null) {
            this.isUsersLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: this.folder_name + '/users/get',
                data: {branch: branch}
            }).then(response => {
                this.isUsersLoading = false
                this.newUsers = response.data
            }).catch(error => {
                this.isUsersLoading = false
                this.$root.defaultError()
            })
        },
        addUser() {
            this.isAddUserLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: this.folder_name + '/users/add',
                data: this.newUser
            }).then(response => {
                this.isAddUserLoading = false
                this.isAddUserVisible = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getUsers()
            }).catch(error => {
                this.isAddUserLoading = false
                this.$root.defaultError()
            })
        },
        deleteUser(userID) {
            const found = this.newUsers.find(user => user.id === userID)
            this.$swal({
                icon: 'warning',
                title: 'Confirm?',
                html: '<p>Are you sure you want to delete<br/>' + found.name + '?</p>',
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const loading = this.$loading({
                        lock: true,
                        background: 'rgba(0, 0, 0, 0.5)',
                        customClass: 'loading-overlay'
                    })
                    axios({
                        method: 'POST',
                        type: 'JSON',
                        url: this.folder_name + '/users/delete',
                        data: {id: userID}
                    }).then(response => {
                        loading.close()
                        this.$swal(response.data.title, response.data.text, response.data.type)
                        this.getUsers()
                    }).catch(error => {
                        loading.close()
                        this.$root.defaultError()
                    })
                }
            })
        },
        editPermissions(userID) {
            this.isEditPermissionVisible = true
            const found = this.newUsers.find(user => user.id === userID)
            this.currentUser = {
                id: found.id,
                branch: this.branch,
                name: found.name,
                permissions: found.permissions,
                branch_account: found.branch_account,
                user_type: found.user_type
            }

            let checkedCount = found.permissions.length
            this.permissions.isAllChecked = checkedCount === this.permissions.options.length
            this.permissions.isIndeterminate = checkedCount > 0 && checkedCount < this.permissions.options.length
        },
        savePermissions() {
            this.isEditPermissionLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: this.folder_name + '/users/permissions/update',
                data: this.currentUser
            }).then(response => {
                this.isEditPermissionLoading = false
                this.isEditPermissionVisible = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getUsers()
            }).catch(error => {
                this.isEditPermissionLoading = false
                this.$root.defaultError()
            })
        },
        handleCheckAllPermissions(value) {
            this.currentUser.permissions = value ? this.permissions.options : []
            this.permissions.isIndeterminate = false
        },
        handleCheckedPermissions(value) {
            let checkedCount = value.length
            this.permissions.isAllChecked = checkedCount === this.permissions.options.length
            this.permissions.isIndeterminate = checkedCount > 0 && checkedCount < this.permissions.options.length
        }
    }
}
</script>
