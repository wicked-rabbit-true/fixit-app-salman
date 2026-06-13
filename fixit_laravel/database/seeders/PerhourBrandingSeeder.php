<?php

namespace Database\Seeders;

use App\Models\HomePage;
use App\Models\ThemeOption;
use Illuminate\Database\Seeder;

class PerhourBrandingSeeder extends Seeder
{
    public function run(): void
    {
        $appName = config('app.name', 'The Perhour');

        $theme = ThemeOption::query()->first();

        if ($theme) {
            $options = $theme->options;
            $options['general']['site_title'] = $appName;
            $options['general']['site_tagline'] = 'Book trusted home services by the hour';
            $options['general']['breadcrumb_description'] = 'Browse hourly home services across our categories and book professionals in minutes.';
            $options['footer']['footer_copyright'] = '©' . date('Y') . ' ' . $appName . ' All rights reserved';
            $theme->update(['options' => $options]);
        }

        $homePage = HomePage::where('slug', 'default')->first();

        if ($homePage) {
            $content = $homePage->content;

            foreach ($content as $locale => $sections) {
                $content[$locale]['home_banner']['title'] = 'Book Home Services by the Hour with ';
                $content[$locale]['home_banner']['animate_text'] = $appName;
                $content[$locale]['home_banner']['description'] = 'From handymen to cleaners — hire trusted professionals at transparent hourly rates. Easy booking, clear pricing, stress-free service.';
            }

            $homePage->update(['content' => $content]);
        }
    }
}
