<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function __construct(
        private Category    $category,
        private Translation $translation
    )
    {
    }

    private function languageSettings(): array
    {
        $languages = Helpers::get_business_settings('language') ?? [];

        return is_array($languages) ? $languages : [];
    }


    /**
     * @param Request $request
     * @return Renderable
     */
    function index(Request $request): Renderable
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);

            $categories = $this->category->where('position', 0)->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $categories = $this->category->where('position', 0);
        }


        $categories = $categories->latest()->paginate(Helpers::getPagination())->appends($query_param);
        $languageSettings = $this->languageSettings();

        return view('admin-views.category.index', compact('categories', 'search', 'languageSettings'));
    }

    /**
     * @param Request $request
     * @return Renderable
     */
    function sub_index(Request $request)
    {
        $search = $request['search'];
        $query_param = ['search' => $search];


        $categories = $this->category->with(['parent'])
            ->when($request['search'], function ($query) use ($search) {
                $query->orWhere('name', 'like', "%{$search}%");
            })
            ->where(['position' => 1])
            ->latest()
            ->paginate(Helpers::getPagination());
        $languageSettings = $this->languageSettings();
        $parentCategories = $this->category->where(['position' => 0])->get();

        return view('admin-views.category.sub-index', compact('categories', 'search', 'languageSettings', 'parentCategories'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $key = explode(' ', $request['search']);
        $categories = $this->category
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->get();

        return response()->json([
            'view' => view('admin-views.category.partials._table', compact('categories'))->render()
        ]);
    }

    /**
     * @return Renderable
     */
    function sub_sub_index(): Renderable
    {
        $parentCategories = $this->category->where(['position' => 1])->get();
        $categories = $this->category->with(['parent'])->where(['position' => 2])->latest()->get();

        return view('admin-views.category.sub-sub-index', compact('parentCategories', 'categories'));
    }

    /**
     * @return Renderable
     */
    function sub_category_index(): Renderable
    {
        return view('admin-views.category.index');
    }

    /**
     * @return Renderable
     */
    function sub_sub_category_index(): Renderable
    {
        return view('admin-views.category.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);

        if ($request->has('type')) {
            $request->validate([
                'parent_id' => 'required',
            ], [
                'parent_id.required' => translate('Select a category first')
            ]);
        }

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        //uniqueness check
        $cat = $this->category->where('name', $request->name)->where('parent_id', $request->parent_id ?? 0)->first();
        if (isset($cat)) {
            Toastr::error(translate(($request->parent_id == null ? 'Category' : 'Sub-category') . ' already exists!'));
            return back();
        }

        //image upload
        if (!empty($request->file('image'))) {
            $image_name = Helpers::upload('category/', 'png', $request->file('image'));
        } else {
            $image_name = 'def.png';
        }
        if (!empty($request->file('banner_image'))) {
            $banner_image_name = Helpers::upload('category/banner/', 'png', $request->file('banner_image'));
        } else {
            $banner_image_name = 'def.png';
        }

        //into db
        $category = $this->category;
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->image = $image_name;
        $category->banner_image = $banner_image_name;
        $category->parent_id = $request->parent_id == null ? 0 : $request->parent_id;
        $category->position = $request->position;
        $category->save();

        //translation
        $data = [];
        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $data[] = array(
                    'translationable_type' => 'App\Model\Category',
                    'translationable_id' => $category->id,
                    'locale' => $key,
                    'key' => 'name',
                    'value' => $request->name[$index],
                );
            }
        }
        if (count($data)) {
            $this->translation->insert($data);
        }

        Toastr::success($request->parent_id == 0 ? translate('Category Added Successfully!') : translate('Sub Category Added Successfully!'));
        return back();
    }

    /**
     * @param $id
     * @return Renderable
     */
    public function edit($id): Renderable
    {
        $category = $this->category->withoutGlobalScopes()->with('translations')->find($id);
        $languageSettings = $this->languageSettings();

        return view('admin-views.category.edit', compact('category', 'languageSettings'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        $category->status = $request->status;
        $category->save();

        Toastr::success($category->parent_id == 0 ? translate('Category status updated!') : translate('Sub Category status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);

        foreach ($request->name as $name) {
            if (strlen($name) > 255) {
                toastr::error(translate('Name is too long!'));
                return back();
            }
        }

        $category = $this->category->find($id);
        $category->name = $request->name[array_search('en', $request->lang)];
        $category->image = $request->has('image') ? Helpers::update('category/', $category->image, 'png', $request->file('image')) : $category->image;
        $category->banner_image = $request->has('banner_image') ? Helpers::update('category/banner/', $category->banner_image, 'png', $request->file('banner_image')) : $category->banner_image;
        $category->save();

        foreach ($request->lang as $index => $key) {
            if ($request->name[$index] && $key != 'en') {
                $this->translation->updateOrInsert(
                    ['translationable_type' => 'App\Model\Category',
                        'translationable_id' => $category->id,
                        'locale' => $key,
                        'key' => 'name'],
                    ['value' => $request->name[$index]]
                );
            }
        }

        Toastr::success($category->parent_id == 0 ? translate('Category updated successfully!') : translate('Sub Category updated successfully!'));
        return back();
    }

    public function bulk_import_index(){
        return view('admin-views.category.bulk-import');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function bulk_import_data(Request $request): RedirectResponse
    {
     
        try {
            $collections = (new FastExcel)->import($request->file('category_file'));
        } catch (\Exception $exception) {
            Toastr::error(translate('You have uploaded a wrong format file, please upload the right file.'));
            return back();
        }

        //check
        $field_array = ['name', 'parent_id', 'position', 'status', 'priority'];
        if (count($collections) < 1) {
            Toastr::error(translate('At least one category have to import.'));
            return back();
        }
        foreach ($field_array as $field) {
            if (!array_key_exists($field, $collections->first())) {
                Toastr::error(translate($field) . translate(' must not be empty.'));
                return back();
            }
        }

        $data = [];
        foreach ($collections as $key => $collection) {
            if ($collection['name'] === "") {
                Toastr::error(translate('Please fill name field of row') . ' ' . ($key + 2));
                return back();
            }
            if ($collection['parent_id'] === "") {
                Toastr::error(translate('Please fill parent id field of row') . ' ' . ($key + 2));
                return back();
            }
            if ($collection['position'] === "") {
                Toastr::error(translate('Please fill position field of row') . ' ' . ($key + 2));
                return back();
            }
            if ($collection['status'] === "") {
                Toastr::error(translate('Please fill status field of row') . ' ' . ($key + 2));
                return back();
            }
            if ($collection['priority'] === "") {
                Toastr::error(translate('Please fill priority field of row') . ' ' . ($key + 2));
                return back();
            }

            if (!is_numeric($collection['parent_id'])) {
                Toastr::error(translate('parent id of row') . ' ' . ($key + 2) . ' ' . translate('must be number'));
                return back();
            }

            if (!is_numeric($collection['position'])) {
                Toastr::error(translate('position of row') . ' ' . ($key + 2) . ' ' . translate('must be number'));
                return back();
            }

            if (!is_numeric($collection['status'])) {
                Toastr::error(translate('status of row') . ' ' . ($key + 2) . ' ' . ' must be number');
                return back();
            }

            if (!is_numeric($collection['priority'])) {
                Toastr::error(translate('priority of row') . ' ' . ($key + 2) . ' ' . ' must be number');
                return back();
            }
        }
        ['name', 'parent_id', 'position', 'status', 'priority'];
        foreach ($collections as $collection) {
            $data[] = [
                'name' => $collection['name'],
                'parent_id' => $collection['parent_id'],
                'position' => $collection['position'],
                'status' => $collection['status'],
                'priority' => $collection['priority'],
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        $this->category->insert($data);

        Toastr::success(count($data) . ' - ' . translate('Category imported successfully!'));
        return back();
    }

    public function bulk_export_index(){
        return view('admin-views.category.bulk-export');
    }

        /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string|RedirectResponse
     * @throws \Box\Spout\Common\Exception\IOException
     * @throws \Box\Spout\Common\Exception\InvalidArgumentException
     * @throws \Box\Spout\Common\Exception\UnsupportedTypeException
     * @throws \Box\Spout\Writer\Exception\WriterNotOpenedException
     */
    public function bulk_export_data(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|string|RedirectResponse
    {

        if ($request->type == 'date_wise') {
            $request->validate([
                'start_date' => 'required',
                'end_date' => 'required'
            ]);
        }
        $start_date = Carbon::parse($request->start_date)->startOfDay();
        $end_date = Carbon::parse($request->end_date)->endOfDay();

        $categories = $this->category->when($request['type'] == 'date_wise', function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        })->get();

        $storage = [];

        if ($categories->count() < 1) {
            Toastr::info(translate('no_category_found'));
            return back();
        }

        // dd($categories);
        foreach ($categories as $item) {
        
            if (!isset($item->name)) {
                $item->name = 'Demo Product';
            }

            if (!isset($item->description)) {
                $item->description = 'No description available';
            }

            $storage[] = [
                'name' => $item->name,
                'parent_id' => $item->parent_id,
                'position' => $item->position,
                'status' => $item->status,
                'priority' => $item->priority,
            ];
        }
        return (new FastExcel($storage))->download('categories.xlsx');
    }

    public function update_priority(Request $request, $id){
        $category = $this->category->find($id);
        $category->priority = $request->priority;
        $category->save();

        Toastr::success(translate('Category priority updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $category = $this->category->find($request->id);
        Helpers::delete('category/' . $category['image']);

        if ($category->childes->count() == 0) {
            $category->delete();
            Toastr::success($category->parent_id == 0 ? translate('Category removed!') : translate('Sub Category removed!'));
        } else {
            Toastr::warning($category->parent_id == 0 ? translate('Remove subcategories first!') : translate('Sub Remove subcategories first!'));
        }

        return back();
    }
}
