<template>
    <div>
        <el-menu class="el-menu-demo" mode="horizontal" v-if="!display_only" style="position: fixed; top: 0; width: 100%; z-index: 99;">
            <el-menu-item>
                <img :src="img" width="50px" height="50px"/>
                <b style="font-size: 22px; color: black; padding-left: 10px;" v-if="isWindowOnDesktop"></b>
            </el-menu-item>
            <template>
                  <el-submenu index="2" class="dock-right">
                        <template slot="title">{{ display_name }}</template>
                        <el-menu-item @click="redirect('/dashboard')">Dashboard</el-menu-item>
                        <el-menu-item v-if="$root.auth.info.type == 'Employee'" @click="redirect('/home')">Employee Dashboard</el-menu-item>
                        <el-menu-item @click="redirect('/dashboard/qrcode')">View QR</el-menu-item>
                        <el-menu-item @click="$root.logout()">Logout</el-menu-item>
                </el-submenu>
            </template>
            <el-menu-item index="1" class="dock-right" style="border-bottom: 0px; cursor: default;">
                <el-dropdown @command="navigate" placement="bottom-end" trigger="click">
                   
                    <el-dropdown-menu slot="dropdown" class="dropdown-notification">
                        <el-dropdown-item command="mark_all_as_read">
                            Notifications
                            <span class="font-weight-bold ml-4 pull-right">Mark all as read</span>
                        </el-dropdown-item>
                        <el-dropdown-item divided v-if="notifications.length == 0"><center>No new notifications.</center></el-dropdown-item>
                        <el-dropdown-item divided :command="JSON.parse(item.data).action_route" v-for="(item, i) in notifications" :key="i">
                            <div class="clearfix">
                                <div class="float-left" style="width: 90%;">
                                    <i :class="JSON.parse(item.data).icon_class" style="font-size: 12px;"></i>
                                    <span class="font-weight-bold" style="font-size: 12px;">
                                        {{ JSON.parse(item.data).title }}<br/>
                                    </span>
                                    <div class="truncate" style="width: 95%;">
                                        {{ JSON.parse(item.data).description }}
                                    </div>
                                </div>
                                <img v-if="JSON.parse(item.data).picture" :src="JSON.parse(item.data).picture" width="45" height="45" style="position: absolute; top: 20px; right: 20px; border-radius: 5px;" onerror="this.onerror=null;this.src='this.onerror=null;this.src='/assets/img/book_cover.png';">
                                <img v-else src="/assets/img/book_cover.png" width="45" height="45" style="position: absolute; top: 20px; right: 20px; border-radius: 5px;">
                            </div>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </el-menu-item>
        </el-menu>
        <div v-if="!display_only">
            <br><br><br>
        </div>
        <div :class="{'px-5': !display_only && isWindowOnDesktop, 'pt-3': true, 'pb-3': true, 'px-3': display_only || !isWindowOnDesktop, 'mx-5': isWindowOnDesktop && !display_only}" v-if="image">
            <h2 class="text-muted font-weight-bolder">{{ title }}</h2>
            <img :src="image" alt="" style="width: 100%; max-height: 500px;">
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            image: String,
            title: String,
            display_only: Boolean,
        },
        data() {
            return {
                notifications: [],
                isWindowOnDesktop: true,
                screenWidth: 0,
                isCollapsed: true,
                display_name: this.$root.auth.info.name,
                folder_name: this.$root.folder_name,
                img: this.$root.folder_name + '/public/assets/img/header_logo.png',
            }
        },
        watch: {
            screenWidth(width) {
                if (width > 768) {
                    this.isWindowOnDesktop = true
                    this.display_name = this.$root.auth.info.name;
                } else {
                    this.isWindowOnDesktop = false
                    this.display_name = this.$root.auth.info.fname;
                }
            },
        },
        mounted() {
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize)
            })
            this.onResize()
            this.getNotifications()
        },
        beforeDestroy() {
            window.removeEventListener('resize', this.onResize)
        },
        methods: {
            getNotifications() {
                axios({
                    method: 'GET',
                    type: 'JSON',
                    url: this.folder_name + '/notifications/get'
                }).then(response => {
                    this.notifications = response.data
                }).catch(error => {
                    this.$root.defaultError()
                })
            },
            onResize(event) {
                this.screenWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0)
            },
            redirect(path) {
                if (path){
                    window.location.href = this.folder_name + path;
                }
            },
            navigate(URL) {
                if (URL == 'mark_all_as_read') this.notifMarkAsRead()
                else if (!URL) return
                else if (URL != 'mark_all_as_read') window.location = URL
            },
            notifMarkAsRead() {
                axios({
                    method: 'GET',
                    type: 'JSON',
                    url: this.folder_name + '/notifications/mark_as_read'
                }).then(response => {
                    this.notifications = []
                }).catch(error => {
                    this.$root.defaultError()
                })
            },
        }
    }
</script>
<style lang="scss" scoped>
    .el-menu--horizontal > .el-menu-item.dock-right {
        float: right;
    }
    .el-menu--horizontal > .el-submenu.dock-right {
        float: right;
    }
    .isNew {
        background-color: #e6f4ff;
    }
    .truncate {
        line-height: 25px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .dropdown-notification {
        width: 450px;
        position: relative;
        max-height: 500px;
        overflow-y: auto;
    }
</style>
