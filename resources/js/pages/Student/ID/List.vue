<template>
    <el-container style="height: 100%;" direction="vertical">
        <layout
            :image="setup.image_path"
            :title="setup.display_name"
            :display_only="false"
        />
         <el-row type="flex" justify="center" :gutter="0">
         <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6"></el-col>
            <el-col id="wrapper" :xs="24" :sm="24" :md="12" :lg="24" :xl="24" style="margin-top:6em;">
             <center>
                <el-button id="button" @click="printID" type="primary" style="text-align:left; width: 100%; border-radius 6px; font-size: 1.8em; height:3em; margin-top: 21px;">View ID  <i style="float:right;" class="circle-bg-icon fa fa-address-card-o" aria-hidden="true"></i></el-button><br>
                <el-button id="button1" @click="qrcode" type="warning" style="text-align:left; width: 100%; border-radius 6px; font-size: 1.8em; height:3em; margin-top: 21px;">View QR  <i style="float:right;" class="circle-bg-icon fa fa-qrcode" aria-hidden="true"></i></el-button><br>
                <el-button id="button2" @click="IdRequestMail" type="success" style="text-align:left; width: 100%; border-radius 6px; font-size: 1.7em; height:3em; margin-top: 21px;">Request Physical ID <i style="float:right;" class="circle-bg-icon fa fa-file-text" aria-hidden="true"></i></el-button>
             </center> 
            </el-col>
             <el-col :xs="6" :sm="6" :md="6" :lg="6" :xl="6"></el-col>
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
                 axios.post(this.folder_name + 'student_id/getqr',{std_id: this.$root.auth.info.id})
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
                    axios.post(this.folder_name + '/student_id/request/physical-id', { data: this.$root.auth.info, branch_code:  this.student.branch_account.branch_code  })
                        .then((res) => {
                            console.log(res.data);
                            if (res.data.code == 1) {
                                this.$swal("Request Success", '', "success");
                            } else if (res.data.code == 2) {
                                this.$swal("You have pending request", '', "warning");
                            }
                        });
                })
            },
             qrcode() {
              window.location.href = this.folder_name +'/dashboard/qrcode';
            },
             printID() {
                let grade_id;
                if (this.$root.auth.info.type == 'Employee') {
                    grade_id = "";
                } else {
                    grade_id = this.$root.auth.info.branch_account.grade_level_info.grade_level_id;
                }
                axios.post(this.folder_name + '/request/print', { branch_code: this.student.branch_account.branch_code, id: this.student.id, grade_level_id: grade_id,  user_type : this.student.type, request_type: 1  }, {responseType: 'blob'})
                    .then(response => {
                        const file = new Blob(
                        [response.data], 
                        {type: 'application/pdf'});
                    //Build a URL from the file
                        const fileURL = URL.createObjectURL(file);
                    //Open the URL on new Window
                        window.open(fileURL,'_blank');
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
    @media only screen and (max-width:736px) {
        #button {
            font-size: 1.4em !important;
        }
        #button1 {
            font-size: 1.4em!important;
        }
        #button2 {
            font-size: 1.3em !important;
        } 
    }
</style>