<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="View Employee"></Navbar>
            <el-container class="main-section">
                <div class="employee-info-container">
                    <img :src="employee.picture" :alt="employee.full_name" class="employee-img">
                    <h3>{{ employee.full_name }}</h3>
                    <p>{{ employee.employee_id }} - {{ employee.gender }}</p>
                    <el-tabs v-model="activeTab">
                        <el-tab-pane label="Personal Data" name="0">
                            <h3>Personal Data</h3>
                            <el-form :model="employeeInfo" label-width="120px">
                                <el-form-item label="Employee No.">
                                    <el-input v-model="employee.employee_id" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="Surname">
                                    <el-input v-model="employee.lastname" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="First Name">
                                    <el-input v-model="employee.firstname" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="Middle Name">
                                    <el-input v-model="employee.middlename" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="Suffix">
                                    <el-input v-model="employee.ext_name" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="Gender (Sex)">
                                    <el-radio v-model="employee.gender" label="Male" disabled>Male</el-radio>
                                    <el-radio v-model="employee.gender" label="Female" disabled>Female</el-radio>
                                </el-form-item>
                                <el-form-item label="Date of Birth">
                                    <el-input v-model="employee.date_of_birth" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="SSS Full Name">
                                    <el-input v-model="employeeInfo.full_name_sss"></el-input>
                                </el-form-item>
                                <el-form-item label="Civil Status">
                                    <el-select v-model="employeeInfo.civil_status" placeholder="Please select">
                                        <el-option
                                            v-for="(item, index) in civilStatuses" :key="index"
                                            :label="item" :value="item">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="Religion">
                                    <el-select v-model="employeeInfo.religion_id" placeholder="Please select">
                                        <el-option
                                            v-for="(item, index) in religions" :key="index"
                                            :label="item.religion" :value="item.religion_id">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="Units Load">
                                    <el-input type="number" v-model="employeeInfo.units_load"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" :loading="isUpdateInfoLoading"
                                        @click="updateInfo">Save</el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                        <el-tab-pane label="Contact Information" name="1">
                            <h3>Contact Information</h3>
                            <el-form :model="employeeInfo" label-width="150px">
                                <el-form-item label="Home Phone No.">
                                    <el-input type="number" v-model="employeeInfo.home_phone"></el-input>
                                </el-form-item>
                                <el-form-item label="Email Address 1">
                                    <el-input :value="employee.email" disabled></el-input>
                                </el-form-item>
                                <el-form-item label="Email Address 2">
                                    <el-input v-model="employeeInfo.email_2"></el-input>
                                </el-form-item>
                                <el-form-item label="Province Address">
                                    <el-input v-model="employeeInfo.province_address"></el-input>
                                </el-form-item>
                                <el-form-item label="Province Phone No.">
                                    <el-input type="number" v-model="employeeInfo.province_phone"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" :loading="isUpdateInfoLoading"
                                        @click="updateInfo">Save</el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                        <el-tab-pane label="In case of Emergency" name="2">
                            <h3>In case of Emergency</h3>
                            <el-form :model="employeeInfo" label-width="150px">
                                <el-form-item label="Contact Person">
                                    <el-input v-model="employeeInfo.emergency_person"></el-input>
                                </el-form-item>
                                <el-form-item label="Relationship">
                                    <el-input v-model="employeeInfo.emergency_relationship"></el-input>
                                </el-form-item>
                                <el-form-item label="Home Phone No.">
                                    <el-input type="number" v-model="employeeInfo.emergency_phone"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" :loading="isUpdateInfoLoading"
                                        @click="updateInfo">Save</el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                        <el-tab-pane label="Hierarchy" name="3">
                            <h3>Hierarchy</h3>
                            <el-form :model="hierarchy" label-width="150px">
                                <el-form-item label="Department">
                                    <el-select @change="getPositions(true)" v-loading="isDepartmentsLoading" disabled
                                        v-model="hierarchy.department" placeholder="Please select">
                                        <el-option label="None" :value="JSON.stringify({
                                            dept_id: null, dept_type_name: null
                                        })"></el-option>
                                        <el-option
                                            v-for="(item, index) in hierarchy.departments" :key="index"
                                            :label="item.dept_name" :value="JSON.stringify({
                                                dept_id: item.dept_id, dept_type_name: item.dept_type_name
                                            })">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="Position">
                                    <el-select v-model="hierarchy.position" disabled
                                        v-loading="isPositionsLoading"
                                        placeholder="Please select">
                                        <el-option
                                            v-for="(item, index) in hierarchy.positions" :key="index"
                                            :label="item.name" :value="item.id">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                                <!-- <el-form-item>
                                    <el-button type="primary" :loading="isUpdateHierarchyLoading"
                                        @click="updateHierarchy">Save</el-button>
                                </el-form-item> -->
                            </el-form>
                        </el-tab-pane>
                        <el-tab-pane label="Permissions" name="4">
                            <h3>Permissions</h3>
                            <div class="permissions-container" v-loading="isPermissionsLoading">
                                <div>
                                    <h5>HR MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="view menu employees">Employees</p>
                                </div>
                                <div>
                                    <h5>INVENTORY SETTINGS MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory settings (warehouse)">Warehouse</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory settings (item category)">Item Category</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory settings (item database)">Item Database</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory settings (supplier database)">Supplier database</p>
                                </div>
                                <div>
                                    <h5>REPORTS</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory critical level reports">Critical Level Reports</p>
                                </div>
                                <div>
                                    <h5>SMR MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory supplies request creator">SMR Creator</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory supplies request approver (dept head)">SMR Approver (Department)</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory supplies request approver (property head)">SMR Approver (Property Head)</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory supplies request approver (csd)">SMR Approver (CSD)</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory supplies request approver (president)">SMR Approver (President)</p>
                                </div>
                                <div>
                                    <h5>PR MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory purchase request creator">PR Creator</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory purchase request approver">PR Approver</p>
                                </div>
                                <div>
                                    <h5>PO MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory purchase order creator">PO Creator</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory purchase order approver">PO Approver</p>
                                </div>
                                <div>
                                    <h5>SRR MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory receiving viewer">View List of SRR</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory receiving creator">Creator of SRR</p>
                                </div>
                                <div>
                                    <h5>WAYBILL MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory waybill viewer">View List of Waybill</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory waybill creator">Creator of Waybill</p>
                                </div>
                                <div>
                                    <h5>ISSUANCE MENU</h5>
                                    <p><input type="checkbox" v-model="permissions" value="inventory issuance viewer">View List of Issuance</p>
                                    <p><input type="checkbox" v-model="permissions" value="inventory issuance creator">Creator of Issuance</p>
                                </div>
                                <div>
                                    <h5>LIBRARY</h5>
                                    <p><input type="checkbox" v-model="permissions" value="library upload">Upload</p>
                                    <p><input type="checkbox" v-model="permissions" value="library settings">Settings</p>
                                    <p><input type="checkbox" v-model="permissions" value="library book requests">Book Requests</p>
                                    <p><input type="checkbox" v-model="permissions" value="library released books">Released Books</p>
                                </div>
                                <div>
                                    <h5>LEARNING MATERIALS</h5>
                                    <p><input type="checkbox" v-model="permissions" value="learning material settings">Settings</p>
                                    <p><input type="checkbox" v-model="permissions" value="learning material approval">Approval</p>
                                    <p><input type="checkbox" v-model="permissions" value="learning material requests">Requests</p>
                                </div>
                                <div>
                                    <h5>SYLLABUS</h5>
                                    <p><input type="checkbox" v-model="permissions" value="syllabus settings">Settings</p>
                                    <p><input type="checkbox" v-model="permissions" value="syllabus approval">Approval</p>
                                    <p><input type="checkbox" v-model="permissions" value="syllabus requests">Requests</p>
                                </div>
                                <div>
                                    <h5>RESEARCH</h5>
                                    <p><input type="checkbox" v-model="permissions" value="research settings">Settings</p>
                                    <p><input type="checkbox" v-model="permissions" value="research approval">Approval</p>
                                    <p><input type="checkbox" v-model="permissions" value="research requests">Requests</p>
                                </div>
                                <div>
                                    <el-button type="primary" :loading="isUpdatePermissionsLoading"
                                        @click="updatePermissions">Save</el-button>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="Family / Dependents" name="5">
                            <div class="dependent-header">
                                <h3>Family / Dependents</h3>
                                <el-button @click="isAddDependentVisible = true"
                                    type="primary" icon="el-icon-plus">Add</el-button>
                            </div>
                            <el-table :data="employee.dependents"
                                v-loading="isDependentsLoading" style="width: 100%"
                                empty-text="No records found."
                                :border="true">
                                <el-table-column
                                    prop="full_name"
                                    label="Full Name">
                                </el-table-column>
                                <el-table-column
                                    prop="date_of_birth"
                                    label="Date of Birth">
                                </el-table-column>
                                <el-table-column
                                    prop="relationship"
                                    label="Relationship">
                                </el-table-column>
                                <el-table-column
                                    label="Is Dependent">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.is_dependent ? 'Yes' : 'No' }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    prop="occupation"
                                    label="Occupation">
                                </el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="Educational Background" name="6">
                            <div class="educ-bg-header">
                                <h3>Educational Background</h3>
                                <el-button @click="isAddEducationBGVisible = true"
                                    type="primary" icon="el-icon-plus">Add</el-button>
                            </div>
                            <el-table :data="employee.educational_bgs"
                                v-loading="isEducationBGLoading"
                                empty-text="No records found."
                                style="width: 100%" :border="true">
                                <el-table-column
                                    prop="from"
                                    label="From">
                                </el-table-column>
                                <el-table-column
                                    prop="to"
                                    label="To">
                                </el-table-column>
                                <el-table-column
                                    label="Education Type">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.education_type.title }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    prop="school"
                                    label="School">
                                </el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="Work Experience" name="7">
                            <div class="work-exp-header">
                                <h3>Work Experience</h3>
                                <el-button @click="isAddWorkExpVisible = true"
                                    type="primary" icon="el-icon-plus">Add</el-button>
                            </div>
                            <el-table :data="employee.work_experiences"
                                v-loading="isWorkExpLoading" style="width: 100%"
                                empty-text="No records found."
                                :border="true">
                                <el-table-column
                                    prop="from"
                                    label="From">
                                </el-table-column>
                                <el-table-column
                                    prop="to"
                                    label="To">
                                </el-table-column>
                                <el-table-column
                                    prop="company_name"
                                    label="Company Name">
                                </el-table-column>
                                <el-table-column
                                    prop="address"
                                    label="Address">
                                </el-table-column>
                                <el-table-column
                                    prop="position"
                                    label="Position">
                                </el-table-column>
                                <el-table-column
                                    prop="reason"
                                    label="Reason">
                                </el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="License" name="8">
                            <h3>License</h3>
                            <el-form v-loading="isLicenseLoading" :model="license" label-width="150px">
                                <el-form-item label="Professional License">
                                    <el-input v-model="license.professional_license"></el-input>
                                </el-form-item>
                                <el-form-item label="Registration Number">
                                    <el-input v-model="license.registration_number"></el-input>
                                </el-form-item>
                                <el-form-item label="Registration Date">
                                    <el-date-picker v-model="license.registration_date"
                                        type="date" placeholder="Please select">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item label="Expiration Date">
                                    <el-date-picker v-model="license.expiration_date"
                                        type="date" placeholder="Please select">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" :loading="isUpdateLicenseLoading"
                                        @click="updateLicense">Save</el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                        <el-tab-pane label="Trainings and Seminars Attended" name="9">
                            <div class="trainings-header">
                                <h3>Trainings and Seminars Attended</h3>
                                <el-button @click="isAddTrainingVisible = true"
                                    type="primary" icon="el-icon-plus">Add</el-button>
                            </div>
                            <el-table :data="employee.trainings"
                                v-loading="isTrainingLoading" style="width: 100%"
                                empty-text="No records found."
                                :border="true">
                                <el-table-column
                                    prop="title"
                                    label="Title">
                                </el-table-column>
                                <el-table-column
                                    prop="accreditation_number"
                                    label="Accreditation Number">
                                </el-table-column>
                                <el-table-column
                                    label="Dates">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.dates[0] }} to {{ scope.row.dates[1] }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    prop="venue"
                                    label="Venue">
                                </el-table-column>
                                <el-table-column
                                    prop="units"
                                    width="125"
                                    label="Units Earned">
                                </el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="Hiring Information" name="10">
                            <div class="hire-info-header">
                                <h3>Hiring Information</h3>
                                <el-button @click="isAddHireInfoVisible = true"
                                    type="primary" icon="el-icon-plus">Add</el-button>
                            </div>
                            <el-table :data="employee.hire_infos"
                                v-loading="isHireInfoLoading" style="width: 100%"
                                empty-text="No records found."
                                :border="true">
                                <el-table-column
                                    prop="hire_date"
                                    label="Date">
                                </el-table-column>
                                <el-table-column
                                    label="Position">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.position.description }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    label="Division">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.division.description }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    label="Department">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.department.description }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    label="Section">
                                    <template slot-scope="scope">
                                        <span>{{ scope.row.section.description }}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                    prop="location"
                                    label="Location">
                                </el-table-column>
                                <el-table-column
                                    prop="nature"
                                    label="Nature">
                                </el-table-column>
                            </el-table>
                        </el-tab-pane>
                        <el-tab-pane label="Accounting Data" name="11">
                            <h3>Accounting Data</h3>
                            <el-form v-loading="isAccountingDataLoading" :model="accountingData" label-width="180px">
                                <el-form-item label="SSS No.">
                                    <el-input v-model="accountingData.sss_number"></el-input>
                                </el-form-item>
                                <el-form-item label="Skip SSS Deduction">
                                    <input type="checkbox" v-model="accountingData.is_sss_enabled">
                                </el-form-item>
                                <el-form-item label="Tax ID No.">
                                    <el-input v-model="accountingData.tax_number"></el-input>
                                </el-form-item>
                                <el-form-item label="Skip Tax Deduction">
                                    <input type="checkbox" v-model="accountingData.is_tax_enabled">
                                </el-form-item>
                                <el-form-item label="Pag-ibig Fund No.">
                                    <el-input v-model="accountingData.hdmf_number"></el-input>
                                </el-form-item>
                                <el-form-item label="Skip Pag-ibig Deduction">
                                    <input type="checkbox" v-model="accountingData.is_hdmf_enabled">
                                </el-form-item>
                                <el-form-item label="PhilHealth ID No.">
                                    <el-input v-model="accountingData.phic_number"></el-input>
                                </el-form-item>
                                <el-form-item label="Skip PhilHealth Deduction">
                                    <input type="checkbox" v-model="accountingData.is_phic_enabled">
                                </el-form-item>
                                <el-form-item label="Retirement Fund Percentage">
                                    <el-input v-model="accountingData.retirement_fund_percentage"></el-input>
                                </el-form-item>
                                <el-form-item label="Tax Category">
                                    <el-input v-model="accountingData.tax_category"></el-input>
                                </el-form-item>
                                <el-form-item label="Bank">
                                    <el-input v-model="accountingData.bank"></el-input>
                                </el-form-item>
                                <el-form-item label="Account No.">
                                    <el-input v-model="accountingData.account_number"></el-input>
                                </el-form-item>
                                <el-form-item label="Payment Type">
                                    <el-radio v-model="accountingData.payment_type" :label="1">Cash</el-radio>
                                    <el-radio v-model="accountingData.payment_type" :label="2">Bank</el-radio>
                                    <el-radio v-model="accountingData.payment_type" :label="3">Voucher</el-radio>
                                </el-form-item>
                                <el-form-item label="Separation Date (BIR)">
                                    <el-date-picker v-model="accountingData.separation_date_bir"
                                        type="date" placeholder="Please select">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" :loading="isUpdateAccountingDataLoading"
                                        @click="updateAccountingData">Save</el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                        <el-tab-pane label="Fixed Deductions" name="12">
                            <h3>Fixed Deductions</h3>
                            <el-form v-loading="isGovDeductionsLoading" :model="govDeductions" label-width="170px">
                                <el-form-item label="SSS (Employer)">
                                    <el-input type="number" v-model="govDeductions.sss_er"></el-input>
                                </el-form-item>
                                <el-form-item label="SSS (Employee)">
                                    <el-input type="number" v-model="govDeductions.sss_ee"></el-input>
                                </el-form-item>
                                <el-form-item label="Pag-ibig (Employer)">
                                    <el-input type="number" v-model="govDeductions.hdmf_er"></el-input>
                                </el-form-item>
                                <el-form-item label="Pag-ibig (Employee)">
                                    <el-input type="number" v-model="govDeductions.hdmf_ee"></el-input>
                                </el-form-item>
                                <el-form-item label="PhilHealth (Employer)">
                                    <el-input type="number" v-model="govDeductions.phic_er"></el-input>
                                </el-form-item>
                                <el-form-item label="PhilHealth (Employee)">
                                    <el-input type="number" v-model="govDeductions.phic_ee"></el-input>
                                </el-form-item>
                                <el-form-item label="ECC">
                                    <el-input type="number" v-model="govDeductions.ecc"></el-input>
                                </el-form-item>
                                <el-form-item label="Withholding Tax">
                                    <el-input type="number" v-model="govDeductions.tax"></el-input>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" :loading="isUpdateGovDeductionLoading"
                                        @click="updateGovDeductions">Save</el-button>
                                </el-form-item>
                            </el-form>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </el-container>
            <el-dialog title="Add Dependent" :visible.sync="isAddDependentVisible">
                <el-form :model="dependent" label-width="120px">
                    <el-form-item label="Full Name">
                        <el-input v-model="dependent.full_name"></el-input>
                    </el-form-item>
                    <el-form-item label="Date of Birth">
                        <el-date-picker v-model="dependent.date_of_birth"
                            type="date" placeholder="Please select">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="Relationship">
                        <el-input v-model="dependent.relationship"></el-input>
                    </el-form-item>
                    <el-form-item label="Is Dependent">
                        <el-checkbox v-model="dependent.is_dependent"></el-checkbox>
                    </el-form-item>
                    <el-form-item label="Occupation">
                        <el-input v-model="dependent.occupation"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="isAddDependentVisible = false">Cancel</el-button>
                    <el-button type="primary" :loading="isAddDependentLoading" @click="addDependent()">Save</el-button>
                </span>
            </el-dialog>
            <el-dialog title="Add Educational Background" :visible.sync="isAddEducationBGVisible">
                <el-form :model="educationalBG" label-width="185px">
                    <el-form-item label="Student ID">
                        <el-input v-model="educationalBG.student_id"></el-input>
                    </el-form-item>
                    <el-form-item label="Full Name">
                        <el-input v-model="educationalBG.student_name"></el-input>
                    </el-form-item>
                    <el-form-item label="Period of Schooling">
                        <el-date-picker style="width: 100px;"
                            v-model="educationalBG.years[0]"
                            type="year"
                            placeholder="From"
                            value-format="yyyy">
                        </el-date-picker>
                        <el-date-picker style="width: 100px;"
                            v-model="educationalBG.years[1]"
                            type="year"
                            placeholder="To"
                            value-format="yyyy">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="Education Type">
                        <el-select v-model="educationalBG.education_type_id" placeholder="Please select">
                            <el-option
                                v-for="(item, index) in educationTypes" :key="index"
                                :label="item.title" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Name of School">
                        <el-input v-model="educationalBG.school"></el-input>
                    </el-form-item>
                    <el-form-item label="Awards / Honors">
                        <el-input v-model="educationalBG.awards"></el-input>
                    </el-form-item>
                    <el-form-item label="Scholastic Honors">
                        <el-input v-model="educationalBG.scholastic_honor"></el-input>
                    </el-form-item>
                    <el-form-item label="Last Semester Attended">
                        <el-input v-model="educationalBG.last_sem_attended"></el-input>
                    </el-form-item>
                    <el-form-item label="Level / Attainment">
                        <el-input v-model="educationalBG.level_attainment"></el-input>
                    </el-form-item>
                    <el-form-item label="Course, Program or Degree">
                        <el-input v-model="educationalBG.course"></el-input>
                    </el-form-item>
                    <el-form-item label="Units Earned">
                        <el-input v-model="educationalBG.units"></el-input>
                    </el-form-item>
                    <el-form-item label="As of date">
                        <el-date-picker
                            v-model="educationalBG.as_of"
                            type="date"
                            placeholder="Please select"
                            value-format="yyyy-MM-dd">
                        </el-date-picker>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="isAddEducationBGVisible = false">Cancel</el-button>
                    <el-button type="primary" :loading="isAddEducationBGLoading" @click="addEducationalBG()">Save</el-button>
                </span>
            </el-dialog>
            <el-dialog title="Add Work Experience" :visible.sync="isAddWorkExpVisible">
                <el-form :model="workExperience" label-width="120px">
                    <el-form-item label="From">
                        <el-date-picker v-model="workExperience.from"
                            type="date" placeholder="Please select">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="To">
                        <el-date-picker v-model="workExperience.to"
                            type="date" placeholder="Please select">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="Company Name">
                        <el-input v-model="workExperience.company_name"></el-input>
                    </el-form-item>
                    <el-form-item label="Address">
                        <el-input v-model="workExperience.address"></el-input>
                    </el-form-item>
                    <el-form-item label="Position">
                        <el-input v-model="workExperience.position"></el-input>
                    </el-form-item>
                    <el-form-item label="Reason">
                        <el-input v-model="workExperience.reason"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="isAddWorkExpVisible = false">Cancel</el-button>
                    <el-button type="primary" :loading="isAddWorkExpLoading" @click="addWorkExp()">Save</el-button>
                </span>
            </el-dialog>
            <el-dialog title="Add Trainings and Seminars Attended" :visible.sync="isAddTrainingVisible">
                <el-form :model="training" label-width="175px">
                    <el-form-item label="Title">
                        <el-input v-model="training.title"></el-input>
                    </el-form-item>
                    <el-form-item label="Accreditation Number">
                        <el-input type="number" v-model="training.accreditation_number"></el-input>
                    </el-form-item>
                    <el-form-item label="Date">
                        <el-date-picker
                            v-model="training.dates"
                            type="daterange"
                            range-separator="-"
                            start-placeholder="From"
                            end-placeholder="To"
                            value-format="yyyy-MM-dd">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="Venue">
                        <el-input v-model="training.venue"></el-input>
                    </el-form-item>
                    <el-form-item label="Units Earned">
                        <el-input v-model="training.units"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="isAddTrainingVisible = false">Cancel</el-button>
                    <el-button type="primary" :loading="isAddTrainingLoading" @click="addTraining()">Save</el-button>
                </span>
            </el-dialog>
            <el-dialog title="Add Hiring Information" :visible.sync="isAddHireInfoVisible">
                <el-form :model="hireInfo" label-width="150px">
                    <el-form-item label="Date">
                        <el-date-picker v-model="hireInfo.hire_date"
                            type="date" placeholder="Please select">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="Position">
                        <el-select v-model="hireInfo.position_id" placeholder="Please select">
                            <el-option
                                v-for="(item, index) in positions" :key="index"
                                :label="item.description" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Division">
                        <el-select v-model="hireInfo.division_id" placeholder="Please select">
                            <el-option
                                v-for="(item, index) in divisions" :key="index"
                                :label="item.description" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Department">
                        <el-select v-model="hireInfo.department_id" placeholder="Please select">
                            <el-option
                                v-for="(item, index) in departments" :key="index"
                                :label="item.description" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Section">
                        <el-select v-model="hireInfo.section_id" placeholder="Please select">
                            <el-option
                                v-for="(item, index) in sections" :key="index"
                                :label="item.description" :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Location">
                        <el-input v-model="hireInfo.location"></el-input>
                    </el-form-item>
                    <el-form-item label="Nature">
                        <el-input v-model="hireInfo.nature"></el-input>
                    </el-form-item>
                    <el-form-item label="Accounting Unit">
                        <el-input v-model="hireInfo.accounting_unit"></el-input>
                    </el-form-item>
                    <el-form-item label="For Movement">
                        <el-input v-model="hireInfo.for_movement"></el-input>
                    </el-form-item>
                    <el-form-item label="Last Edit Date:">
                        <el-input v-model="hireInfo.updated_at" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="Created Date:">
                        <el-input v-model="hireInfo.created_at" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="School Branch">
                        <el-select v-model="hireInfo.school_branch" placeholder="Please select">
                            <el-option v-for="(item, index) in schoolBranches"
                                :key="index" :label="item" :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Employee Type">
                        <el-radio v-model="hireInfo.employee_type" label="full_time_faculty">Full Time Faculty</el-radio><br/>
                        <el-radio v-model="hireInfo.employee_type" label="part_time_faculty">Part-Time Faculty</el-radio><br/>
                        <el-radio v-model="hireInfo.employee_type" label="admin_employee_wo_teaching_loads">Admin Employee W/o Teaching Loads</el-radio><br/>
                        <el-radio v-model="hireInfo.employee_type" label="admin_employee_w_teaching_loads">Admin Employee W/ Teaching Loads</el-radio><br/>
                        <el-radio v-model="hireInfo.employee_type" label="special_lecturer">Special Lecturer</el-radio><br/>
                        <el-radio v-model="hireInfo.employee_type" label="special_lecturer_part_time">Special Lecturer (Part-Time)</el-radio><br/>
                        <input type="checkbox" v-model="hireInfo.is_bundy_clock_exempted"><span>&nbsp;With Bundy Clock Exemption</span><br/>
                        <input type="checkbox" v-model="hireInfo.is_late_undertime_exempted"><span>&nbsp;Late and Undertime Exemption</span><br/>
                    </el-form-item>
                    <el-form-item label="Posting Account">
                        <el-input v-model="hireInfo.posting_account"></el-input>
                    </el-form-item>
                    <el-form-item label="Confidential Level">
                        <el-input v-model="hireInfo.confidential_level"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                    <el-button @click="isAddHireInfoVisible = false">Cancel</el-button>
                    <el-button type="primary" :loading="isAddHireInfoLoading" @click="addHireInfo()">Save</el-button>
                </span>
            </el-dialog>
        </el-main>
    </el-container>
