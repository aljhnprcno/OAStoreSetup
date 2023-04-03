<template>
  <el-container style="height: 100%;">
    <Sidebar></Sidebar>
    <el-main class="wrapper__body">
      <Navbar title="Store Setup"></Navbar>
      <el-container class="main-section">
        <el-tabs v-model="activeName" type="card" class="demo-tabs" @tab-click="handleClick">
          <el-tab-pane label="Store Packages" name="first">
            <a class="btn btn-success float-right" @click="packageVisible = true">ADD PACKAGE</a>
            <el-table :data="packageList">
              <el-table-column align="center" fixed prop="package_name" resizable :min-width="300" label="Package Name">
                <template slot="header">
                  <span style="color: black;">PACKAGE NAME</span>
                </template>
              </el-table-column>
              <el-table-column align="center" fixed prop="branch_code" resizable :min-width="300" label="Branch">
                <template slot="header">
                  <span style="color: black;">BRANCH</span>
                </template>
              </el-table-column>
              <el-table-column align="center" fixed prop="grade_name" resizable :min-width="300" label="Grade Level">
                <template slot="header">
                  <span style="color: black;">GRADE LEVEL</span>
                </template>
              </el-table-column>
              <el-table-column align="center" resizable fixed="right" :min-width="300" label="ACTION">
                <template slot="header">
                  <span style="color: black;">ACTION</span>
                </template>
                <template slot-scope="scope">
                  <el-button type="success" @click="editPackage(scope.row)" size="small"><i
                      class="fa fa-edit"></i></el-button>
                  <el-button type="danger" @click="deletePackage(scope.row.id)" size="small"><i
                      class="fa fa-trash"></i></el-button>
                </template>
              </el-table-column>
            </el-table>

            <el-dialog :visible.sync="packageVisible">
              <el-form ref="package" :model="package">
                <el-form-item label="Package Name">
                  <el-input name="package_name" v-model="package.package_name"></el-input>
                </el-form-item>
                <el-form-item label="Branch">
                  <el-select name="branch_code" v-model="filter_branch" filterable placeholder="Select Branch...">
                    <el-option v-for="branch in branches" :key="branch.id" :label="branch.text" :value="branch.id" />
                  </el-select>
                </el-form-item>
                <el-form-item label="Grade Level">
                  <el-select v-model="filter_grade_level" value-key="grade_level_id" filterable
                    placeholder="Select Grade Level...">
                    <el-option v-for="gradelevel in gradelevels" :key="gradelevel.grade_level_id"
                      :label="gradelevel.grade_name" :value="gradelevel.grade_level_id" />
                  </el-select>
                  <!-- <el-input v-model="product.gradelevel" name="gradelevel"></el-input> -->
                </el-form-item>
                <div class="">
                  <el-button type="success" @click="createPackage">SUBMIT</el-button>
                  <a href="store" class="btn btn-danger ml-2" @click="cancelPackage">CANCEL</a>
                </div>
              </el-form>
            </el-dialog>

          </el-tab-pane>
          <el-tab-pane label="Product Category" name="second">
            <a class="btn btn-success float-right" @click="categoryVisible = true">ADD CATEGORY</a>
            <el-table :data="categoryList">
              <el-table-column align="center" fixed prop="category_name" resizable :min-width="300" label="Category Name">
                <template slot="header">
                  <span style="color: black;">CATEGORY NAME</span>
                </template>
              </el-table-column>
              <el-table-column align="center" fixed prop="has_sizes" resizable :min-width="300" label="Sizes">
                <template slot="header">
                  <span style="color: black;">SIZES</span>
                </template>
              </el-table-column>
              <el-table-column align="center" resizable fixed="right" :min-width="300" label="ACTION">
                <template slot="header">
                  <span style="color: black;">ACTION</span>
                </template>
                <template slot-scope="scope">
                  <el-button type="success" @click="editCategory(scope.row)" size="small"><i
                      class="fa fa-edit"></i></el-button>
                  <el-button type="danger" @click="deleteCategory(scope.row.id)" size="small"><i
                      class="fa fa-trash"></i></el-button>
                </template>
              </el-table-column>
            </el-table>

            <el-dialog :visible.sync="categoryVisible">
              <el-form ref="category" :model="category">
                <el-form-item label="Category Name">
                  <el-input v-model="category.category_name" name="category_name"></el-input>
                </el-form-item>
                <el-form-item label="Sizes">
                  <el-input type="number" v-model="category.has_sizes" name="has_sizes"></el-input>
                </el-form-item>
                <!-- <el-form-item label="Grade Level">
                  <el-select v-model="filter_gradelevel" filterable placeholder="Select Grade Level...">
                    <el-option v-for="gradelevel in gradelevels" :key="gradelevel.grade_level_id"
                      :label="gradelevel.grade_name" :value="gradelevel.grade_level_id" />
                  </el-select>
                </el-form-item> -->
                <div class="">
                  <el-button type="success" @click="createCategory">SUBMIT</el-button>
                  <a href="store" class="btn btn-danger ml-2" @click="cancelCategory">CANCEL</a>
                </div>
              </el-form>
            </el-dialog>

          </el-tab-pane>
          <el-tab-pane label="Product Inventory" name="third">
            <div class="container">
              <div class="card-index">
                <h1 class="mb-2"><b>Inventories</b></h1>
                <h3 class="mb-3">Select Grade Level:</h3>
                <el-select class="mb-4" name="" id="">
                  <el-option selected placeholder="Select Grade Level">Select Grade Level</el-option>
                  <el-option value="">1</el-option>
                  <el-option value="">2</el-option>
                  <el-option value="">3</el-option>
                </el-select>
                <h5>Total # of Sold Items: </h5>
                <h5 class="mb-4">Total # of Return Items: </h5>
                <h5><b>Entries: </b></h5>
                <a class="btn btn-success float-right" @click="productVisible = true">ADD PRODUCT</a>
                <el-select>
                  <el-option selected>Select Grade Level</el-option>
                  <el-option value="">10</el-option>
                  <el-option value="">25</el-option>
                  <el-option value="">50</el-option>
                  <el-option value="">100</el-option>
                </el-select>
                <div class="card-body">
                  <el-table :data="productList">
                    <el-table-column align="center" fixed prop="productname" resizable :min-width="300"
                      label="Product Name">
                      <template slot="header">
                        <span style="color: black;">PRODUCT NAME</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" prop="category" resizable :min-width="300" label="Product Category">
                      <template slot="header">
                        <span style="color: black;">PRODUCT CATEGORY</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" prop="package" resizable :min-width="300" label="Product Package">
                      <template slot="header">
                        <span style="color: black;">PRODUCT PACKAGE</span>
                      </template>
                    </el-table-column>
                    <!-- <el-table-column align="center" prop="branch_code" resizable :min-width="300" label="Branch">
                      <template slot="header">
                        <span style="color: black;">BRANCH</span>
                      </template>
                    </el-table-column> -->
                    <el-table-column align="center" prop="name" resizable :min-width="300" label="Price">
                      <template slot="header">
                        <span style="color: black;">PRICE</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" prop="name" resizable :min-width="300" label="Beginning Inventory">
                      <template slot="header">
                        <span style="color: black;">BEGINNING INVENTORY</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" prop="name" resizable :min-width="300" label="Sold Items">
                      <template slot="header">
                        <span style="color: black;">SOLD ITEMS</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" prop="name" resizable :min-width="300" label="Stock on Hand">
                      <template slot="header">
                        <span style="color: black;">STOCK ON HAND</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" prop="name" resizable :min-width="300" label="Remarks">
                      <template slot="header">
                        <span style="color: black;">REMARKS</span>
                      </template>
                    </el-table-column>
                    <el-table-column align="center" resizable fixed="right" :min-width="300" label="ACTION">
                      <template slot="header">
                        <span style="color: black;">ACTION</span>
                      </template>
                      <template slot-scope="scope">
                        <el-button type="success" @click="editProduct(scope.row)" size="small"><i
                            class="fa fa-edit"></i></el-button>
                        <el-button type="danger" @click="deleteProduct(scope.row.id)" size="small"><i
                            class="fa fa-trash"></i></el-button>
                      </template>
                    </el-table-column>
                  </el-table>
                </div>
              </div>
          </div>

            <el-dialog :visible.sync="productVisible">
              <el-form ref="product" :model="product">
                <el-form-item label="Product Name">
                  <el-input v-model="product.productname" name="productname"></el-input>
                </el-form-item>
                <el-form-item label="Product Package">
                  <el-select name="package" v-model="filter_packageList_one" value-key=""
                    filterableplaceholder="Select Package...">
                    <el-option v-for="packageList_one in packageList" :key="packageList_one.id"
                      :label="packageList_one.package_name" :value="packageList_one.id" />
                  </el-select>
                </el-form-item>
                <el-form-item label="Product Category">
                  <el-select name="category" v-model="filter_categoryList_one" value-key="" filterable
                    placeholder="Select Category...">
                    <el-option v-for="categoryList_one in categoryList" :key="categoryList_one.id"
                      :label="categoryList_one.category_name" :value="categoryList_one.id" />
                  </el-select>
                </el-form-item>
                <el-form-item label="Upload Product" prop="attachment_image">
                  <el-upload ref="attachment_image" action="" list-type="picture-card" accept=".jpg, .png, .jpeg"
                    name="attachment_path[]" :on-remove="handleRemove" :on-change="handleChange"
                    :file-list="product.attachment_image" :auto-upload="false" :multiple="false" :limit="1">
                    <i slot="default" class="el-icon-plus"></i>
                  </el-upload>
                  <span v-if="product.productID !== ''">
                    <br>
                    <img w-full :src="img_src" alt="Preview Image" style="width: 150px;" />
                  </span>
                </el-form-item>
                <div class="">
                  <el-button type="success" @click="createProduct">SUBMIT</el-button>
                  <a href="store" class="btn btn-danger ml-2" @click="cancelProduct">CANCEL</a>
                </div>
              </el-form>
            </el-dialog>

          </el-tab-pane>
          <!-- <el-tab-pane label="Task" name="fourth">Task</el-tab-pane> -->
        </el-tabs>
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
        productID: '',
        productname: '',
        filter_packageList_one: [],
        filter_categoryList_one: [],
        attatchment_image: [],
      },
      package: {
        packageID: '',
        filter_branch: '',
        filter_gradelevel: [],
      },
      category: {
        categoryID: '',
        category_name: '',
        has_sizes: '',
      },
      productList: [],
      packageList: [],
      categoryList: [],
      productVisible: false,
      packageVisible: false,
      categoryVisible: false,
      img_src: '',
      branch: '',
      branches: [],
      filter_branch: '',
      gradelevel: '',
      gradelevels: [],
      filter_grade_level: '',
      package_one: '',
      packages: [],
      category_one: '',
      categories: [],
      activeName: 'first',
      filter_productList_one: [],
      filter_packageList_one: [],
      filter_categoryList_one: [],
    }
  },
  mounted() {
    this.getProduct();
    this.getBranches();
    setTimeout(() => { this.getGradeLevels() }, 2000)
    this.getPackage();
    this.getCategory();
    // this.getGradeLevels();
  },
  methods: {
    handleRemove(file, fileList) {
      const index = this.product.attachment_image.indexOf(file);
      this.product.attachment_image.splice(index, 1);

      // if (this.fields.id !== '') {
      //     this.fields.delete_image = true;
      // }
    },
    handleChange(file, fileList) {
      this.product.attachment_image[0] = fileList[0].raw;
    },
    getGradeLevels() {

      let data = {
        'branch_code': this.filter_branch,
        'request_type': 1,
      }

      axios.post(this.folder_name + '/admin/gradelevels', data).then(response => {
        this.gradelevels = response.data;
        this.filter_gradelevel = response.data[0].id;

      });
    },
    getBranches() {
      axios.post(this.folder_name + '/branches').then(response => {
        this.branches = response.data;
        this.filter_branch = response.data[0].id;

        // console.log(this.filter_gradelevel);

        // response.data.forEach(branch => this.itemForm.stocks[branch.id] = 0);
      });
    },
    getProduct() {
      axios.post(this.folder_name + '/admin/getproduct')
        .then(res => {
          this.productList = res.data
          this.filter_productList_one = response.data[0].id;
        }).catch(error => {

        });
    },
    getPackage() {
      axios.post(this.folder_name + '/admin/getpackage')
        .then(res => {
          this.packageList = res.data
          this.filter_packageList_one = response.data[0].id;
        }).catch(error => {

        });
    },
    getCategory() {
      axios.post(this.folder_name + '/admin/getcategory')
        .then(res => {
          this.categoryList = res.data
          this.filter_categoryList_one = response.data[0].id;
        }).catch(error => {

        });
    },
    createProduct() {
      const form = this.$refs.product.$el;
      let formData = new FormData(form);
      formData.append('productID', this.product.productID);
      axios.post(this.folder_name + '/admin/add', formData)
        .then(res => {
          this.$swal(res.data.title, res.data.text, res.data.type);
          this.getProduct()
          this.cancelProduct()
        }).catch(error => {

        });
    },
    createPackage() {
      const form = this.$refs.package.$el;
      let formData = new FormData(form);
      formData.append('packageID', this.package.packageID);
      formData.append('grade_level_id', this.filter_grade_level);
      axios.post(this.folder_name + '/admin/addpackage', formData)
        .then(res => {
          this.$swal(res.data.title, res.data.text, res.data.type);
          this.getPackage()
          this.cancelPackage()
        }).catch(error => {

        });
    },
    createCategory() {
      const form = this.$refs.category.$el;
      let formData = new FormData(form);
      formData.append('categoryID', this.category.categoryID);
      axios.post(this.folder_name + '/admin/addcategory', formData)
        .then(res => {
          this.$swal(res.data.title, res.data.text, res.data.type);
          this.getCategory()
          this.cancelCategory()
        }).catch(error => {

        });
    },
    editProduct(data) {
      this.product = {
        productID: data.id,
        productname: data.productname,
        gradelevel: data.gradelevel,
        package: data.package,
        category: data.category,
      }
      this.img_src = data.image,
        this.productVisible = true
    },
    editPackage(data) {
      this.package = {
        packageID: data.id,
        package_name: data.package_name,
        grade_level_id: data.grade_level_id,
      }
      this.img_src = data.image,
        this.packageVisible = true
    },
    editCategory(data) {
      this.category = {
        categoryID: data.id,
        category_name: data.category_name,
        has_sizes: data.has_sizes,
      }
      this.img_src = data.image,
        this.categoryVisible = true
    },
    cancelProduct() {
      this.product = {
        productID: '',
        productname: '',
        gradelevel: '',
        package: '',
        category: '',
      }
      this.img_src = '',
        this.productVisible = false
    },
    cancelPackage() {
      this.package = {
        packageID: '',
        package_name: '',
      }
      this.img_src = '',
        this.productVisible = false
    },
    cancelCategory() {
      this.category = {
        categoryID: '',
        has_sizes: '',
      }
      this.img_src = '',
        this.productVisible = false
    },
    deleteProduct(id) {
      let data = { 'id': id };
      axios.post(this.folder_name + '/admin/delete', data)
        .then(res => {
          this.$swal(res.data.title, res.data.text, res.data.type);
          this.getProduct()
        }).catch(error => {

        });
    },
    deletePackage(id) {
      let data = { 'id': id };
      axios.post(this.folder_name + '/admin/deletepackage', data)
        .then(res => {
          this.$swal(res.data.title, res.data.text, res.data.type);
          this.getPackage()
        }).catch(error => {

        });
    },
    deleteCategory(id) {
      let data = { 'id': id };
      axios.post(this.folder_name + '/admin/deletecategory', data)
        .then(res => {
          this.$swal(res.data.title, res.data.text, res.data.type);
          this.getCategory()
        }).catch(error => {

        });
    }
  }
}
</script>



