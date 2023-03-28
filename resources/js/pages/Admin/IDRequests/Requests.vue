<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="ID Requests"></Navbar>
            <section class="main-section">
                <layout v-if="setup.image_path" :image="setup.image_path" :title="setup.display_name" :display_only="true" />
                <el-row :gutter="24">
                    <el-col :span="6">
                        <el-input v-model="search" @change="getSetup" placeholder="Search by ID" name="search" />
                    </el-col>
                    <el-col :span="18">
                        <el-select v-model="status_filter" value-key="value" @change="getSetup" multiple clearable clearable placeholder="Select">
                            <el-option
                            v-for="item in status"
                            :key="item.value"
                            :label="item.name"
                            :value="item.value">
                            </el-option>
                        </el-select>
                        &nbsp;&nbsp;
                         <el-select v-model="branchs" @change="getSetup" multiple name="branch_code" clearable placeholder="Filter By Branch">
                            <el-option
                            v-for="item in branches"
                            :key="item.id"
                            :label="item.text"
                            :value="item.id">
                            </el-option>
                         </el-select>
                         &nbsp;&nbsp;
                            <el-date-picker
                            v-model="date_filter"
                            type="daterange"
                            range-separator="-"
                            start-placeholder="Start date"
                            end-placeholder="End date"
                            @change="getSetup"
                            >
                            </el-date-picker>
                      </el-col>
                 </el-row>
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
                            <span class="text-muted">Student Name</span>
                        </template>
                        <template slot-scope="scope">
                         {{ scope.row.name }}
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="class_material_code"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Grade Level / Position</span>
                        </template>
                        <template slot-scope="scope">
                            <template v-if="scope.row.type == 'Student'">
                                {{ scope.row.grade_name.grade_name }}
                            </template>
                            <template v-else>
                              {{ scope.row.position }}
                             </template>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="title"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Campus</span>
                        </template>
                        <template slot-scope="scope">
                               {{ scope.row.campus }}
                        </template>
                    </el-table-column>
                     <el-table-column
                        prop="title"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Status</span>
                        </template>
                        <template slot-scope="scope">
                             {{ (scope.row.status == 2) ? 'Ready For pickup' : (scope.row.status == 0) ? 'Pending' : 'Approved' }}
                        </template>
                    </el-table-column>
                     <el-table-column
                        prop="title"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Date Requests</span>
                        </template>
                        <template slot-scope="scope">  
                           {{ $root.moment(scope.row.created_at).format('MMMM Do YYYY, h:mm:ss a')}}
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
                            <el-button type="success" size="small" v-if="scope.row.status == 0" icon="el-icon-checked" @click="approveRequest(scope.row.id, scope.row.branch_code, scope.row.entity_id, scope.row.type)"> Approve</el-button>
                            <el-button type="primary" size="small"  v-if="scope.row.status == 1 || scope.row.status == 2" icon="el-icon-user-solid" @click="printID(scope.row.branch_code, scope.row.entity_id, scope.row.grade_name, scope.row.type)">Print</el-button>
                            <el-button type="primary" size="small"  v-if="scope.row.status == 1" icon="el-icon-user-solid" @click="pickupModal(scope.row.id, scope.row.branch_code, scope.row.entity_id, scope.row.type)">Ready For Pickup</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="clearfix">
                    <el-pagination
                        v-if="data.total > 0"
                        class="float-right my-3"
                        background
                        layout="total, prev, pager, next"
                        :current-page.sync="filter.page"
                        :page-size="filter.per_page"
                         @current-change="getSetup"
                        :total="data.total">
                    </el-pagination>
                </div>
            </div>
             <el-dialog
        title="Select Pickup Date"
        :visible.sync="dialogVisible"
        width="35%"
        :before-close="resetForm">
         <el-form ref="requestForm" :rules="rules" :model="requestForm" label-width="120px" label-position="top">
                <label class="text-uppercase font-weight-bolder" style="margin-bottom: 6px;">Date And Time</label>
                        <div class="grid-content">
                           <el-date-picker
                            v-model="date"
                            type="datetime"
                            placeholder="Select date and time">
                            </el-date-picker>
                        </div>
                        <br>
                        <div class="clearfix">
                            <el-button :loading="isUploading" type="primary" @click="readyForPickup" class="float-left">Confirm</el-button>
                        </div>
                    </el-form>
        </el-dialog>
                </div>
            </section>
        </el-main>
    </el-container>
