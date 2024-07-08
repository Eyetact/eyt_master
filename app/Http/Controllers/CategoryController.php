<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use Illuminate\Http\Request;
use App\Http\Requests\CreatecategoryRequest;
use App\Http\Requests\UpdatecategoryRequest;
use App\Repositories\CategoryRepository;
use Flash;
use Illuminate\Support\Facades\Session;
use App\Repositories\FlashRepository;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Category;
use DataTables;
// use App\Helpers\S3Helper;

class CategoryController extends AppBaseController
{
    /** @var categoryRepository $categoryRepository*/
    private $categoryRepository;
    private $flashRepository;

    public function __construct(categoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
        $this->flashRepository = new FlashRepository();
    }

    /**
     * Display a listing of the category.
     *
     * @param categoryDataTable $categoryDataTable
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // dd("aasdasd");
            $data = Category::with('createdUser', 'updatedUser')->orderBy('created_at', 'desc')->get();
            return datatables()
                ->of($data)
                ->addColumn('action', 'categories.action')
                ->filter(function ($data) use ($request) {
                    // if (!empty($request->get('search'))) {
                    //     $data->where(function ($w) use ($request) {
                    //         $search = $request->get('search');
                    //         $w->orWhere('category_name', 'LIKE', "%$search%");
                    //     });
                    // }
                })
                ->make(true);
        }
        return view('categories.index');
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CreatecategoryRequest $request
     *
     * @return Response
     */
    public function store(CreatecategoryRequest $request)
    {
        $input = $request->all();
        // dd($input);
        $id = auth()->user()->id;
        $input['created_by'] = $id;

        // if ($request->hasFile('category_image')) {
        //     $path = 'category/'; // Optional: the path within the bucket where the image will be stored.
        //     $visibility = 'public-read'; // Optional: set to 'private' if you want the image to be private.

        //     // Get the uploaded file
        //     $image = $request->file('category_image');
        //     // Create an instance of the S3Helper
        //     $s3Helper = new S3Helper();

        //     // Upload the image to S3
        //     $imageUrl = $s3Helper->uploadImageToS3($image, $path, $visibility);
        //     if ($imageUrl) {
        //         $photo = $imageUrl;
        //         unset($input['category_image']);
        //         $input['category_image'] = $photo;
        //     } else {
        //         // Failed to upload the image. Handle the error if necessary.
        //         return response()->json(['message' => 'Image upload failed.'], 500);
        //     }
        // }

        $category = $this->categoryRepository->create($input);
        // dd($category);
        // Flash::success(__('models/categories.messages.create_success', ['model' => __('models/categories.singular')]));

        // $this->flashRepository->setFlashSession('alert-success', __('models/categories.messages.create_success', ['model' => __('models/categories.singular')]));

        return redirect(route('categories.index'));
    }

    /**
     * Display the specified category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->users()->find($id);
        // dd($category->toArray());
        if (empty($category)) {
            // Flash::error(__('models/categories.messages.not_found', ['model' => __('models/categories.singular')]));

            return redirect(route('categories.index'));
        }

        return view('categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            // Flash::error(__('models/categories.messages.not_found', ['model' => __('models/categories.singular')]));

            return redirect(route('categories.index'));
        }

        return view('categories.edit')->with('category', $category);
    }

    /**
     * Update the specified category in storage.
     *
     * @param int $id
     * @param UpdatecategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatecategoryRequest $request)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            // Flash::error(__('models/categories.messages.not_found', ['model' => __('models/categories.singular')]));

            return redirect(route('categories.index'));
        }

        $requests = $request->all();
        // if ($request->hasFile('category_image')) {
        //     $path = 'category/'; // Optional: the path within the bucket where the image will be stored.
        //     $visibility = 'public-read'; // Optional: set to 'private' if you want the image to be private.
        //     // Get the uploaded file
        //     $image = $request->file('category_image');
        //     // Create an instance of the S3Helper
        //     $s3Helper = new S3Helper();
        //     // Upload the image to S3
        //     $imageUrl = $s3Helper->uploadImageToS3($image, $path, $visibility);
        //     if ($imageUrl) {
        //         $photo = $imageUrl;
        //         unset($requests['category_image']);
        //         $requests['category_image'] = $photo;
        //     } else {
        //         // Failed to upload the image. Handle the error if necessary.
        //         return response()->json(['message' => 'Image upload failed.'], 500);
        //     }
        //     $oldImage = $request['old_image'];
        //     $s3Helper->removeImageToS3($oldImage);
        // }

        $userId = auth()->user()->id;
        $requests['updated_by'] = $userId;
        $category = $this->categoryRepository->update($requests, $id);

        // Flash::success(__('models/categories.messages.update_success', ['model' => __('models/categories.singular')]));

        return redirect(route('categories.index'));
    }

    /**
     * Remove the specified category from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            $response = ['status' => 201, 'message' => __('models/categories.messages.not_found', ['model' => __('models/categories.singular')])];
        }

        $this->categoryRepository->delete($id);
        return redirect(route('categories.index'));
    }
}
