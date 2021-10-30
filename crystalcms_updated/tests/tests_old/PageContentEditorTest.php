<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Menu;
use App\Models\Pages;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;

class PageContentEditorTest extends TestCase
{
	use DatabaseTransactions;

	public $TEST_PAGE_CONTENT_ID;

    public function testIfAdminPanelLinkCorrect()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/admin_panel')
         ->click('Page Content Editor')
         ->seePageIs('/admin/page_content_editor');
    }

    public function testIfPageContentEditorThere()
    {
        $this->setupGuestPermission();

        $this->visit('/admin/page_content_editor')
            ->see('Page Content Editor');
    }

    public function testIfShowIsThere()
    {
        $this->setupGuestPermission();

        $this->createPage();

        $this->visit('/admin/page_content_editor/show/' . $this->TEST_PAGE_CONTENT_ID)
            ->see('Page Content Editor');
    }

    public function testIfAddIsThere()
    {
        $this->setupGuestPermission();

        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
            ->see('Page Content Editor');
    }

    public function testAddText()
    {
        $this->setupGuestPermission();

        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press(trans('admin.add') . ' ' . trans('contentcontroller.text'))
            ->see(trans('admin.field_added_successfully'));
    }

    public function testUp()
    {
        $this->setupGuestPermission();

        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press(trans('admin.add') . ' ' . trans('contentcontroller.text'))
            ->see(trans('admin.field_added_successfully'));

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press(trans('admin.add') . ' ' . trans('contentcontroller.text'))
            ->see(trans('admin.field_added_successfully'));

        $this->visit('/admin/page_content_editor/show/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Up')
        	->see('Page Content Editor');
    }

    public function testDown()
    {
        $this->setupGuestPermission();

        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press(trans('admin.add') . ' ' . trans('contentcontroller.text'))
            ->see(trans('admin.field_added_successfully'));

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press(trans('admin.add') . ' ' . trans('contentcontroller.text'))
            ->see(trans('admin.field_added_successfully'));

        $this->visit('/admin/page_content_editor/show/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Down')
        	->see('Page Content Editor');
    }

    public function testDelete()
    {
        $this->setupGuestPermission();

        $this->createPage();
        
        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press(trans('admin.add') . ' ' . trans('contentcontroller.text'))
            ->see(trans('admin.field_added_successfully'));

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press(trans('admin.add') . ' ' . trans('contentcontroller.text'))
            ->see(trans('admin.field_added_successfully'));

        $this->visit('/admin/page_content_editor/show/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Delete')
        	->see('Page Content Editor');

        $this->visit('/admin/page_content_editor/show/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Delete')
        	->dontSee(trans('admin.type') . ':');
    }

    protected function createPage()
    {
        $page = new Pages();
        $page->name = "test";
        $page->url = "test";
        $page->save();

        $this->TEST_PAGE_CONTENT_ID = $page->id;
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
