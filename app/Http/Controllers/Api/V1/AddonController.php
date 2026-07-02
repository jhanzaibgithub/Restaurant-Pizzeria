<?php

namespace App\Http\Controllers\Api\V1;

use App\Model\AddOn;
use App\Model\Translation;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Resources\AddonResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;

class AddonController extends Controller
{
    public function __construct(
        private AddOn       $addon,
        private Translation $translation
    ) {}

    public function index()
    {
        $addons = $this->addon->latest()->get();

        // Use the AddonResource to transform the addons
        return response()->json([
            'data' => AddonResource::collection($addons),
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {

        $request->validate([
            'translations.*.name' => 'required|unique:add_ons',
            'price' => 'required|max:8',
            'tax' => 'required|max:100'
        ]);

        $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

        $addon = $this->addon;
        foreach ($data as $translation) {
            if ($translation['locale'] === 'en') {
                $addon->name = $translation['name'];
            }
        }

        // Handle translations safely
        if ($data && is_array($data)) {
            foreach ($data as $translation) {
                if (!isset($translation['name']) || !isset($translation['locale'])) {
                    return response()->json(['error', 'Translation data is missing or invalid!']);
                }
            }
        } else {
            return response()->json(['error', 'Translation data is missing or invalid!']);
        }
        $addon->price = $request->price;
        $addon->tax = $request->tax;
        $addon->save();

        $translationData = [];
        foreach ($data as $translation) {
            if (isset($translation['name']) && $translation['name']) {
                $translationData[] = [
                    'translationable_type' => 'App\Model\AddOn',
                    'translationable_id' => $addon->id, // ID is now available
                    'locale' => $translation['locale'],
                    'key' => 'name',
                    'value' => $translation['name'],
                ];
            }
        }

        // Insert translations into the database
        if (!empty($translationData)) {
            $this->translation->insert($translationData);
        }

        return response()->json(['message' => 'Addon added Successfully']);
    }
    /**
     * @param $id
     * @return Renderable
     */
    public function edit($id): Renderable
    {
        $addon = $this->addon->withoutGlobalScopes()->with('translations')->find($id);
        return view('admin-views.addon.edit', compact('addon'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'translations.*.name' => 'required|unique:add_ons,name,' . $request->id,
            'price' => 'required|max:8',
            'tax' => 'required|max:100'
        ]);

        $data = is_string($request->translations) ? json_decode($request->translations, true) : $request->translations;

        $addon = $this->addon->find($request->id);

        foreach ($data as $translation) {
            if ($translation['locale'] === 'en') {
                $addon->name = $translation['name'];
            }
        }
        $addon->price = $request->price;
        $addon->tax = $request->tax;
        $addon->save();

        Translation::where('translationable_id', $addon->id)->delete();

        $translationData = [];
        foreach ($data as $translation) {
            if (isset($translation['name']) && $translation['name']) {
                $translationData[] = [
                    'translationable_type' => 'App\Model\AddOn',
                    'translationable_id' => $addon->id, // ID is now available
                    'locale' => $translation['locale'],
                    'key' => 'name',
                    'value' => $translation['name'],
                ];
            }
        }

        // Insert translations into the database
        if (!empty($translationData)) {
            $this->translation->insert($translationData);
        }
        return response()->json(['message' => 'Addon updated Successfully']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $addon = $this->addon->find($request->id);
        $addon->delete();
        Toastr::success(translate('Addon removed!'));
        return back();
    }

    public function status(Request $request): RedirectResponse
    {
        $addon = $this->addon->find($request->id);
        $addon->status = $request->status;
        $addon->save();

        Toastr::success(translate('Addon status updated!'));
        return back();
    }
}
