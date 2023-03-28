<template>
  <el-container style="height: 100%;">
    <Sidebar></Sidebar>
    <el-main class="wrapper__body">
      <Navbar title="Store"></Navbar>
      <el-container class="main-section">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
              <div class="card">
                <div class="card-header">
                  <h1>Add New Product</h1>
                </div>
                <div class="card-body">
                  <el-form ref="product" :model="product">
                    <el-form-item label="Product Name">
                      <el-input v-model="product.productname" name="productname"></el-input>
                    </el-form-item>
                    <el-form-item label="Grade Level">
                      <el-input v-model="product.gradelevel" name="gradelevel"></el-input>
                    </el-form-item>
                    <el-form-item label="Package">
                      <el-input v-model="product.package" name="package"></el-input>
                    </el-form-item>
                    <el-form-item label="Category">
                      <el-input v-model="product.category" name="category"></el-input>
                    </el-form-item>
                    <div class="">
                      <el-button type="success" @click="createProduct">SUBMIT</el-button>
                      <a href="store" class="btn btn-danger ml-2">BACK</a>
                    </div>
                  </el-form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </el-container>
    </el-main>
  </el-container>
</template>

<script>
import Navbar from './../../../components/admin/Navbar'
import Sidebar from './../../../components/admin/Sidebar'
export default {
  components: { Navbar, Sidebar },
  data() {
    return {
      folder_name: this.$root.folder_name,
      product: {
        productID:1,
        productname: '',
        gradelevel: '',
        package: '',
        category: '',
      }
    }
  },
  mounted() {
  },
  methods: {
    createProduct() {
      const form = this.$refs.product.$el;
      let formData = new FormData(form);
      formData.append('productID', this.product.productID);
      axios.post(this.folder_name + '/admin/add', formData)
        .then(res => {
          this.$swal(res.data.title, res.data.text, res.data.type);
        }).catch(error => {

        });
    }
  }
}
</script>



