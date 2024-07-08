<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\RequestAddCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryController extends Controller
{
    public function addCategory(RequestAddCategory $request) {

    // Khởi tạo và Truy xuất Dữ liệu

        // $categories = Category::all();
        // $category = Category::find(4);

        // try {
        //     $category = Category::findOrFail(99); 
        //     return response()->json([
        //         'data' => $category,
        //         'message' => 'Success !',
        //     ], 200);
        // } catch (Throwable $e) {
        //     return response()->json([
        //         'message' => 'Fail !',
        //     ], 200);
        // }

        // $category = Category::where('title', 'title 999')->first();

        // $category = Category::where('title', 'title 999')->get();

        // $category_titles = Category::where('title', 'title 999')->pluck('title');
        
        // lấy ra những cột cụ thể 
        // $data = Category::where('title', 'title 999')->select('number', 'title')->get();

        // $data = Category::where('title', 'title 999')->orWhere('number','>=', 199)->get();

        // $ids = [1,4,5];
        // $data = Category::whereIn('id', $ids)->get();
        // $data = Category::whereNotIn('id', $ids)->get();
        
        // $data = Category::whereNull('created_at')->get();

        // $data = Category::whereBetween('number', [50, 200])->get();


    // Tạo, Cập nhật và Xóa Dữ liệu

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'number' => $request->number,
        ];

        $new_category = Category::create($data);

        // for($i=1; $i<=100; $i++) { 
            // $new_category = Category::create($data);
        // }

        return response()->json([
            'data' => $new_category,
            'message' => 'Success !',
        ], 200);


        // $category = Category::find(7);
        // $data = [
        //     'number' => 119999,
        // ];
        // $category->update($data);

        // return response()->json([
        //     'data' => $category,
        //     'message' => 'Success !',
        // ], 200);

        // $category = Category::find(7);
        // $category->title = 'save_title';
        // $category->number = '10';
        // $category->save();

        // return response()->json([
        //     'data' => $category,
        //     'message' => 'Success !',
        // ], 200);

        // $category = Category::find(7);
        // $category->delete();
        // return response()->json([
        //     'message' => 'Success !',
        // ], 200);


        // Category::where('title', 'like', '%test%')->delete(); // C1

        // $categories = Category::where('title', 'like', '%test%')->get(); // C2
        // foreach($categories as $category => $index) {
        //     $category->delete();
        // }

        // return response()->json([
        //     'message' => 'Success !',
        // ], 200);


        // $data = Category::take(2)->get();
        // return response()->json([
        //     'data' => $data,
        //     'message' => 'Success !',
        // ], 200);

        // $data  = Category::limit(2)->get();
        // return response()->json([
        //     'data' => $data,
        //     'message' => 'Success !',
        // ], 200);

        // lấy ra n+1 => lấy ra bảng ghi thứ 3 
        // $data = Category::offset(2)->limit(10)->get(); 
        // return response()->json([
        //     'data' => $data,
        //     'message' => 'Success !',
        // ], 200);


        // $n = Category::count(); 
        // return response()->json([
        //     'data' => $n,
        //     'message' => 'Success !',
        // ], 200);


        // Lọc 
        // $ids = Category::select('title', 'number', DB::raw('MAX(id) as id'))
        // ->groupBy('title', 'number')
        // ->pluck('id');

        // $data = Category::whereIn('id', $ids)->get();

        // return response()->json([
        //     'data' => $data,
        //     'message' => 'Success !',
        // ], 200);

        // $data = DB::select("
        //     SELECT *
        //     FROM categories
        //     WHERE id IN (
        //         SELECT MAX(id)
        //         FROM categories
        //         GROUP BY title, number
        //     )
        // ");

        // return response()->json([
        //     'data' => $data,
        //     'message' => 'Success !',
        // ], 200);
    }

    public function getAll(Request $request) {
        // Phân trang 
        $result = Category::paginate($request->per_page);
        return response()->json([
            'result' => $result,
            'message' => 'Success !',
        ], 200);
    }

}
