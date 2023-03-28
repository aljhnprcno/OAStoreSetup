<template>
    <el-container>
        <el-main>
            <el-row type="flex" justify="center">
                <el-card style="width: 40%" shadow="hover">
                    <!-- <h1 class="heading">HR Payroll Inventory System</h1> -->
                    <center>
                        <img style="margin: 2rem;" src="public/assets/img/header_logo.png" height="175px">
                    </center>
                    <el-form ref="login_form" :model="login" @keyup.native.enter="onSubmit">
                        <el-form-item>
                            <p>Email / Employee ID / Control ID / Student ID</p>
                            <el-input v-model="login.email"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <p>Password</p>
                            <el-input v-model="login.password" show-password></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button class="pull-right" :loading="isLoading" type="primary" @click="onSubmit">Login</el-button>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-row>
        </el-main>
    </el-container>
</template>

<script>
import { Message } from 'element-ui'
export default {
    props : ['message'],
    data () {
        return {
            isLoading: false,
            login: {
                email: null,
                password: null
            }
        }
    },
    mounted() {
        this.showPrompt(this.$props.message, 'info')
    },
    methods: {
        onSubmit() {
            this.isLoading = true
            axios({
                method: 'POST',
                type: 'JSON',
                url: this.$root.folder_name + '/login',
                data: this.login,
            }).then(response => {
                this.isLoading = false
                if (response.data.status == 1) {
                    this.showPrompt(response.data.text, 'success')
                    window.location = response.data.redirect
                } else {
                    this.showPrompt(response.data.text, 'error')
                }
            }).catch(error => {
                this.isLoading = false
                this.$root.defaultError()
            })
        },
        showPrompt(message, type) {
            if (message != null && message !== "") {
                Message({
                    showClose: true,
                    message,
                    type,
                    duration: 3000,
                })
            }
        }
    }
}
</script>
