<template>
    <el-container style="height: 100%;">
        <Sidebar></Sidebar>
        <el-main class="wrapper__body">
            <Navbar title="Home"></Navbar>
            <el-container class="main-section"></el-container>
        </el-main>
    </el-container>
</template>

<script>
import Navbar from './../../components/admin/Navbar'
import Sidebar from './../../components/admin/Sidebar'
export default {
    components: { Navbar, Sidebar }
}
</script>
