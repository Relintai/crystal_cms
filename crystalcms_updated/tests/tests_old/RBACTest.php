<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;

//as of 2015 dec 10 -> somehow the app will go over the 100 xdebug nest limit, and 
//when running the tests, this setting in the php.ini gets ignored
//also the error message is really bad, so its not easy to tell which function goes over the limit.
//Xdebug should be disabled on production.
//dd(ini_get('xdebug.max_nesting_level'));
ini_set('xdebug.max_nesting_level', 200);

class RBACTest extends TestCase
{
	use DatabaseTransactions;

    public function testIfAdminPanelLinkCorrect()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/admin_panel')
         ->click('RBAC Editor')
         ->seePageIs('/admin/rbac_editor');
    }

    public function testIfRankEditorThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor')
         ->click('New Rank')
         ->see('RBAC Editor');
    }

    public function testIfNewRankWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');
    }

    public function testIfEditRankWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
        	->click('[Edit Names]')
        	->type('pagetestasd', 'name')
            ->type('pagetestasd', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');
    }

    public function testIfGroupEditThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
        	->click('pagetest')
            ->see('RBAC Editor');
    }


    public function testIfnewGroupLinkWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
        	->click('pagetest')
        	->click('New Group')
            ->see('RBAC Editor');
    }

    public function testIfnewGroupWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('testname', 'name')
            ->type('testurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testname')
            ->see('testurl');
    }

    public function testIfEditGroupThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('testname', 'name')
            ->type('testurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testname');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('testurl,')
            ->see('RBAC Editor');
    }

    public function testIfEditGroupWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('testname', 'name')
            ->type('testurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testname')
            ->see('testurl');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('testname,')
            ->type('testna', 'name')
            ->type('testu', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testna')
            ->see('testu');
    }

    public function testIfGroupUpWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('testname', 'name')
            ->type('testurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testname')
            ->see('testurl');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('trname', 'name')
            ->type('trurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('trname')
            ->see('trurl');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->press('Up')
            ->dontSee(trans('errors.internal_error_try_again'))
            ->see('RBAC Editor');
    }

    public function testIfGroupDownWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('testname', 'name')
            ->type('testurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testname')
            ->see('testurl');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('trname', 'name')
            ->type('trurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('trname')
            ->see('trurl');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->press('Down')
            ->dontSee(trans('errors.internal_error_try_again'))
            ->see('RBAC Editor');
    }

    public function testIfGroupDeleteWorks()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('testname', 'name')
            ->type('testurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testname')
            ->see('testurl');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->press('Delete')
            ->dontSee(trans('errors.internal_error_try_again'))
            ->see('RBAC Editor');
    }

    public function testIfPermissionSaveWorks()
    {
        $this->setupGuestPermission();
        
        $this->visit('/admin/rbac_editor/rank_editor')
            ->type('pagetest', 'name')
            ->type('pagetest', 'name_internal')
            ->press('Save')
            ->see('RBAC Editor');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->click('New Group')
            ->type('testname', 'name')
            ->type('testurl', 'url')
            ->press('Save')
            ->see('Success!')
            ->see('testname')
            ->see('testurl');

        $this->visit('/admin/rbac_editor')
            ->click('pagetest')
            ->press('Save')
            ->dontSee(trans('errors.internal_error_try_again'))
            ->see('RBAC Editor');
    }

    protected function setupGuestPermission()
    {
        $rank = new RBACRanks();
        $rank->name = "test";
        $rank->name_internal = "test";
        $rank->settings = "showadminpanellink";
        $rank->save();

        $permissions = new RBACPermissions();
        $permissions->permissions = "all";
        $permissions->save();

        $group = new RBACGroups();
        $group->rank_id = $rank->id;
        $group->permission_id = $permissions->id;
        $group->name = "test";
        $group->url = "/";
        $group->revoke = false;
        $group->sort_order = 0;
        $group->save();

        $s = GlobalSettings::find(1);
        $s->value = $rank->id;
        $s->save();

    }
}
