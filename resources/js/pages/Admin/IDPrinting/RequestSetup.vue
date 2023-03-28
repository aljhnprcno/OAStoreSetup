<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="Request Setup"></Navbar>
            <section class="main-section">
            <el-row :gutter="24">
                <el-col :span="12">
                    <el-input v-model="search" name="search" />
                </el-col>
                 <el-col :span="12">
                     <el-button type="primary" @click="isCreate" class="float-left">Create Setup</el-button>
                </el-col>
            </el-row>
             <div>
                 <el-table
                    class="mt-4"
                    v-loading=""
                    element-loading-text="Loading datas..."
                    row-class-name="warning-row"
                    :data="data.datas"
                    style="width: 100%; min-height: 50vh;"
                    size="small">
                    <el-table-column
                        align="center"
                        width="150">
                        <template slot="header">
                            <span class="text-muted">Branch</span>
                        </template>
                        <template slot-scope="scope">
                         {{ scope.row.branch_code}}
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="class_material_code"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Email</span>
                        </template>
                        <template slot-scope="scope">
                            <span class="text-muted font-weight-bolder">{{ scope.row.email_address }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="title"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Date Created</span>
                        </template>
                        <template slot-scope="scope">
                            <span class="text-muted font-weight-bolder">{{ $root.moment(scope.row.created_at).format('MMMM Do YYYY, h:mm:ss a')}}</span>
                        </template>
                    </el-table-column>
                     <el-table-column
                        align="center"
                        :min-width="300"
                        resizable>
                        <template slot="header">
                            <span style="color: black;">ACTIONS</span>
                        </template>
                        <template slot-scope="scope">
                            <el-button type="primary" size="small" icon="el-icon-delete" @click="handleDelete(scope.row.id)"> Delete</el-button>
                            <el-button type="success" size="small" @click="isUpdate(scope.row)"> Update</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="clearfix">
                    <el-pagination
                        v-if="data.total > 0"
                        class="float-right my-3"
                        background
                        layout="total, prev, pager, next"
                        :current-page.sync="request_setup.page"
                        :page-size="request_setup.per_page"
                        @current-change="getRequestSetup"
                        :total="data.total">
                    </el-pagination>
                </div>
            </div>
                <!--<layout v-if="setup.image_path" :image="setup.image_path" :title="setup.display_name" :display_only="true" />
                <div class="px-2">
                    <el-form ref="requestForm" :rules="rules" :model="requestForm" label-width="120px" label-position="top">
                        <label class="text-uppercase font-weight-bolder" style="margin-bottom: 6px;">Campus</label>
                        <el-form-item label="" prop="branch_code">
                            <el-select v-model="requestForm.branch_code" name="branch_code" clearable placeholder="Select">
                                    <el-option
                                    v-for="item in branches"
                                    :key="item.code"
                                    :label="item.name"
                                    :value="item.code">
                                    </el-option>
                            </el-select>
                            </el-form-item>
                        <el-row :gutter="20">
                          <el-col :span="12">
                            <div class="grid-content">
                            <el-form-item label="" prop="email_address">
                              <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">Email Address</label>
                              <el-input v-model="requestForm.email_address" name="email_address" />
                            </el-form-item>
                            </div>
                            </el-col>
                         </el-row>
                        <hr>
                        <div class="clearfix">
                            <el-button type="danger" @click="handleDelete" class="float-right ml-2" v-if="setup.image_path">Remove</el-button>
                            <el-button :loading="isUploading" type="primary" @click="submitForm" class="float-left">Create Setup</el-button>
                        </div>
                    </el-form>
                </div>-->
            </section>
        </el-main>
        <el-dialog
        :title="text"
        :visible.sync="dialogVisible"
        width="35%"
        :before-close="resetForm">
         <el-form ref="requestForm" :rules="rules" :model="requestForm" label-width="120px" label-position="top">
                <label class="text-uppercase font-weight-bolder" style="margin-bottom: 6px;">Campus</label>
                   <el-form-item label="" prop="branch_code">
                    <div class="grid-content">
                        <el-select v-model="requestForm.branch_code" name="branch_code" clearable placeholder="Select">
                            <el-option
                            v-for="item in branches"
                            :key="item.code"
                            :label="item.name"
                            :value="item.code">
                            </el-option>
                        </el-select>
                        </div>
                    </el-form-item>
                        <div class="grid-content">
                            <el-form-item label="" prop="email_address">
                            <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">Email Address</label>
                            <el-input type="email" v-model="requestForm.email_address" name="email_address" />
                            </el-form-item>
                        </div>
                        <hr>
                        <div class="clearfix">
                            <el-button type="danger" @click="handleDelete" class="float-right ml-2" v-if="setup.image_path">Remove</el-button>
                            <el-button v-if="isUpdating == false":loading="isUploading" type="primary" @click="submitForm" class="float-left">Create Setup</el-button>
                             <el-button v-if="isUpdating == true" :loading="isUploading" type="primary" @click="handleUpdate" class="float-left">Update Setup</el-button>
                        </div>
                    </el-form>
        </el-dialog>
    </el-container>
</template>
<script>
    import Layout from './../../../pages/General/Layout';
    import Navbar from './../../../components/admin/Navbar';
    import Sidebar from './../../../components/admin/Sidebar';
    export default {
        components: { Navbar, Sidebar, Layout },
        data() {
            var validateUpload = (rule, value, callback) => {
                if (this.uploadForm.id_front.length < 1 && !this.setup.image_path) {
                    callback(new Error('Please upload a id front image template'));
                } else if (this.uploadForm.id_back.length < 1 && !this.setup.image_path) {
                    callback(new Error('Please upload a id back image template'));
                } else if (this.uploadForm.digital_signature.length < 1 && !this.setup.image_path) {
                    callback(new Error('Please upload a digital image template'));
                } else {
                    callback();
                }
            };
            return {
                setup: {
                    display_name: '',
                    image_path: '',
                },
                isUploading: false,
                requestForm: {
                    branch_code: '',
                    email_address: '',
                    notif_type: '',
                },
                rules: {
                    branch_code: [
                        { required: true, message: 'Please select a campus', trigger: 'blur' },
                    ],
                    email_address: [
                        {type: 'email', required: true, message: 'Please enter email', trigger: 'blur' },
                    ],
                },
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
                types: [
                    {
                      'type': 'request',
                      'name': 'Requesting'  
                    },{
                      'type': 'approved_request',
                      'name': 'Approved Request'  
                    },{
                      'type': 'pickup',
                      'name': 'Ready For Pickup'  
                    }
                ],
                data: [],
                request_setup: {
                    total: 0,
                    page: 1,
                    per_page: 10,
                },
                search: '',
                loading: false,
                dialogVisible: false,
                upDating: false,
                text: '',
                filter: {
                    datas: [],
                    total: 0,
                    page: 1,
                    per_page: 10,
                },
            }
        },
        mounted() {
            this.getRequestSetup();
        },
        methods: {
            getRequestSetup() {
                this.request_setup.search = this.search;
                this.loading = true;
                axios.post(this.folder_name + '/request_setup/request/list', this.request_setup)
                    .then(res => {
                       console.log(res.data);
                       this.data = res.data;
                       this.loading = false;
                    })
                    .catch(() => {
                        
                    });
            },
            handleDelete(id) {
                this.$confirm('Remove this setup?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/request_setup/request/delete', {id: id})
                        .then(res => {
                            this.getRequestSetup();
                            this.$swal("Success", '', "success");
                        })
                        .catch(() => {
                            this.$swal("Error", "Something went wrong", "error");
                        });
                }).catch(() => {
                    //
                });
            },
            handleUpdate() {
                this.$confirm('Update this setup?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/request_setup/request/update', {branch_code: this.requestForm.branch_code, email_address: this.requestForm.email_address, id: this.requestForm.id})
                        .then(res => {
                            this.getRequestSetup();
                            this.$swal("Success", '', "success");
                            this.resetForm();
                        })
                        .catch(() => {
                            this.$swal("Error", "Something went wrong", "error");
                        });
                }).catch(() => {
                    //
                });
            },
            resetForm() {
                this.dialogVisible = false;
                this.isUpdating = false;
                this.requestForm = {
                    branch_code: '',
                    email_address: '',
                };
            },
            isUpdate(data) {
                this.text = 'Update Setup Request';
                this.isUpdating = true;
                if (this.isUpdating) {
                    this.dialogVisible = true;
                    this.requestForm = data;
                }
            },
            isCreate() {
                this.dialogVisible = true;
                this.isUpdating = false;
                this.text = 'Create Setup Request';
            },
            submitForm() {
                this.$refs['requestForm'].validate((valid) => {
                    if (valid) {
                        const form = this.$refs.requestForm.$el;
                        let formData = new FormData(form);
                        this.isUploading = true;
                        axios.post(this.folder_name + '/request_setup/request', formData)
                            .then(res => {
                                this.isUploading = false;
                                this.dialogVisible = false,
                                this.$swal("Success", "Request Setup Created.", "success");
                                this.getRequestSetup();
                                this.resetForm();
                            });
                    }
                });
            },
        }
    }
</script>