</template>
<script>
    import Layout from './../../../pages/General/Layout';
    import Navbar from './../../../components/admin/Navbar';
    import Sidebar from './../../../components/admin/Sidebar';
    import moment from 'moment';
    export default {
        components: { Navbar, Sidebar, Layout },
        data() {
            var validateUpload = (rule, value, callback) => {
                if (this.uploadForm.id_front.length < 1 ) {
                    callback(new Error('Please upload a id front image template'));
                } else if (this.uploadForm.id_back.length < 1 ) {
                    callback(new Error('Please upload a id back image template'));
                } else if (this.uploadForm.digital_signature.length < 1 ) {
                    callback(new Error('Please upload a digital image template'));
                } else {
                    callback();
                }
            };
            var validateCampus = (rule, value, callback) => {
                if (this.uploadForm.branch_code.length < 1 ) {
                    callback(new Error('Please select campus template'));
                } else {
                    callback();
                }
            };
            return {
                setup: {
                    display_name: '',
                    image_path: '',
                },
                request: {
                    id: '',
                    branch_code: '',
                    student_id: [],
                    pickup_date: null,
                },
                filter: {
                    datas: [],
                    total: 0,
                    page: 1,
                    per_page: 10,
                },
                isUploading: false,
                dialogVisible: false,
                date: null,
                folder_name: this.$root.folder_name,
                branches: [],
                value: '',
                data: [],
                dialogVisible: false,
                gradelevel: [],
                type: '',
                status: [
                    {
                        name: 'Approved',
                        value: 1,
                    },{
                        name: 'Pending',
                        value: 0,
                    },{
                        name: 'Ready to pickup',
                        value: 2,
                    },{
                        name: 'All',
                        value: 3,
                    }
                ],
                status_filter: [3],
                search: '',
                date: null,
                branches: [],
                branchs: [],
                date_filter: null,
            }
        },
        mounted() {
            this.getSetup();
            this.getBranches();
            this.validateCampus();
            this.getGradelevel();
        },
        methods: {
            handleDelete() {
                this.$confirm('Remove this setup?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/learning_materials/remove_setup')
                        .then(res => {
                            this.getSetup();
                            this.$swal("Success", '', "success");
                        })
                        .catch(() => {
                            this.$swal("Error", "Something went wrong", "error");
                        });
                }).catch(() => {
                    //
                });
            },
            getSetup() {
                this.filter.status = this.status;
                this.filter.search = this.search;
                this.filter.branchs = this.branchs;
                this.filter.status = this.status_filter;
                this.filter.date = this.date_filter;
                axios.post(this.folder_name + '/id_requests/requests/all', this.filter)
                    .then(res => {
                        this.data = res.data;
                        // this.uploadForm.fileList = [{'name': image.split('/')[5], 'url': image}];
                    });
            },
             getBranches() {
                axios.post(this.folder_name + '/id_printing/branches')
                    .then(res => {
                       
                        // this.uploadForm.fileList = [{'name': image.split('/')[5], 'url': image}];
                    });
            },
            resetForm() {
               this.request ={
                    id: '',
                    branch_code: '',
                    entity_id: [],
                    pickup_date: null,
                },
                this.dialogVisible = false;
            },
            approveRequest(id, branch_code, entity_id, type) {
                this.$confirm('Approve this Request?', 'Warning', {
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/id_requests/request/approve', { id: id, branch_code: branch_code, entity_id: entity_id, type: type })
                        .then(res => {
                            this.getSetup();
                            this.$swal("Success", '', "success");
                        })
                        .catch(() => {
                            this.$swal("Error", "Something went wrong", "error");
                        });
                }).catch(() => {
                    //
                });
            },
            pickupModal(id, branch_code, entity_id, type){
                this.dialogVisible = true;
                this.request.id = id
                this.request.branch_code = branch_code
                this.request.entity_id = entity_id
                this.type = type;
            },
            readyForPickup() {
                this.$confirm('This request is ready for pickup?', 'Warning', {
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/id_requests/request/pickup', { id: this.request.id, branch_code:  this.request.branch_code, entity_id: this.request.entity_id , pickup_date: this.date, user_type: this.type })
                        .then(res => {
                            this.$swal("Success", '', "success");
                            this.dialogVisible = false;
                            this.getSetup();
                        })
                        .catch(() => {
                            this.$swal("Error", "Something went wrong", "error");
                        });
                }).catch(() => {
                    //
                });
            },
            getGradelevel() {
                axios.post(this.folder_name + '/id_printing/branch/gradelevel', { branch_code: this.uploadForm.branch_code })
                    .then(res => {
                        this.gradelevel = res.data;
                        // this.uploadForm.fileList = [{'name': image.split('/')[5], 'url': image}];
                    });
            },
             getBranches() {
                axios.post(this.folder_name + '/branches')
                    .then(res => {
                        this.branches = res.data;
                        // this.uploadForm.fileList = [{'name': image.split('/')[5], 'url': image}];
                    });
            },
             printID(branch_code, entity_id, grade_level_id, type) {
                this.$confirm('Are you sure you want to print?', 'Warning', {
                confirmButtonText: 'Print',
                cancelButtonText: 'Cancel',
                type: 'warning'
                }).then(() => {
                    let grade_id = "";
                    if (type == 'Student') {
                        grade_id = grade_level_id;
                    }
                    axios.post(this.folder_name + '/request/print', { branch_code: branch_code, entity_id: entity_id, grade_id: grade_id, user_type: type, request_type: 2 }, {responseType: 'blob'})
                        .then(response => {
                            const file = new Blob(
                            [response.data], 
                            {type: 'application/pdf'});
                        //Build a URL from the file
                            const fileURL = URL.createObjectURL(file);
                        //Open the URL on new Window
                            window.open(fileURL,'_blank');
                        })
                        .catch(() => {
                            this.$swal("Error", "Something went wrong", "error");
                        });
                }).catch(() => {
                    //
                });
            },
        }
    }
</script>
