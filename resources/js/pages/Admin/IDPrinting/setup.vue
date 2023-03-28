<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="ID Setup"></Navbar>
            <section class="main-section">
                <layout v-if="setup.image_path" :image="setup.image_path" :title="setup.display_name" :display_only="true" />
                <div class="px-2">
                <el-row :gutter="24">
                 <el-col :span="12">
                     <el-button type="primary" @click="dialogVisible = true" class="float-left">Create Setup</el-button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <el-select v-model="branchs" @change="getSetup" multiple name="branch_code" clearable placeholder="Filter By Branch">
                        <el-option
                            v-for="item in branches"
                            :key="item.id"
                            :label="item.text"
                            :value="item.id">
                            </el-option>
                     </el-select>
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
                            <span class="text-muted">Grade Level</span>
                        </template>
                        <template slot-scope="scope">
                         <template v-if="scope.row.type == 1">
                              {{ scope.row.grade_name.grade_name }}
                        </template>
                         <template v-else>
                             NO GRADE LEVEL
                        </template>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="class_material_code"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Campus</span>
                        </template>
                        <template slot-scope="scope">
                            <span class="text-muted font-weight-bolder">{{ scope.row.campus }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="class_material_code"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Type</span>
                        </template>
                        <template slot-scope="scope">
                            <span class="text-muted font-weight-bolder">{{ scope.row.type == 1 ? 'Student' : 'Employee' }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="title"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Front ID</span>
                        </template>
                        <template slot-scope="scope">
                             <img :src="scope.row.id_front_path" width="100px" height="100px"/>
                        </template>
                    </el-table-column>
                     <el-table-column
                        prop="title"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Back ID</span>
                        </template>
                        <template slot-scope="scope">
                             <img :src="scope.row.id_back_path" width="100px" height="100px"/>
                        </template>
                    </el-table-column>
                    <el-table-column
                        prop="title"
                        min-width="200"
                        resizable
                        align="center"
                        show-overflow-tooltip>
                        <template slot="header">
                            <span class="text-muted">Back Content</span>
                        </template>
                        <template slot-scope="scope">
                            <img :src="scope.row.back_content" width="100px" height="100px"/>
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
                            <el-button type="danger" size="small" icon="el-icon-delete"  @click="handleDelete(scope.row.id)"> Delete</el-button>
                            <el-button type="success" size="small" icon="el-icon-user-solid" @click="isUpdate(scope.row)"> Update</el-button>
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
                  title="Create ID Setup"
                  :visible.sync="dialogVisible"
                  width="35%"
                  :before-close="resetForm">
                  <el-form ref="uploadForm" :rules="rules" :model="uploadForm" label-width="120px" label-position="top">
                          <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">Campus</label>
                          <el-form-item label="" prop="branch_code">
                              <el-select v-model="uploadForm.branch_code" @change="getGradelevel" multiple name="branch_code" clearable placeholder="Select">
                                <el-option
                                  v-for="item in branches"
                                  :key="item.id"
                                  :label="item.text"
                                  :value="item.id">
                                  </el-option>
                              </el-select>
                            </el-form-item>
                        <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">Type</label>
                          <el-form-item label="" prop="type">
                              <el-select v-model="uploadForm.type" value-key="id" @change="getGradelevel" clearable placeholder="Select">
                                <el-option
                                  v-for="item in types"
                                  :key="item.id"
                                  :label="item.name"
                                  :value="item.id">
                                  </el-option>
                              </el-select>
                           </el-form-item>    
                        <template v-if="uploadForm.type == 1">    
                            <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">Grade Level</label>
                             <el-form-item label="" prop="grade_code">
                                <el-select v-model="uploadForm.grade_code" default-first-option value-key="grade_name" clearable  clearable placeholder="Select">
                                    <el-option
                                    v-for="item in gradelevel"
                                    :key="item.grade_level_id"
                                    :value="item.grade_name">
                                    </el-option>
                                </el-select>
                              </el-form-item>
                            </template>
                            <el-form-item prop="id_front">
                              <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">ID Front</label>
                              <el-upload
                                  ref="upload-id-front"
                                  action=""
                                  name="id_front_path[]"
                                  :on-remove="handleRemove"
                                  :on-change="handleChange"
                                  :file-list="uploadForm.id_front"
                                  :auto-upload="false">
                                  <el-button size="small" type="primary">Click to upload</el-button>
                              </el-upload>
                          </el-form-item>
                          <el-form-item prop="id_back">
                              <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">ID Back</label>
                              <el-upload
                                  ref="upload-id-back"
                                  action=""
                                  name="id_back_path[]"
                                  :on-remove="handleRemoveIdBack"
                                  :on-change="handleChangeIdBack"
                                  :file-list="uploadForm.id_back"
                                  :auto-upload="false">
                                  <el-button size="small" type="primary">Click to upload</el-button>
                              </el-upload>
                          </el-form-item>
                              <el-form-item prop="back_content">
                              <label class="text-uppercase font-weight-bolder" style="margin-bottom: 0;">Back Content</label>
                              <el-upload
                                  ref="upload-id-back_content"
                                  action=""
                                  name="back_content[]"
                                  :on-remove="handleRemoveDigitalSignature"
                                  :on-change="handleChangeDigitalSignature"
                                  :file-list="uploadForm.back_content"
                                  :auto-upload="false">
                                  <el-button size="small" type="primary">Click to upload</el-button>
                              </el-upload>
                          </el-form-item>
                          <hr>
                          <div class="clearfix">
                              <el-button type="danger" class="float-right ml-2" v-if="setup.image_path">Remove</el-button>
                              <el-button :loading="isUploading" type="primary" @click="submitForm" class="float-right">Create Setup</el-button>
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
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    export default {
        components: { Navbar, Sidebar, Layout },
        data() {
                var validateUpload = (rule, value, callback) => {
                if (this.uploadForm.id_front.length < 1 ) {
                    callback(new Error('Please upload a id front image template'));
                } else if (this.uploadForm.id_back.length < 1 ) {
                    callback(new Error('Please upload a id back image template'));
                } else if (this.uploadForm.back_content.length < 1 ) {
                    callback(new Error('Please upload a back content image'));
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
                search: '',
                isUploading: false,
                uploadForm: {
                    grade_code: '',
                    branch_code: [],
                    id_front: [],
                    id_back: [],
                    back_content: [],
                    id: '',
                    type: '',
                },
                rules: {
                    grade_level_id: [
                        { required: true, message: 'Please select a grade level id', trigger: 'blur' },
                    ],
                    type: [
                        { required: true, message: 'Please select type', trigger: 'blur' },
                    ],
                    files: [
                        { validator: validateUpload, trigger: 'change' },
                    ],
                    branch_code: [
                        { validator: validateCampus, trigger: 'change' },
                    ],
                    id_front: [
                        { validator: validateUpload, trigger: 'change' },
                    ],
                    id_back: [
                        { validator: validateUpload, trigger: 'change' },
                    ],
                    back_content: [
                        { validator: validateUpload, trigger: 'change' },
                    ],
                },
                types: [
                    { 
                       id: '1', 
                       name: 'Student',
                    },
                    { 
                       id: '2', 
                       name: 'Employee',
                    },
                ],
                folder_name: this.$root.folder_name,
                branches: [],
                value: '',
                data: [],
                dialogVisible: false,
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {},
                gradelevel: [],
                isUpdating: false,
                student_level: [],
                branchs: [],
                filter: {
                    datas: [],
                    total: 0,
                    page: 1,
                    per_page: 10,
                },
            }
        },
        mounted() {
            this.getSetup();
            this.getBranches();
            this.validateCampus();
            this.getGradelevel();
        },
        methods: {
            handleDelete(id) {
                this.$confirm('Remove this setup?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/id_printing/id/delete',{id: id})
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
            handleRemove(file, fileList) {
                const index = this.uploadForm.id_front.indexOf(file);
                this.uploadForm.id_front.splice(index, 1);
            },
            handleRemoveIdBack(file, fileList) {
                const index = this.uploadForm.id_back.indexOf(file);
                this.uploadForm.id_back.splice(index, 1);
            },
            handleRemoveDigitalSignature(file, fileList) {
                const index = this.uploadForm.digital_signature.indexOf(file);
                this.uploadForm.back_content.splice(index, 1);
            },
            handleChange(file, fileList) {
               this.uploadForm.id_front = fileList;
            },
            handleChangeIdBack(file, fileList) {
                this.uploadForm.id_back = fileList;
            },
            handleChangeDigitalSignature(file, fileList) {
                this.uploadForm.back_content = fileList;
            },
            resetForm() {
                this.uploadForm = {
                    grade_code: '',
                    id_front: [],
                    id_back: [],
                    branch_code: [],
                    back_content: [],
                    id: '',
                };
                this.dialogVisible = false;
            },
            getSetup() {
                this.filter.search = this.search;
                this.filter.branchs = this.branchs;
                axios.post(this.folder_name + '/id_printing/request',this.filter)
                    .then(res => {
                        this.data = res.data;
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
            getGradelevel() {
                axios.post(this.folder_name + '/id_printing/branch/gradelevel', { branch_code: this.uploadForm.branch_code })
                    .then(res => {
                        this.gradelevel = res.data;
                        // this.uploadForm.fileList = [{'name': image.split('/')[5], 'url': image}];
                    });
            },
            submitForm() {
                this.$refs['uploadForm'].validate((valid) => {
                    if (valid) {
                        const form = this.$refs.uploadForm.$el;
                        let formData = new FormData(form);
                        formData.append('branch_code', this.uploadForm.branch_code);
                        formData.append('grade_level_id', this.uploadForm.grade_code);
                        formData.append('id', this.uploadForm.id);
                        formData.append('type', this.uploadForm.type);
                        this.isUploading = true;
                        axios.post(this.folder_name + '/id_printing/request/create', formData)
                            .then(res => {
                                this.isUploading = false;
                                this.getSetup();
                                this.$swal("Success", "ID Setup Created Successfully", "success");
                                this.resetForm();
                            });
                    }
                });
            },
            isUpdate(data) {
                this.isUpdating = true;
                if (this.isUpdating) {
                    this.dialogVisible = true;
                    this.uploadForm.branch_code = [data.branch_code];
                    this.getGradelevel();
                    this.uploadForm.id_front = [{uid: Math.random(), name: data.id_front_path.split('/').pop(), url: data.id_front_path }];
                    this.uploadForm.id_back = [{uid: Math.random(), name: data.id_back_path.split('/').pop(),   url: data.id_back_path }];
                    this.uploadForm.back_content = [{uid: Math.random(), name: data.back_content.split('/').pop(),   url: data.back_content }];
                    this.uploadForm.id = data.id;
                    this.uploadForm.type = data.type;
                    this.uploadForm.grade_code = data.grade_name.grade_name;
                }
            }
        }
    }
</script>
