<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePagesRequest;
use App\Http\Requests\UpdatePagesRequest;
use App\Repositories\PagesRepository;
use Flash;
use Illuminate\Support\Facades\Session;
use App\Repositories\FlashRepository;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Pages;
use App\Models\StoreView;
use DataTables;
// use App\Helpers\S3Helper;

class PagesController extends AppBaseController
{
    /** @var pagesRepository $pagesRepository*/
    private $pagesRepository;
    private $flashRepository;

    public function __construct(PagesRepository $pagesRepo)
    {
        $this->pagesRepository = $pagesRepo;
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
            $data = Pages::orderBy('created_at', 'desc')->get();
            return datatables()
                ->of($data)
                ->addColumn('action', 'pages.action')
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
        return view('pages.index');
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create()
    {

        $StoreView = StoreView::all()->pluck('slug','id');
        return view('pages.create')->with('StoreView',$StoreView);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CreatePagesRequest $request
     *
     * @return Response
     */
    public function store(CreatePagesRequest $request)
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

        $category = Pages::create($input);
        // dd($category);
        // Flash::success(__('models/pages.messages.create_success', ['model' => __('models/pages.singular')]));

        // $this->flashRepository->setFlashSession('alert-success', __('models/pages.messages.create_success', ['model' => __('models/pages.singular')]));

        return redirect(route('pages.index'));
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
        $category = Pages::find($id);
        // dd($category->toArray());
        if (empty($category)) {
            // Flash::error(__('models/pages.messages.not_found', ['model' => __('models/pages.singular')]));

            return redirect(route('pages.index'));
        }

        return view('pages.show')->with('category', $category);
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
        $StoreView = StoreView::all()->pluck('slug','id');
        $category = Pages::find($id);

        if (empty($category)) {
            // Flash::error(__('models/pages.messages.not_found', ['model' => __('models/pages.singular')]));

            return redirect(route('pages.index'));
        }

        return view('pages.edit')->with('category', $category)->with('StoreView',$StoreView);
    }

    /**
     * Update the specified category in storage.
     *
     * @param int $id
     * @param UpdatePagesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePagesRequest $request)
    {
        $category = Pages::find($id);

        if (empty($category)) {
            // Flash::error(__('models/pages.messages.not_found', ['model' => __('models/pages.singular')]));

            return redirect(route('pages.index'));
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
        $category->update($requests);

        // Flash::success(__('models/pages.messages.update_success', ['model' => __('models/pages.singular')]));

        return redirect(route('pages.index'));
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
        $category = Pages::find($id);

        if (empty($category)) {
            $response = ['status' => 201, 'message' => __('models/pages.messages.not_found', ['model' => __('models/pages.singular')])];
        }

        Pages::delete($id);
        return redirect(route('pages.index'));
    }
}
