<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="Catalog"></Navbar>
            <section class="main-section">
                <admin-template />
                <!-- <admin-template v-if="isAdmin()" /> -->
                <!-- <general-template v-else-if="isEmployee()" /> -->
            </section>
        </el-main>
    </el-container>
</template>
<script>
    import Navbar from './../../../components/admin/Navbar';
    import Sidebar from './../../../components/admin/Sidebar';
    import AdminTemplate from './../../../pages/General/Library/AdminTemplate';
    import GeneralTemplate from './../../../pages/General/Library/GeneralTemplate';
    export default {
        components: { Navbar, Sidebar, AdminTemplate, GeneralTemplate },
        props: {
            status: String,
        },
        data() {
            return {

            }
        },
        mounted() {

        },
        methods: {
            isAdmin() {
                return this.$root.auth.info.user_type === "App\\Services\\Users\\User";
            },
            isEmployee() {
                return this.$root.auth.info.user_type === "App\\Services\\Employees\\Employee";
            },
        }
    }
</script>
