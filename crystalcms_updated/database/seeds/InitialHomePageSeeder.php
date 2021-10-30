<?php

use Illuminate\Database\Seeder;

use App\Models\Pages;
use App\Models\Menu;
use App\Models\PageUrls;

class InitialHomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = new Pages();

        $page->name = "home";
        $page->url = "home";

        $page->save();

        $menu = new Menu();

        $menu->name_key = "home";
        $menu->url = "home";
        $menu->sort_order = 0;

        $menu->save();

        $pageurl = new PageUrls();

        $pageurl->route = "home";
        $pageurl->content_id = 1;
        $pageurl->content_data = "";
        $pageurl->save();
    }
}

