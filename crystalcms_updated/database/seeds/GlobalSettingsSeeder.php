<?php

use Illuminate\Database\Seeder;

use App\Models\GlobalSettings;

class GlobalSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rid = new GlobalSettings();
        $rid->key = "guest_rbac_rank_id";
        $rid->value = "1";
        $rid->save();

        $rid = new GlobalSettings();
        $rid->key = "redirect_to_when_no_view_permission";
        $rid->value = "/";
        $rid->save();

        $rid = new GlobalSettings();
        $rid->key = "theme_site";
        $rid->value = "null";
        $rid->save();

        $rid = new GlobalSettings();
        $rid->key = "theme_admin";
        $rid->value = "null";
        $rid->save();

        $rid = new GlobalSettings();
        $rid->key = "register_default_user_rank";
        $rid->value = "1";
        $rid->save();

        $rid = new GlobalSettings();
        $rid->key = "registration_enabled";
        $rid->value = "1";
        $rid->save();
    }
}
