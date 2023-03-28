<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Branch;
use App\Libraries\SharedFunctions;
use App\Services\Employees\Employee;
use App\Services\Employees\EmployeeInfo;
use App\Services\Users\BranchUser;
use App\Services\Users\Permission;
use App\Services\Users\User;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Package;
use App\Category;
use App\Services\GradeLevel;
use Doctrine\DBAL\Schema\Sequence;
use Illuminate\Support\Facades\Storage;


class StoreSetupController extends Controller
{
  public function index()
  {
    $data['css'] = ['admin.main'];
    $data['js']  = [];
    return view('/store', $data);
  }

  public function store()
  {
    $data['css'] = ['admin.main'];
    $data['js']  = [];
    return view('/AddProduct', $data);
  }


  public function create(Request $request)
  {
    $disk = Storage::disk('gcs');
    if (isset($request->productID) && $request->productID !== '') {
      $attachment_name = "";
      $imageSaved = "";
      if (isset($request->attachment_path) && $request->attachment_path != null) {
        $attachment = $request->attachment_path[0]->getClientOriginalName();
        $attachment_name = $this->GenerateRandom();

        if ($attachment) {
          $attachment_name .= ('' . str_replace(' ', '', $attachment_name));
          $disk->put('store_product/' . $attachment_name, file_get_contents($request->attachment_path[0]));
        }
        $imageSaved = env('GOOGLE_CLOUD_STORAGE_API_URI') . '/' . env('BUCKET_NAME') . '/store_product/' . $attachment_name;
      } else {
        $query = Product::where('id', $request->productID)->first();
        $imageSaved = $query->image;
      }
      $query = Product::where('id', $request->productID)->update([
        'productname' => $request->productname,
        'package' => $request->package,
        'category' => $request->category,
        'image' => $imageSaved,
      ]);
      if ($query) {
        $rs = ['title' => 'Success', 'text' => 'Product Updated', 'type' => 'success'];
      }
    } else {
      $attachment_name = "";
      if (isset($request->attachment_path) && $request->attachment_path != null) {
        $attachment = $request->attachment_path[0]->getClientOriginalName();
        $attachment_name = $this->GenerateRandom();

        if ($attachment) {
          $attachment_name .= ('' . str_replace(' ', '', $attachment_name));
          $disk->put('store_product/' . $attachment_name, file_get_contents($request->attachment_path[0]));
        }
      }
      $query = Product::create([
        'productname' => $request->productname,
        'package' => $request->package,
        'category' => $request->category,
        'image' => env('GOOGLE_CLOUD_STORAGE_API_URI') . '/' . env('BUCKET_NAME') . '/store_product/' . $attachment_name,
      ]);
      if ($query) {
        $rs = ['title' => 'Success', 'text' => 'Product Inserted', 'type' => 'success'];
      }
    }
    return response()->json($rs);
  }

  public function getProduct(Request $request)
  {
    $data = Product::get();

    return response()->json($data);
  }

  public function deleteProduct(Request $request)
  {
    $query = Product::where('id', $request->id)->delete();

    if ($query) {
      $rs = ['title' => 'Success', 'text' => 'Product Deleted', 'type' => 'success'];
    }

    return response()->json($rs);
  }

  public function GenerateRandom()
  {
    $today = date('YmdHi');
    $startDate = date('YmdHi', strtotime('-9 days'));
    $range = $today - $startDate;
    $rand1 = rand(0, $range);
    $rand2 = rand(0, 600000);
    return  $value = ($rand1 + $rand2);
  }

  public function get_branches()
  {
    $data = Branch::get("", false, false, false);
    return response()->json($data);
  }

  public function getGradeLevels(Request $request)
  { 
    $branch = Branch::get($request->branch_code, false, false, true);
    $data = GradeLevel::on($branch[0])->where('is_active', 0)->orderBy('sequence')->get();
    return response()->json($data);
  }

  public function getPackage(Request $request)
  {
    $data = Package::get();
    return response()->json($data);
  }

  public function getCategory(Request $request)
  {
    $data = Category::get();
    return response()->json($data);
  }

  public function createPackage(Request $request)
  {
    $query = Package::create([
      'package_name' => $request->package_name,

    ]);
    if ($query) {
      $rs = ['title' => 'Success', 'text' => 'Package Inserted', 'type' => 'success'];
    }
    return response()->json($rs);
  }

  public function createCategory(Request $request)
  {
    $query = Category::create([
      'category_name' => $request->category_name,
      'has_sizes' => $request->has_sizes,
    ]);
    if ($query) {
      $rs = ['title' => 'Success', 'text' => 'Category Inserted', 'type' => 'success'];
    }
    return response()->json($rs);
  }
}
