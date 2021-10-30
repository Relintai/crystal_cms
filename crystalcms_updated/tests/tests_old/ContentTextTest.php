<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\ContentDataText;

use App\Models\GlobalSettings;
use App\Models\RBACGroups;
use App\Models\RBACPermissions;
use App\Models\RBACRanks;
use App\Models\Pages;

class ContentTextTest extends TestCase
{
	use DatabaseTransactions;

    public $page;
	public $TEST_PAGE_CONTENT_ID;

    public function testAddContentText()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');
    }

    public function testIfEditLinkIsOnThePage()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->see('Edit');
    }

    public function testIfEditLinkOnThePageWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->see('Text Editor');
    }

    public function testIfSavingTextWorks()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type('testing', 'nomod_text')
         ->press('Save')
         ->see('testing');
    }

    public function testBasicBBcode()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type('[b]testing[/b]', 'nomod_text')
         ->press('Save')
         ->see('testing');

         $this->assertTrue($this->modelMatch('<strong>testing</strong>'));
    }

    public function testnewlineToBR()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type("\n", 'nomod_text')
         ->press('Save');

         $this->assertTrue($this->modelMatch('<br>'));
    }

    public function testInnerLinkBBcode()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type('[l=home]Home[/l]', 'nomod_text')
         ->press('Save');

         $this->assertTrue($this->modelMatch('<a href="' . url('') . '/home">Home</a>'));
    }

    public function testInnerLinkImageBBcode()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type('[il]test.jpg[/il]', 'nomod_text')
         ->press('Save');

         $this->assertTrue($this->modelMatch('<img src="' . asset('/img/uploaded/test.jpg') . '">'));
    }

    public function testInnerLinkImageWidthBBcode()
    {
        $this->setupGuestPermission();
        $this->createPage();

        $this->visit('/admin/page_content_editor/add/' . $this->TEST_PAGE_CONTENT_ID)
         ->press('Add Text')
         ->see('Text added successfully!');

        $this->visit($this->page->url)
         ->click('Edit')
         ->type('[il=20]test.jpg[/il]', 'nomod_text')
         ->press('Save');

         $this->assertTrue($this->modelMatch('<img style="width: 20%;" src="' . asset('img/uploaded/test.jpg') . '">'));
    }

    protected function modelMatch($string)
    {
        $model = ContentDataText::orderBy('id', 'desc')->first();

        if ($model->text === $string)
        {
            return true;
        }

        return false;
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
