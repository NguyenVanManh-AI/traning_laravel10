<?php

namespace App\Services;

use App\Http\Requests\Category\RequestCreateCategory;
use App\Http\Requests\Category\RequestUpdateCategory;
use App\Models\Category;
use App\Repositories\ArticleRepository;
use App\Repositories\CategoryInterface;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Traits\APIResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class CategoryService
{
    use APIResponse;
    protected CategoryInterface $categoryRepository;

    public function __construct(
        CategoryInterface $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function saveImage(Request $request)
    {
        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '_category_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/image/thumbnail/categories/', $filename);

            return 'storage/image/thumbnail/categories/' . $filename;
        }
    }

    public function add(RequestCreateCategory $request)
    {
        // có hàm create trong repository nhưng không sử dụng , code tại đây luôn cho dễ hiểu 
        DB::beginTransaction();
        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'search_number' => $request->search_number,
                'tag' => $request->tag, // mảng đã được JSON.stringtify ở FE nên ở đây không cần en_code 
                'thumbnail' => $this->saveImage($request),
            ];
            $category = Category::create($data); 
            $category->tag = json_decode($category->tag);

            DB::commit();
            return $this->responseSuccessWithData($category, 'Thêm danh mục thành công !', 201);
        } catch (Throwable $e) {
            DB::rollback();
            return $this->responseError($e->getMessage());
        }
    }

    public function edit(RequestUpdateCategory $request, $id)
    {
        DB::beginTransaction();
        try {
            $category = CategoryRepository::getCategory(['id' => $id])->first();
            if ($request->hasFile('thumbnail')) {
                if ($category->thumbnail) {
                    File::delete($category->thumbnail);
                }
                $thumbnail = $this->saveImage($request);
                $data = array_merge($request->all(), ['thumbnail' => $thumbnail]);
                $category = CategoryRepository::updateCategory($category->id, $data);
            } else {
                $request['thumbnail'] = $category->thumbnail;
                $category = CategoryRepository::updateCategory($category->id, $request->all());
            }
            $category->tag = json_decode($category->tag);

            DB::commit();
            return $this->responseSuccessWithData($category, 'Cập nhật thông tin danh mục thành công !');
        } catch (Throwable $e) {
            DB::rollback();
            return $this->responseError($e->getMessage());
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $category = CategoryRepository::getCategory(['id' => $id])->first();
            if ($category) {

                // CHO NHỮNG BÀI VIẾT THUỘC CATEGORY NÀY VỀ NULL (REFERENCE)
                // $article = ArticleRepository::getArticle(['id_category' => $id]);
                // ArticleRepository::updateArticle($article, ['id_category' => null]);

                if ($category->thumbnail) {
                    File::delete($category->thumbnail);
                }
                $category->delete();

                DB::commit();
                return $this->responseSuccess('Xóa danh mục thành công !');
            } else {
                DB::commit();
                return $this->responseError(400, 'Không tìm thấy danh mục !');
            }
        } catch (Throwable $e) {
            DB::rollback();
            return $this->responseError($e->getMessage());
        }
    }

    public function deleteMany(Request $request)
    {
        DB::beginTransaction();
        try {
            $list_id = $request->list_id;
            // dd($list_id); // vẫn là một mảng , nếu k lưu vào database thì k cần encode
            $categories = CategoryRepository::getCategory(['list_id' => $list_id])->get();
            if (!$categories->isEmpty()) { // hay
                // $articles = ArticleRepository::getArticle(['list_id' => $list_id]);
                // ArticleRepository::updateArticle($articles, ['id_category' => null]);
                foreach ($categories as $category) {
                    if ($category->thumbnail) {
                        File::delete($category->thumbnail);
                    }
                    $category->delete(); 
                }
                DB::commit();
                return $this->responseSuccess('Xóa các danh mục thành công!');
            } else {
                return $this->responseError('Không tìm thấy danh mục nào để xóa.', 404);
            }
        } catch (Throwable $e) {
            DB::rollback();
            return $this->responseError($e->getMessage());
        }
    }

    public function all(Request $request)
    {
        DB::beginTransaction();
        try {
            $orderBy = $request->typesort ?? 'id';
            switch ($orderBy) {
                case 'title':
                    $orderBy = 'title';
                    break;

                case 'new':
                    $orderBy = 'id';
                    break;

                default:
                    $orderBy = 'id';
                    break;
            }

            $orderDirection = $request->sortlatest ?? 'true';
            switch ($orderDirection) {
                case 'true':
                    $orderDirection = 'DESC';
                    break;

                default:
                    $orderDirection = 'ASC';
                    break;
            }

            $filter = (object) [
                'orderBy' => $orderBy,
                'orderDirection' => $orderDirection,
                'search' => $request->search ?? '',
            ];

            if (!(empty($request->paginate))) {// lấy cho category
                $categorys = CategoryRepository::searchCategory($filter)->paginate($request->paginate);
            } else {
                $categorys = CategoryRepository::getCategory($filter)->get();
            }

            foreach($categorys as $category) {
                $category->tag = json_decode($category->tag);
            }

            return $this->responseSuccessWithData($categorys, 'Xem tất cả danh mục thành công !');
        } catch (Throwable $e) {
            DB::rollback();
            return $this->responseError($e->getMessage());
        }
    }

    public function details(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $category = CategoryRepository::getCategory(['id' => $id])->first();
            if ($category) {
                // search number
                $search_number = $category->search_number + 1;
                $category->search_number = $search_number;
                $category->save();
                // search number
                $category->tag = json_decode($category->tag);

                DB::commit(); // nhớ commit data 
                return $this->responseSuccessWithData($category, 'Xem danh mục chi tiết thành công !');
            } else {
                return $this->responseError('Không tìm thấy danh mục !');
            }
        } catch (Throwable $e) {
            DB::rollback();
            return $this->responseError($e->getMessage());
        }
    }
}
