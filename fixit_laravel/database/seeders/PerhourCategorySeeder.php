<?php

namespace Database\Seeders;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\HomePage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PerhourCategorySeeder extends Seeder
{
    /**
     * Phase 1 service categories for The Perhour.
     */
    public function run(): void
    {
        $adminId = User::role('admin')->value('id') ?? User::query()->value('id') ?? 1;
        $categoryIds = [];

        $titles = [
            'Handyman',
            'Carpenter',
            'Electrician',
            'Curtain fixer',
            'Plumber',
            'Cleaner',
            'Maid',
            'Shoe cleaner',
            'Wall Painter',
            'Car wash',
            'Ac technician',
            'Sofa shampoo',
            'Packing helper',
            'Furniture cleaner',
        ];

        foreach ($titles as $title) {
            $slug = Str::slug($title);

            $category = Category::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'description' => "Hourly {$title} services",
                    'parent_id' => null,
                    'category_type' => CategoryType::SERVICE,
                    'status' => 1,
                    'is_featured' => 1,
                    'created_by' => $adminId,
                ]
            );

            $category->setTranslation('title', 'en', $title);
            $category->setTranslation('description', 'en', "Hourly {$title} services");
            $category->save();

            $categoryIds[] = $category->id;
        }

        $this->syncHomePageCategories($categoryIds);
    }

    private function syncHomePageCategories(array $categoryIds): void
    {
        $homePage = HomePage::where('slug', 'default')->first();

        if (! $homePage) {
            return;
        }

        $content = $homePage->content;

        foreach ($content as $locale => $sections) {
            if (isset($sections['categories_icon_list'])) {
                $content[$locale]['categories_icon_list']['category_ids'] = $categoryIds;
                $content[$locale]['categories_icon_list']['status'] = true;
                $content[$locale]['categories_icon_list']['title'] = 'Browse Categories';
            }
        }

        $homePage->update(['content' => $content]);
    }
}
