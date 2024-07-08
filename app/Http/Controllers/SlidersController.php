<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use Illuminate\Http\Request;
use App\Http\Requests\CreateSliderRequest;
use App\Http\Requests\UpdateSliderRequest;
use App\Repositories\SlidersRepository;
use Flash;
use Illuminate\Support\Facades\Session;
use App\Repositories\FlashRepository;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Sliders;
use App\Models\StoreView;
use DataTables;
// use App\Helpers\S3Helper;

class SlidersController extends AppBaseController
{
    /** @var slidersRepository $slidersRepository*/
    private $slidersRepository;
    private $flashRepository;

    public function __construct(SlidersRepository $categoryRepo)
    {
        $this->slidersRepository = $categoryRepo;
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
            $data = Sliders::orderBy('created_at', 'desc')->get();
            return datatables()
                ->of($data)
                ->addColumn('action', 'sliders.action')
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
        return view('sliders.index');
    }

    /**
     * Show the form for creating a new category.
     *
     * @return Response
     */
    public function create()
    {
        $StoreView = StoreView::all()->pluck('slug','id');
        return view('sliders.create')->with('StoreView',$StoreView);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CreatePagesRequest $request
     *
     * @return Response
     */
    public function store(CreateSliderRequest $request)
    {
        $input = $request->all();
        // dd($input);
        $id = auth()->user()->id;
        $input['created_by'] = $id;


        $imagePath = 'images/slider/';

        if ($request->hasFile('web_image')) {
            $filename = time() . '.' . $request->file('web_image')->getClientOriginalExtension();
            $request->file('web_image')->move(public_path($imagePath), $filename);

            $input['web_image'] = $imagePath.$filename;
        }

        if ($request->hasFile('mobile_image')) {
            $filename = time() . '.' . $request->file('mobile_image')->getClientOriginalExtension();
            $request->file('mobile_image')->move(public_path($imagePath), $filename);

            $input['mobile_image'] = $imagePath.$filename;
        }

        // dd($input);
        $category = Sliders::create($input);
        // dd($category);
        // Flash::success(__('models/sliders.messages.create_success', ['model' => __('models/sliders.singular')]));

        // $this->flashRepository->setFlashSession('alert-success', __('models/sliders.messages.create_success', ['model' => __('models/sliders.singular')]));

        return redirect(route('sliders.index'));
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
        $category = Sliders::find($id);
        // dd($category->toArray());
        if (empty($category)) {
            // Flash::error(__('models/sliders.messages.not_found', ['model' => __('models/sliders.singular')]));

            return redirect(route('sliders.index'));
        }

        return view('sliders.show')->with('category', $category);
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
        $category = Sliders::find($id);

        if (empty($category)) {
            // Flash::error(__('models/sliders.messages.not_found', ['model' => __('models/sliders.singular')]));

            return redirect(route('sliders.index'));
        }

        return view('sliders.edit')->with('category', $category)->with('StoreView',$StoreView);
    }

    /**
     * Update the specified category in storage.
     *
     * @param int $id
     * @param UpdatePagesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSliderRequest $request)
    {
        $category = Sliders::find($id);

        if (empty($category)) {
            // Flash::error(__('models/sliders.messages.not_found', ['model' => __('models/sliders.singular')]));

            return redirect(route('sliders.index'));
        }

        $requests = $request->all();

        $imagePath = 'images/slider/';
        if ($request->hasFile('web_image')) {
            $filename = time() . '.' . $request->file('web_image')->getClientOriginalExtension();
            $request->file('web_image')->move(public_path($imagePath), $filename);

            $requests['web_image'] = $imagePath.$filename;
        }

        if ($request->hasFile('mobile_image')) {
            $filename = time() . '.' . $request->file('mobile_image')->getClientOriginalExtension();
            $request->file('mobile_image')->move(public_path($imagePath), $filename);

            $requests['mobile_image'] = $imagePath.$filename;
        }

        $userId = auth()->user()->id;
        $requests['updated_by'] = $userId;
        $category->update($requests);

        // Flash::success(__('models/sliders.messages.update_success', ['model' => __('models/sliders.singular')]));

        return redirect(route('sliders.index'));
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
        $category = Sliders::find($id);

        if (empty($category)) {
            $response = ['status' => 201, 'message' => __('models/sliders.messages.not_found', ['model' => __('models/sliders.singular')])];
        }

        Sliders::delete($id);
        return redirect(route('sliders.index'));
    }
}
