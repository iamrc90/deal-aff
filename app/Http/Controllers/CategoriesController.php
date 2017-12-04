<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CategoriesController extends Controller
{
    public function addCategoryForm() {
        $categories = Category::where('status',1)->select('name','id')->get();
    	return view('categories.create_category',[
    	    'categories' => $categories
        ]);
    }

    public function saveCategory(Request $request) {
        $aRequestParams = Input::all();
        $category = new Category();
        $category->name = $aRequestParams['name'];
        $category->save();
        return redirect()->route('addCategoryForm');
    }
}
