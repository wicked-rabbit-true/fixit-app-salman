<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Zone;
use App\Models\Currency;
use Illuminate\Database\Seeder;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;

class PerhourZoneSeeder extends Seeder
{
    public function run(): void
    {
        $currency = Currency::where('code', 'AED')->first();

        $this->createDubaiZone($currency?->id);
        $this->createSharjahZone($currency?->id);
    }

    private function createDubaiZone($currencyId): void
    {
        if (Zone::where('name', 'Dubai')->exists()) {
            return;
        }

        $polygon = new Polygon([
            new LineString([
                new Point(25.3500, 55.0000),
                new Point(25.3500, 55.5000),
                new Point(25.0000, 55.5000),
                new Point(25.0000, 55.0000),
                new Point(25.3500, 55.0000),
            ]),
        ]);

        $zone = Zone::create([
            'name' => 'Dubai',
            'place_points' => $polygon,
            'locations' => [
                ['lat' => 25.2048, 'lng' => 55.2708],
                ['lat' => 25.1972, 'lng' => 55.2744],
                ['lat' => 25.0657, 'lng' => 55.1713],
                ['lat' => 25.1189, 'lng' => 55.2009],
            ],
            'status' => 1,
            'currency_id' => $currencyId,
        ]);

        $categoryIds = Category::where('category_type', 'service')
            ->whereNull('parent_id')
            ->pluck('id');

        if ($categoryIds->isNotEmpty()) {
            $zone->categories()->syncWithoutDetaching($categoryIds);
        }
    }

    private function createSharjahZone($currencyId): void
    {
        if (Zone::where('name', 'Sharjah')->exists()) {
            return;
        }

        $polygon = new Polygon([
            new LineString([
                new Point(25.4000, 55.3000),
                new Point(25.4000, 55.5000),
                new Point(25.2800, 55.5000),
                new Point(25.2800, 55.3000),
                new Point(25.4000, 55.3000),
            ]),
        ]);

        $zone = Zone::create([
            'name' => 'Sharjah',
            'place_points' => $polygon,
            'locations' => [
                ['lat' => 25.3573, 'lng' => 55.3884],
                ['lat' => 25.3463, 'lng' => 55.4209],
                ['lat' => 25.2867, 'lng' => 55.3807],
            ],
            'status' => 1,
            'currency_id' => $currencyId,
        ]);

        $maid = Category::where('slug', 'maid')->first();
        $cleaner = Category::where('slug', 'cleaner')->first();
        $categoryIds = collect();

        if ($maid) {
            $categoryIds->push($maid->id);
        }
        if ($cleaner) {
            $categoryIds->push($cleaner->id);
        }

        if ($categoryIds->isNotEmpty()) {
            $zone->categories()->sync($categoryIds->toArray());
        }
    }
}
