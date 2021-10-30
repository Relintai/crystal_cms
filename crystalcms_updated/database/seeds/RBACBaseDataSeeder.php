<?php

use Illuminate\Database\Seeder;

use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;
use App\Models\GlobalSettings;

class RBACBaseDataSeeder extends Seeder
{
	public function create()
	{
		$rank = new RBACRanks();
		$rank->name = "guest";
		$rank->name_internal = "guest";
		$rank->save();

		$gs = GlobalSettings::where('key', 'guest_rbac_rank_id')->first();
		$gs->value = $rank->id;
		$gs->save();

		$perm = new RBACPermissions();
		$perm->permissions = "view";
		$perm->save();

		$group = new RBACGroups();
		$group->rank_id = $rank->id;
		$group->permission_id = $perm->id;
		$group->name = "root";
		$group->url = "/";
		$group->revoke = false;
		$group->sort_order = 0;
		$group->save();

		$perm = new RBACPermissions();
		$perm->permissions = "none";
		$perm->save();

		$group = new RBACGroups();
		$group->rank_id = $rank->id;
		$group->permission_id = $perm->id;
		$group->name = "No admin rights";
		$group->url = "admin";
		$group->revoke = false;
		$group->sort_order = 0;
		$group->save();

		$perm = new RBACPermissions();
		$perm->permissions = "none";
		$perm->save();

		$group = new RBACGroups();
		$group->rank_id = $rank->id;
		$group->permission_id = $perm->id;
		$group->name = "No editing rights";
		$group->url = "editor";
		$group->revoke = false;
		$group->sort_order = 0;
		$group->save();

		//create the user rank
		$rank = new RBACRanks();
		$rank->name = "user";
		$rank->name_internal = "user";
		$rank->save();

		$gs = GlobalSettings::where('key', 'register_default_user_rank')->first();
		$gs->value = $rank->id;
		$gs->save();

		$perm = new RBACPermissions();
		$perm->permissions = "view";
		$perm->save();

		$group = new RBACGroups();
		$group->rank_id = $rank->id;
		$group->permission_id = $perm->id;
		$group->name = "root";
		$group->url = "/";
		$group->revoke = false;
		$group->sort_order = 0;
		$group->save();

		$perm = new RBACPermissions();
		$perm->permissions = "none";
		$perm->save();

		$group = new RBACGroups();
		$group->rank_id = $rank->id;
		$group->permission_id = $perm->id;
		$group->name = "No admin rights";
		$group->url = "admin";
		$group->revoke = false;
		$group->sort_order = 0;
		$group->save();

		$perm = new RBACPermissions();
		$perm->permissions = "none";
		$perm->save();

		$group = new RBACGroups();
		$group->rank_id = $rank->id;
		$group->permission_id = $perm->id;
		$group->name = "No editing rights";
		$group->url = "editor";
		$group->revoke = false;
		$group->sort_order = 0;
		$group->save();

		//create the admin rank
		$rank = new RBACRanks();
		$rank->name = "admin";
		$rank->name_internal = "admin";
		$rank->settings = "showadminpanellink";
		$rank->save();

		$perm = new RBACPermissions();
		$perm->permissions = "all";
		$perm->save();

		$group = new RBACGroups();
		$group->rank_id = $rank->id;
		$group->permission_id = $perm->id;
		$group->name = "root";
		$group->url = "/";
		$group->revoke = false;
		$group->sort_order = 0;
		$group->save();
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create();
    }
}
