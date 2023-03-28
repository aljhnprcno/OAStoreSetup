<template>
    <div :class="css">
        <template>
       
        </template>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                books: {
                    datas: [],
                    total: 0,
                    page: 1,
                    per_page: 10,
                },
                sortings: [
                    {
                        name: 'Alphabetical',
                        col: 'title',
                    },
                    {
                        name: 'Most Popular',
                        col: 'borrow_count',
                    },
                    {
                        name: 'Most Viewed',
                        col: 'view_count',
                    },
                ],
                template: [],
                search: '',
                loading: false,
                activeTab: 'list',
                category: '',
                sorting: 'title',
                isWindowOnDesktop: true,
                screenWidth: 0,
                css: '',
                folder_name: this.$root.folder_name,
            }
        },
        watch: {
            screenWidth(width) {
                if (width > 768) {
                    this.isWindowOnDesktop = true
                    this.css = 'px-5';
                } else {
                    this.isWindowOnDesktop = false
                    this.css = 'px-3';
                }
            },
        },
        mounted() {
            console.log(this.$root.auth.info);
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize)
            })
            this.onResize()
            this.getDatas();
            this.getCategories();
        },
        beforeDestroy() {
            window.removeEventListener('resize', this.onResize)
        },
        methods: {
            onResize(event) {
                this.screenWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0)
            },
            getUserType() {
                let info = this.$root.auth.info.user_type;
                let type = '';
                if (info === "App\\Services\\Users\\User") {
                    type = 'admin';
                } else if (info === "App\\Services\\Employees\\Employee") {
                    type = 'employee';
                } else if (info = "App\\Services\\Students\\Student") {
                    type = 'student';
                }
                return type;
            },
        }
    }
</script>
<style scoped>
    .title {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .centered {
        position: absolute;
        top: 70%;
        left: 47%;
        transform: translate(-50%, -50%);
    }
    ::-webkit-file-upload-button {
        color: #FFF;
        background-color: #409EFF;
        border-color: #409EFF;
        padding: 0px 25px;
        font-size: 12px;
        border-radius: 3px;
        border: 1px solid #DCDFE6;
    }
    .upload_btn {
        color: transparent;
    }
</style>
