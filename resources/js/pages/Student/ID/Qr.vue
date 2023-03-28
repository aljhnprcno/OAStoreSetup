<template>
    <el-container style="height: 100%;" direction="vertical">
        <layout
            :image="setup.image_path"
            :title="setup.display_name"
            :display_only="false"
        />
        <el-row type="flex" justify="center" :gutter="0">
            <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="11">
            </el-col>
            <el-col id="wrapper" :xs="24" :sm="12" :md="12" :lg="18" :xl="11" style="margin-top: 4rem; margin-left: 3rem;">
                <p id="std_name" style="text-align:left; font-size: 1.6em; font-weight: bold;"><strong>{{  student.name }}</strong></p>
                <p id="std_id" style="font-size: 1.6em; font-weight: bold;"><strong>{{  student.id }}</strong></p><br>
                <qrcode id="qr" :value="student.id" :render-as="'svg'" level="H" size="300" :options="{ width: 450 }"></qrcode><br>
                <el-button id="dl" @click="downloadQr" type="primary" style="margin-left: 9rem; border-radius 6px; font-size: 1.2em; text-align:center;">Download QR</el-button>
            </el-col>
             <el-col :xs="24" :sm="12" :md="12" :lg="6" :xl="11">
            </el-col>
        </el-row>
        <general-template :class="{'mx-5': isWindowOnDesktop}" />
    </el-container>
</template>
<script>
    import Navbar from './../../../components/student/Navbar';
    import Layout from './../../../pages/General/Layout';
    import GeneralTemplate from './../../../pages/General/ID/GeneralTemplate';
    export default {
        components: { Navbar, GeneralTemplate, Layout },
        data() {
            return {
                isWindowOnDesktop: true,
                screenWidth: 0,
                setup: {
                    display_name: '',
                    image_path: '',
                },
                template: [],
                student: [],
                folder_name: this.$root.folder_name,
                address: '',
                qr: ''
            }
        },
        watch: {
            screenWidth(width) {
                if (width > 768) {
                    this.isWindowOnDesktop = true
                } else {
                    this.isWindowOnDesktop = false
                }
            },
        },
        beforeDestroy() {
            window.removeEventListener('resize', this.onResize)
        },
        mounted() {
            this.getSetup();
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize)
            })
            this.onResize()
            this.student = this.$root.auth.info;
            this.getAddress(this.$root.auth.info.branch_account.branch_code);
        },
        methods: {
            getSetup() {
                let grade_id;
                if (this.$root.auth.info.type == 'Employee') {
                    grade_id ="";
                } else {
                    grade_id = this.$root.auth.info.branch_account.grade_level_info.grade_level_id;
                }
                axios.post(this.folder_name + '/student_id/getTemplateByStudent',{branch_code: this.$root.auth.info.branch_account.branch_code, grade_level_id: grade_id})
                        .then(res => {
                            this.template = res.data;
                        });
                axios.post(this.folder_name + '/student_id/getqr',{std_id: this.$root.auth.info.id})
                        .then(res => {
                            this.qr = res.data;
                        });        
            },
            onResize(event) {
                this.screenWidth = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0)
            },
            getAddress(branch) {
                if (branch == 'lp') {
                    this.address = 'las pinas'
                } else if (branch == 'sa') {
                    this.address = 'sta ana'
                } else if (branch == 'an') {
                    this.address = 'angeles'
                } else if (branch == 'gh') {
                    this.address = '3 Eisenhover St.,Greenhills, San Juan City'
                }else if (branch == 'fv') {
                    this.address = 'Fairview'
                }
                return this.address;
            },
            IdRequestMail() {
                this.$confirm('Request Physical ID?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    axios.post(this.folder_name + '/student_id/request/physical-id', { data: this.$root.auth.info })
                        .then(res => {
                            this.$swal("Request Success", '', "success");
                        })
                        .catch(() => {
                            this.$swal("Error", "Something went wrong", "error");
                        });
                }).catch(() => {
                    //
                });
            },
               downloadQr() {
                axios.post(this.folder_name + '/request/download', { student_id: this.student.id })
                    .then(res => {
                        const file = new Blob(
                        [res.data], 
                        {type: 'image/svg'});
                        const fileURL = URL.createObjectURL(file);
                        var dl = document.createElement("a");
                        document.body.appendChild(dl); // This line makes it work in Firefox.
                        dl.setAttribute("href", fileURL);
                        dl.setAttribute("download", this.student.id + ".svg");
                        dl.click();
                        this.$swal("Downloaded!", '', "success");
                    })
                    .catch(() => {
                        this.$swal("Error", "Something went wrong", "error");
                    });
            },
        },
    }
</script>
<style scoped>
    .centered {
        position: absolute;
        top: 70%;
        left: 66%;
        transform: translate(-50%, -50%);
    }
    .header {
        position: absolute;
        top: 4%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        width: 75%;
    }
    .caption {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 4%;
        right: 0;        
        text-align: left;
        top: 11%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: white;
        width: 75%; /* Set the width of the positioned div */
        line-height: 2px;
    }
    .address {
        position: absolute;
        top: 18%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }
     .grade_level {
        position: absolute;
        top: 27%;
        left: 66%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }
     .sy {
        position: absolute;
        top: 54%;
        left: 27%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }
     .std_img {
        position: absolute;
        top: 48%;
        left: 66%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        color:black;
    }
    .name {
        position: absolute;
        top: 78%;
        left: 61%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
        width: 75%;
    }
    .branch {
        position: absolute;
        top: 88%;
        left: 78%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }
    .site {
        position: absolute;
        top: 96%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }
    .container {
        position: relative;
        text-align: center;
        color: white;
        font-family: Arial,sans-serif;
    }


    .header_back {
        position: absolute;
        top: 10%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }

    .address_back {
        position: absolute;
        top: 19%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }

    .mobile {
        position: absolute;
        top: 24%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial,sans-serif;
    }
    .text1 {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 30%;
        right: 0;        
        text-align: left;
        top: 65.6%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 54%; /* Set the width of the positioned div */
        line-height: 2px;
        height: 50px;
    }
    .text {
        position: absolute;
        z-index: 999;
        margin: 0 auto;
        left: 22%;
        right: 0;        
        text-align: left;
        top: 6.3%; /* Adjust this value to move the positioned div up and down */
        font-family: Arial,sans-serif;
        color: black;
        width: 100%; /* Set the width of the positioned div */
        line-height: 2px;
    }
    .text h2 {
         text-align: center;
         color:red;
    }
    #qr {
        margin-top: -1.9em;
    }
    
 @media only screen and (max-width:736px) {
        #wrapper {
            margin-left: 0 !important;
        }    
        #qr {
            width: 400px !important;
            margin-left: 0 !important;
        }
        #dl {
            margin-left: 5em !important;
        }
    }
     @media only screen and (max-width:375px) {
        #dl {
            margin-left: 7em !important;
        }
    }
     @media only screen and (max-width:425px) {
        #dl {
            margin-left: 7em !important;
        }
         #std_name {
            margin-left: 2em !important;
        }
          #std_id {
            margin-left: 2em !important;
        }
    }
</style>