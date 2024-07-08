<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use Illuminate\Http\Request;
use App\Http\Requests\CreateStoreViewRequest;
use App\Http\Requests\UpdateStoreViewRequest;
use App\Repositories\StoreViewRepository;
use Flash;
use Illuminate\Support\Facades\Session;
use App\Repositories\FlashRepository;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Category;
use DataTables;
use App\Models\CountryRegion;
use App\Models\StoreView;
// use App\Helpers\S3Helper;

class StoreViewController extends AppBaseController
{
    /** @var storeViewRepository $storeViewRepository*/
    private $storeViewRepository;
    private $flashRepository;

    public function __construct(StoreViewRepository $storeViewRepo)
    {
        $this->storeViewRepository = $storeViewRepo;
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
        $countryRegion = CountryRegion::all()->mapWithKeys(function ($country) {
            return [$country->id => "{$country->name} / {$country->region}"];
        });

        if ($request->ajax()) {
            // dd("aasdasd");
            $data = StoreView::orderBy('created_at', 'desc')->get();
            return datatables()
                ->of($data)
                ->addColumn('action', 'store_view.action')
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
        return view('store_view.index')->with('countryRegion', $countryRegion);
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create()
    {
        $countryRegion = CountryRegion::all()->mapWithKeys(function ($country) {
            return [$country->id => "{$country->name} / {$country->region}"];
        });

        // dd($countryRegion);
        return view('store_view.create')->with('countryRegion', $countryRegion);;
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CreateStoreViewRequest $request
     *
     * @return Response
     */
    public function store(CreateStoreViewRequest $request)
    {
        $input = $request->all();
        // dd($input);
        $id = auth()->user()->id;
        $input['created_by'] = $id;

        if(isset($input['status'])){
            $input['status'] = "active";
        }else{
            $input['status'] = "inactive";
        }

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

        $category = StoreView::create($input);
        // dd($category);
        // Flash::success(__('models/store_view.messages.create_success', ['model' => __('models/store_view.singular')]));

        // $this->flashRepository->setFlashSession('alert-success', __('models/store_view.messages.create_success', ['model' => __('models/store_view.singular')]));

        return redirect(route('store_view.index'));
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
        $category = StoreView::find($id);
        $countryRegion = CountryRegion::all()->mapWithKeys(function ($country) {
            return [$country->id => "{$country->name} / {$country->region}"];
        });
        if (empty($category)) {
            // Flash::error(__('models/store_view.messages.not_found', ['model' => __('models/store_view.singular')]));

            return redirect(route('store_view.index'));
        }

        return view('store_view.show')->with('category', $category)->with('countryRegion', $countryRegion);
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
        $category = StoreView::find($id);

        $countryRegion = CountryRegion::all()->mapWithKeys(function ($country) {
            return [$country->id => "{$country->name} / {$country->region}"];
        });

        if (empty($category)) {
            // Flash::error(__('models/store_view.messages.not_found', ['model' => __('models/store_view.singular')]));

            return redirect(route('store_view.index'));
        }

        return view('store_view.edit')->with('category', $category)->with('countryRegion', $countryRegion);
    }

    /**
     * Update the specified category in storage.
     *
     * @param int $id
     * @param UpdateStoreViewRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStoreViewRequest $request)
    {
        $category = StoreView::find($id);

        if (empty($category)) {
            // Flash::error(__('models/store_view.messages.not_found', ['model' => __('models/store_view.singular')]));

            return redirect(route('store_view.index'));
        }

        $requests = $request->all();
        // dd($requests);
        if(isset($requests['status'])){
            $requests['status'] = "active";
        }else{
            $requests['status'] = "inactive";
        }
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
        // dd($requests);
        $category->update($requests);

        // Flash::success(__('models/store_view.messages.update_success', ['model' => __('models/store_view.singular')]));

        return redirect(route('store_view.index'));
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
        $category = StoreView::find($id);

        if (empty($category)) {
            $response = ['status' => 201, 'message' => __('models/store_view.messages.not_found', ['model' => __('models/store_view.singular')])];
        }

        StoreView::delete($id);
        return redirect(route('store_view.index'));
    }
}
