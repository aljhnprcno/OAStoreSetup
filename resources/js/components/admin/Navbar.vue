<template>
    <el-menu class="el-top-nav" mode="horizontal">
        <el-menu-item class="title" index="0">{{ title }}</el-menu-item>
        <el-submenu class="user" index="1">
            <template slot="title">
                <img src="https://picsum.photos/200" class="user-avatar">
                {{ $root.auth.info.fname }}
            </template>
            <el-menu-item @click="$root.logout()"><i class="fa fa-sign-out"></i> Logout</el-menu-item>
        </el-submenu>
        <el-menu-item class="notif" index="2">
            <el-dropdown @command="navigate" placement="bottom-end" trigger="click">
                <span><i class="fa fa-bell"></i><i v-if="notifications.length > 0" class="fa fa-circle" style="color: #F77; font-size: 9px;"></i></span>
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
</template>

<style scoped>
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

<script>
export default {
    props: {
        title: String
    },
    data () {
        return {
            notifications: [],
        }
    },
    mounted() {
        this.getNotifications();
    },
    methods: {
        getNotifications() {
            axios({
                method: 'GET',
                type: 'JSON',
                url: this.$root.folder_name + '/notifications/get'
            }).then(response => {
                this.notifications = response.data
            }).catch(error => {
                this.$root.defaultError()
            })
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
                url: this.$root.folder_name + '/notifications/mark_as_read'
            }).then(response => {
                this.notifications = []
            }).catch(error => {
                this.$root.defaultError()
            })
        },
    }
}
</script>