</template>

<script>
import Navbar from './../../../components/admin/Navbar'
import Sidebar from './../../../components/admin/Sidebar'
export default {
    components: { Navbar, Sidebar },
    data () {
        return {
            activeTab: '0',
            isAddDependentLoading: false,
            isAddEducationBGLoading: false,
            isAddHireInfoLoading: false,
            isAddTrainingLoading: false,
            isAddWorkExpLoading: false,
            isDepartmentsLoading: false,
            isPositionsLoading: false,
            isDependentsLoading: false,
            isEducationBGLoading: false,
            isAccountingDataLoading: false,
            isGovDeductionsLoading: false,
            isHireInfoLoading: false,
            isLicenseLoading: false,
            isPermissionsLoading: false,
            isTrainingLoading: false,
            isWorkExpLoading: false,
            isUpdateAccountingDataLoading: false,
            isUpdateGovDeductionLoading: false,
            isUpdateInfoLoading: false,
            // isUpdateHierarchyLoading: false,
            isUpdateLicenseLoading: false,
            isUpdatePermissionsLoading: false,
            isAddDependentVisible: false,
            isAddEducationBGVisible: false,
            isAddHireInfoVisible: false,
            isAddTrainingVisible: false,
            isAddWorkExpVisible: false,
            employeeInfo: {
                full_name_sss: null,
                civil_status: null,
                religion_id: null,
                units_load: null,

                home_phone: null,
                email_2: null,
                province_address: null,
                province_phone: null,

                emergency_person: null,
                emergency_relationship: null,
                emergency_phone: null
            },
            hierarchy: {
                department: null,
                departments: [],
                position: null,
                positions: []
            },
            permissions: this.employee.permissions,
            license: {
                professional_license: null,
                registration_number: null,
                registration_date: null,
                expiration_date: null
            },
            dependent: {
                full_name: null,
                date_of_birth: null,
                relationship: null,
                is_dependent: false,
                occupation: null
            },
            educationalBG: {
                education_type_id: null,
                student_id: null,
                student_name: null,
                years: [],
                school: null,
                awards: null,
                scholastic_honor: null,
                last_sem_attended: null,
                level_attainment: null,
                course: null,
                units: null,
                as_of: null
            },
            workExperience: {
                from: null,
                to: null,
                company_name: null,
                address: null,
                position: null,
                reason: null
            },
            training: {
                title: null,
                accreditation_number: null,
                dates: null,
                venue: null,
                units: null
            },
            hireInfo: {
                position_id: null,
                division_id: null,
                department_id: null,
                section_id: null,
                created_by_user_id: null,
                updated_by_user_id: null,
                hire_date: null,
                location: null,
                nature: null,
                accounting_unit: null,
                for_movement: null,
                school_branch: null,
                employee_type: 'full_time_faculty',
                is_bundy_clock_exempted: false,
                is_late_undertime_exempted: false,
                posting_account: null,
                confidential_level: null
            },
            accountingData: {
                sss_number: null,
                tax_number: null,
                hdmf_number: null,
                phic_number: null,
                retirement_fund_percentage: null,
                tax_category: null,
                bank: null,
                account_number: null,
                payment_type: 1,
                separation_date_bir: null,

                is_sss_enabled: false,
                is_hdmf_enabled: false,
                is_phic_enabled: false,
                is_tax_enabled: false,
            },
            govDeductions: {
                sss_er: 0,
                sss_ee: 0,
                hdmf_er: 0,
                hdmf_ee: 0,
                phic_er: 0,
                phic_ee: 0,
                tax: 0,
                ecc: 0
            },
            civilStatuses: [
                'Single', 'Married', 'Divored', 'Widowed'
            ],
            schoolBranches: [
                'Greenhills Branch',
                'Sta. Ana Branch',
                'Las Piñas Branch',
                'Fairview Branch',
                'Angeles Branch'
            ]
        }
    },
    props: {
        employee: Object,
        educationTypes: Array,
        scholasticHonors: Array,
        lastSemAttendeds: Array,
        levelAttainments: Array,
        religions: Array,
        positions: Array,
        divisions: Array,
        departments: Array,
        sections: Array
    },
    mounted() {
        console.log(this.employee)
        if (this.employee.info) {
            this.employeeInfo = this.employee.info
        }
        if (this.employee.license) {
            this.license = this.employee.license
        }
        if (this.employee.accounting_data) {
            this.accountingData = this.employee.accounting_data
            this.accountingData.is_sss_enabled = !this.employee.gov_deductions.is_sss_enabled
            this.accountingData.is_tax_enabled = !this.employee.gov_deductions.is_tax_enabled
            this.accountingData.is_hdmf_enabled = !this.employee.gov_deductions.is_hdmf_enabled
            this.accountingData.is_phic_enabled = !this.employee.gov_deductions.is_phic_enabled
        }
        if (this.employee.gov_deductions) {
            this.govDeductions = this.employee.gov_deductions
        }
        this.getHierarchy()
    },
    methods: {
        addDependent() {
            this.isAddDependentLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/dependents/add',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.dependent
                }
            }).then(response => {
                this.isAddDependentLoading = false
                this.isAddDependentVisible = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getDependents()
            }).catch(error => {
                this.isAddDependentLoading = false
                this.$root.defaultError()
            })
        },
        addEducationalBG() {
            this.isAddEducationBGLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/educational_bg/add',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.educationalBG
                }
            }).then(response => {
                this.isAddEducationBGLoading = false
                this.isAddEducationBGVisible = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getEducationBGs()
            }).catch(error => {
                this.isAddEducationBGLoading = false
                this.$root.defaultError()
            })
        },
        addWorkExp() {
            this.isAddWorkExpLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/work_experiences/add',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.workExperience
                }
            }).then(response => {
                this.isAddWorkExpVisible = false
                this.isAddWorkExpLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getWorkExps()
            }).catch(error => {
                this.isAddWorkExpLoading = false
                this.$root.defaultError()
            })
        },
        addTraining() {
            this.isAddTrainingLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/trainings/add',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.training
                }
            }).then(response => {
                this.isAddTrainingVisible = false
                this.isAddTrainingLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getTrainings()
            }).catch(error => {
                this.isAddTrainingLoading = false
                this.$root.defaultError()
            })
        },
        addHireInfo() {
            this.isAddHireInfoLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/hiring_infos/add',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.hireInfo
                }
            }).then(response => {
                this.isAddHireInfoVisible = false
                this.isAddHireInfoLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getHireInfos()
            }).catch(error => {
                this.isAddHireInfoLoading = false
                this.$root.defaultError()
            })
        },
        updateInfo() {
            this.isUpdateInfoLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/update_info',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.employeeInfo
                }
            }).then(response => {
                this.isUpdateInfoLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
            }).catch(error => {
                this.isUpdateInfoLoading = false
                this.$root.defaultError()
            })
        },
        // updateHierarchy() {
        //     this.isUpdateHierarchyLoading = true
        //     axios({
        //         method: 'POST',
        //         type: 'JSON',
        //         url: '/library/hierarchy/update',
        //         data: {
        //             id: this.employee.id,
        //             branch_code: this.employee.branch_code,
        //             position_id: this.hierarchy.position
        //         }
        //     }).then(response => {
        //         this.isUpdateHierarchyLoading = false
        //         this.$swal(response.data.title, response.data.text, response.data.type)
        //     }).catch(error => {
        //         this.isUpdateHierarchyLoading = false
        //         this.$root.defaultError()
        //     })
        // },
        updatePermissions() {
            this.isUpdatePermissionsLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/employee_permissions/update',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    permissions: this.permissions
                }
            }).then(response => {
                this.isUpdatePermissionsLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
            }).catch(error => {
                this.isUpdatePermissionsLoading = false
                this.$root.defaultError()
            })
        },
        updateLicense() {
            this.isUpdateLicenseLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/license/save',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.license
                }
            }).then(response => {
                this.isUpdateLicenseLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getLicense()
            }).catch(error => {
                this.isUpdateLicenseLoading = false
                this.$root.defaultError()
            })
        },
        updateAccountingData() {
            this.isUpdateAccountingDataLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/accounting_data/save',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.accountingData
                }
            }).then(response => {
                this.isUpdateAccountingDataLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getAccountingData()
            }).catch(error => {
                this.isUpdateAccountingDataLoading = false
                this.$root.defaultError()
            })
        },
        updateGovDeductions() {
            this.isUpdateGovDeductionLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/gov_deductions/save',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    data: this.govDeductions
                }
            }).then(response => {
                this.isUpdateGovDeductionLoading = false
                this.$swal(response.data.title, response.data.text, response.data.type)
                this.getGovDeductions()
            }).catch(error => {
                this.isUpdateGovDeductionLoading = false
                this.$root.defaultError()
            })
        },
        getHierarchy() {
            this.isDepartmentsLoading = true
            this.isPositionsLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/hierarchy/get_hierarchy',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    academic_position_id: this.employee.academic_position_id,
                    nonacademic_position_id: this.employee.nonacademic_position_id
                }
            }).then(response => {
                this.isDepartmentsLoading = false
                this.isPositionsLoading = false
                this.hierarchy.department = JSON.stringify({
                    dept_id: response.data.department ? response.data.department.dept_id : null,
                    dept_type_name: response.data.department ? response.data.department.dept_type_name : null
                })
                this.hierarchy.position = response.data.position || null
                this.getDepartments()
                this.getPositions()
            }).catch(error => {
                this.isDepartmentsLoading = false
                this.isPositionsLoading = false
                this.$root.defaultError()
            })
        },
        getDepartments() {
            this.isDepartmentsLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/hierarchy/get_departments',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                }
            }).then(response => {
                this.isDepartmentsLoading = false
                this.hierarchy.departments = response.data
            }).catch(error => {
                this.isDepartmentsLoading = false
                this.$root.defaultError()
            })
        },
        getPositions(isChanged = false) {
            this.isPositionsLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/hierarchy/get_positions',
                data: {
                    id: this.employee.id,
                    branch_code: this.employee.branch_code,
                    hierarchy: this.hierarchy.department
                }
            }).then(response => {
                if (isChanged) {
                    this.hierarchy.position = null
                }
                this.isPositionsLoading = false
                this.hierarchy.positions = response.data
            }).catch(error => {
                this.isPositionsLoading = false
                this.$root.defaultError()
            })
        },
        getDependents() {
            this.isDependentsLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/dependents/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isDependentsLoading = false
                this.employee.dependents = response.data
            }).catch(error => {
                this.isDependentsLoading = false
                this.$root.defaultError()
            })
        },
        getEducationBGs() {
            this.isEducationBGLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/educational_bg/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isEducationBGLoading = false
                this.employee.educational_bgs = response.data
            }).catch(error => {
                this.isEducationBGLoading = false
                this.$root.defaultError()
            })
        },
        getWorkExps() {
            this.isWorkExpLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/work_experiences/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isWorkExpLoading = false
                this.employee.work_experiences = response.data
            }).catch(error => {
                this.isWorkExpLoading = false
                this.$root.defaultError()
            })
        },
        getLicense() {
            this.isLicenseLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/license/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isLicenseLoading = false
                this.employee.license = response.data
                this.license = response.data
            }).catch(error => {
                this.isLicenseLoading = false
                this.$root.defaultError()
            })
        },
        getTrainings() {
            this.isTrainingLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/trainings/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isTrainingLoading = false
                this.employee.trainings = response.data
            }).catch(error => {
                this.isTrainingLoading = false
                this.$root.defaultError()
            })
        },
        getHireInfos() {
            this.isHireInfoLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/hiring_infos/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isHireInfoLoading = false
                this.employee.hire_infos = response.data
            }).catch(error => {
                this.isHireInfoLoading = false
                this.$root.defaultError()
            })
        },
        getAccountingData() {
            this.isAccountingDataLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/accounting_data/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isAccountingDataLoading = false
                this.employee.accounting_data = response.data
                this.accountingData = response.data
            }).catch(error => {
                this.isAccountingDataLoading = false
                this.$root.defaultError()
            })
        },
        getGovDeductions() {
            this.isGovDeductionsLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/gov_deductions/get',
                data: {id: this.employee.id, branch_code: this.employee.branch_code}
            }).then(response => {
                this.isGovDeductionsLoading = false
                this.employee.gov_deductions = response.data
                this.govDeductions = response.data
            }).catch(error => {
                this.isGovDeductionsLoading = false
                this.$root.defaultError()
            })
        }
    }
}
</script>
