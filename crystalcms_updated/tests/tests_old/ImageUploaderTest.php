<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;
use App\Models\Pages;

class ImageUploaderTest extends TestCase
{
	use DatabaseTransactions;

    public $page;
	public $TEST_PAGE_CONTENT_ID;


    public function testSeeIfUploaderLinkThere()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->see('Image Uploader');
    }

    public function testSeeIfUploaderLinkWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->click('Image Uploader')
         ->see('Image Uploader');
    }

    public function testSeeIfUploadingAndDeletingWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->click('Image Uploader')
         ->attach(public_path('img/gallery/add.jpg'), 'image')
         ->type('500', 'width')
         ->press('Upload')
         ->see('Success!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->click('Image Uploader')
         ->press('Delete')
         ->see('Success!');
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
