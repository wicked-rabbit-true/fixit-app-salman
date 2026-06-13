<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\SeoSetting;

class CategoryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::where('category_type', 'service')
            ->with(['services', 'children.services'])
            ->whereNull('parent_id')
            ->where('status', true)
            ->whereNull('deleted_at');

        $zoneIds = session('zoneIds', []);
        $seoSetting = SeoSetting::where('page_slug', 'category-list')->where('is_active', true)->first();

        if (! empty($zoneIds)) {
            $categories = $categories->whereRelation('zones', function ($zones) use ($zoneIds) {
                $zones->whereIn('zone_id', $zoneIds);
            });
        }

        return view('frontend.category.index', [
            'categories' => $categories->orderBy('title')->get(),
            'seoSetting' => $seoSetting,
        ]);
    }
}
