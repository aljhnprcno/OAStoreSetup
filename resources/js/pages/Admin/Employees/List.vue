<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="Employees"></Navbar>
            <el-container class="main-section" direction="vertical">
                <el-container style="margin-bottom: 1rem;">
                    <span class="search-label">Branch:</span>
                    <el-select v-model="branch" placeholder="Select a branch" style="margin-right: 1rem;">
                        <el-option
                            v-for="(item, index) in branches"
                            :key="index"
                            :label="item.text"
                            :value="item.id">
                        </el-option>
                    </el-select>
                    <el-button type="primary" icon="el-icon-search"
                        style="margin-right: 1rem;"
                        @click="getEmployees(branch)">Search
                    </el-button>
                    <el-input v-model="searchEmployee"
                        style="width: 25%;"
                        placeholder="Enter a name"
                        prefix-icon="el-icon-search">
                    </el-input>
                </el-container>
                <el-container>
                    <div class="row row-cols-1" :class="{'row-cols-md-4': filteredEmployees.length > 1}">
                        <div class="col mb-3" v-for="(emp, index) in filteredEmployees" :key="index">
                            <div @click="viewEmployee(emp.id)" class="card">
                                <img :src="emp.picture" class="employee-img card-img-top" :alt="emp.full_name">
                                <div class="card-body">
                                    <p class="card-text">{{ emp.full_name | trimString(20) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-container>
            </el-container>
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
            isEmployeesLoading: false,
            branch: this.branches[0].id,
            searchEmployee: null,
            employees: []
        }
    },
    props: {
        branches: Array
    },
    mounted() {
        this.getEmployees(this.branch)
    },
    computed: {
        filteredEmployees: function() {
            if (this.searchEmployee)
                return this.employees.filter(emp => {
                    return this.searchEmployee.toLowerCase().split(' ').every(v => emp.firstname.toLowerCase().includes(v) || emp.lastname.toLowerCase().includes(v))
                })
            else return this.employees
        }
    },
    methods: {
        viewEmployee(id) {
            window.location = '/employees/view/' + this.branch + '/' + id
        },
        getEmployees(branch) {
            this.isEmployeesLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: '/library/get_employees',
                data: {branch: branch}
            }).then(response => {
                this.isEmployeesLoading = false
                this.employees = response.data
            }).catch(error => {
                this.isEmployeesLoading = false
                this.$root.defaultError()
            })
        }
    }
}
</script>
