<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;
use App\Models\Pages;

class ContentImageTest extends TestCase
{
	use DatabaseTransactions;

    public $page;
	public $TEST_PAGE_CONTENT_ID;

    public function testAddContentImage()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Image')
         ->see('Image added successfully!');
    }

    public function testIfEditLinkIsOnThePage()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Image')
         ->see('Image added successfully!');

        $this->visit($this->page->url)
         ->see('Edit');
    }

    public function testIfEditLinkOnThePageWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Image')
         ->see('Image added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->see('Image Editor');
    }

    public function testIfSavingAltWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Image')
         ->see('Image added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type('testing', 'alt')
         ->press('Save')
         ->see('testing');
    }

    public function testIfSavingImageWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Image')
         ->see('Image added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type('testing', 'alt')
         ->attach(public_path('img/gallery/add.jpg'), 'image')
         ->press('Save')
         ->see('testing');

        $this->visit('/admin/page_content_editor/show/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Delete')
         ->see('Delete Successful');
    }

    protected function createPage()
    {
        $page = new Pages();
        $page->name = "test";
        $page->url = "test";
        $page->save();

        $this->TEST_PAGE_CONTENT_ID = $page->id;
        $this->page = $page;
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
