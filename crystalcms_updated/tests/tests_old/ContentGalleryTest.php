<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\ContentDataGallery;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;
use App\Models\Pages;

class ContentGalleryTest extends TestCase
{
	use DatabaseTransactions;

	public $page;
	public $TEST_PAGE_CONTENT_ID;

	public function testAddContentGallery()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Gallery')
         ->see('Gallery added successfully!');
    }

    public function testIfEditLinkIsOnThePage()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Gallery')
         ->see('Gallery added successfully!');

        $this->visit($this->page->url)
         ->see('Edit');
    }

    public function testIfEditImagesLinkIsOnThePage()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Gallery')
         ->see('Gallery added successfully!');

        $this->visit($this->page->url)
         ->see('Edit Images');
    }

    public function testIfEditLinkOnThePageWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Gallery')
         ->see('Gallery added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->see('Gallery Editor');
    }

    public function testIfEditImagesLinkOnThePageWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Gallery')
         ->see('Gallery added successfully!');

        $this->visit($this->page->url)
         ->click('Edit Images')
         ->see('Gallery Editor');
    }

	public function testIfGalleryEditWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Add Gallery')
        	->see('Gallery added successfully!');

        $this->visit($this->page->url)
        	->click('Edit')
        	->type('name', 'name')
        	->type('folder', 'folder')
        	->type('description', 'description')
        	->press('Save');

        $data = ContentDataGallery::orderBy('id', 'desc')->first();

        if ($data->name != 'name')
        {
        	$this->assertTrue(false);
        }

        if ($data->folder != 'folder')
        {
        	$this->assertTrue(false);
        }

        if ($data->description != 'description')
        {
        	$this->assertTrue(false);
        }

        $this->assertTrue(true);
    }

	public function testIfAddingPictureWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Add Gallery')
        	->see('Gallery added successfully!');

        $this->visit($this->page->url)
        	->click('Edit')
        	->type('test', 'name')
        	->type('test', 'folder')
        	->type('test', 'description')
        	->press('Save');

        $this->visit($this->page->url)
        	->click('add_image_link')
        	->type('description', 'description')
        	->attach(public_path('img/gallery/add.jpg'), 'image')
        	->press('Save')
        	->seePageIs('test');

        $this->cleanupFolders();
    }

	public function testUpWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Add Gallery')
        	->see('Gallery added successfully!');

        $this->visit($this->page->url)
        	->click('Edit')
        	->type('test', 'name')
        	->type('test', 'folder')
        	->type('test', 'description')
        	->press('Save');

        $this->visit($this->page->url)
        	->click('add_image_link')
        	->type('description', 'description')
        	->attach(public_path('img/gallery/add.jpg'), 'image')
        	->press('Save')
        	->seePageIs('test');

        $this->visit($this->page->url)
        	->click('add_image_link')
        	->type('description', 'description')
        	->attach(public_path('img/gallery/add.jpg'), 'image')
        	->press('Save')
        	->seePageIs('test');

        $this->visit($this->page->url)
        	->click('Edit Images')
        	->Press('<')
        	->see('Gallery Editor');

        $this->cleanupFolders();
    }

	public function testDownWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Add Gallery')
        	->see('Gallery added successfully!');

        $this->visit($this->page->url)
        	->click('Edit')
        	->type('test', 'name')
        	->type('test', 'folder')
        	->type('test', 'description')
        	->press('Save');

        $this->visit($this->page->url)
        	->click('add_image_link')
        	->type('description', 'description')
        	->attach(public_path('img/gallery/add.jpg'), 'image')
        	->press('Save')
        	->seePageIs('test');

        $this->visit($this->page->url)
        	->click('add_image_link')
        	->type('description', 'description')
        	->attach(public_path('img/gallery/add.jpg'), 'image')
        	->press('Save')
        	->seePageIs('test');

        $this->visit($this->page->url)
        	->click('Edit Images')
        	->Press('>')
        	->see('Gallery Editor');

        $this->cleanupFolders();
    }

	public function testDeleteWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
        	->press('Add Gallery')
        	->see('Gallery added successfully!');

        $this->visit($this->page->url)
        	->click('Edit')
        	->type('test', 'name')
        	->type('test', 'folder')
        	->type('test', 'description')
        	->press('Save');

        $this->visit($this->page->url)
        	->click('add_image_link')
        	->type('description', 'description')
        	->attach(public_path('img/gallery/add.jpg'), 'image')
        	->press('Save')
        	->seePageIs('test');

        $this->visit($this->page->url)
        	->click('Edit Images')
        	->Press('x')
        	->see('Success!');

        $this->cleanupFolders();
    }

    protected function cleanupFolders()
    {
    	$filesystem = new \Illuminate\Filesystem\Filesystem();

    	$filesystem->deleteDirectory(public_path('img/gallery/test'));
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
