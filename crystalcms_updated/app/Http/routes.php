<?php

use App\Constants\PageUrlContentIds;

use Illuminate\Http\Request;
use App\Http\Controllers\Page;
use App\Http\Controllers\Blog;
use App\Models\Pages;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('login', 'Login@index');
Route::post('login', 'Login@store');
Route::get('logout', 'Login@logout');

Route::get('register', 'Register@index');
Route::post('register', 'Register@store');

Route::get('admin/admin_panel', 'Admin@admin_panel');

Route::resource('admin/menu_editor', 'Admin\MenuEditor');
Route::post('admin/menu_editor/up', 'Admin\MenuEditor@up');
Route::post('admin/menu_editor/down', 'Admin\MenuEditor@down');
Route::post('admin/menu_editor/delete', 'Admin\MenuEditor@destroy');

Route::resource('admin/page_editor', 'Admin\PageEditor');
Route::post('admin/page_editor/delete', 'Admin\PageEditor@destroy');

Route::get('admin/page_content_editor', 'Admin\PageContentEditor@index');
Route::get('admin/page_content_editor/show/{page_id}', 'Admin\PageContentEditor@show');
Route::get('admin/page_content_editor/add/{page_id}', 'Admin\PageContentEditor@add');
Route::post('admin/page_content_editor/add', 'Admin\PageContentEditor@add_store');
Route::post('admin/page_content_editor/up', 'Admin\PageContentEditor@up');
Route::post('admin/page_content_editor/down', 'Admin\PageContentEditor@down');
Route::post('admin/page_content_editor/delete', 'Admin\PageContentEditor@delete');
Route::post('admin/page_content_editor/setlocale', 'Admin\PageContentEditor@setLocale');


Route::get('admin/blog_editor', 'Admin\BlogEditor@index');
Route::get('admin/blog_editor/create', 'Admin\BlogEditor@create');
Route::get('admin/blog_editor/edit/{id}', 'Admin\BlogEditor@show');
Route::post('admin/blog_editor/blog_edit_create', 'Admin\BlogEditor@store');
Route::post('admin/blog_editor/blog_page_settings_set', 'Admin\BlogEditor@editContentData');


Route::get('admin/rbac_editor', 'Admin\RBACEditor@index');
Route::get('admin/rbac_editor/rank_editor/{id?}', 'Admin\RBACEditor@rankEditor');
Route::post('admin/rbac_editor/rank_editor', 'Admin\RBACEditor@doRankEdit');
Route::get('admin/rbac_editor/show/{id}', 'Admin\RBACEditor@show');
Route::get('admin/rbac_editor/group_editor/{rank_id}/{group_id?}', 'Admin\RBACEditor@groupEditor');
Route::post('admin/rbac_editor/group_editor', 'Admin\RBACEditor@groupEditorPost');
Route::post('admin/rbac_editor/update_rank_settings', 'Admin\RBACEditor@saveRankSettings');
Route::post('admin/rbac_editor/up', 'Admin\RBACEditor@up');
Route::post('admin/rbac_editor/down', 'Admin\RBACEditor@down');
Route::post('admin/rbac_editor/delete', 'Admin\RBACEditor@delete');
Route::post('admin/rbac_editor/update_permissions', 'Admin\RBACEditor@updatePermissions');

Route::get('admin/theme_editor', 'Admin\ThemeEditor@index');
Route::post('admin/theme_editor', 'Admin\ThemeEditor@store');

/*
This is really unsafe, only enable if absulately necessary
Route::get('admin/artisan', 'Admin\ArtisanController@index');
Route::post('admin/artisan', 'Admin\ArtisanController@doCommand');
*/

Route::get('editor/text_editor/{page_id}/{text_id}', 'Editor\TextEditor@index');
Route::post('editor/text_editor', 'Editor\TextEditor@store');

Route::get('editor/image_editor/{page_id}/{image_id}', 'Editor\ImageEditor@index');
Route::post('editor/image_editor', 'Editor\ImageEditor@store');

Route::get('editor/gallery_editor/{page_id}/{gallery_id}', 'Editor\GalleryEditor@showData');
Route::post('editor/gallery_editor', 'Editor\GalleryEditor@storeData');
Route::get('editor/gallery_editor/add_image/{page_id}/{gallery_id}/{gallery_data_id?}', 'Editor\GalleryEditor@showImageForm');
Route::post('editor/gallery_editor/add_image', 'Editor\GalleryEditor@storeImage');
Route::get('editor/gallery_editor/images/{page_id}/{gallery_id}', 'Editor\GalleryEditor@showImageList');
Route::get('editor/gallery_editor/images/edit/{page_id}/{gallery_id}/{gallery_data_id}', 'Editor\GalleryEditor@showImageEditForm');
Route::post('editor/gallery_editor/images/edit', 'Editor\GalleryEditor@storeImage');
Route::post('editor/gallery_editor/images/delete', 'Editor\GalleryEditor@deleteImage');
Route::post('editor/gallery_editor/images/up', 'Editor\GalleryEditor@upImage');
Route::post('editor/gallery_editor/images/down', 'Editor\GalleryEditor@downImage');

Route::get('editor/image_uploader', 'Editor\ImageUploader@index');
Route::post('editor/image_uploader', 'Editor\ImageUploader@store');
Route::post('editor/image_uploader/delete', 'Editor\ImageUploader@delete');

Route::get('editor/blog_editor/add_entry/{blog_id}', 'Editor\BlogEditor@index');
Route::get('editor/blog_editor/edit_entry/{blog_id}/{entry_id}', 'Editor\BlogEditor@edit');
Route::get('editor/blog_editor/delete_entry/{blog_id}/{entry_id}', 'Editor\BlogEditor@delete');
Route::post('editor/blog_editor/entry', 'Editor\BlogEditor@store');

Route::get('language/{locale}', 'LocaleController@index');
Route::get('language', 'openController@index');


Route::get('/{page_url}/{args?}', function (Request $request, $page_url, $args = null) {
	
	$page = Pages::where('url', $page_url)->first();

    if (!$page)
    {
        Log::notice('Page->index: Page was bad, sending 404. $page: ' . $page);
        abort(404);
    }

	switch ($page->page_type)
	{
		case PageUrlContentIds::$CONTENT_PAGE:
			$p = new Page();
			return $p->index($request, $page, $page_url, $args);
			break;
		case PageUrlContentIds::$CONTENT_BLOG:
			$b = new Blog();
			return $b->index($request, $page, $page_url, $args);
			break;
		case PageUrlContentIds::$CONTENT_NONE:
		default:
			abort(404);
	}

})->where('args', '(.*)');


//Route::get('/{page}', 'Page@index');
Route::get('/', 'Page@indexRoot');

/*
Route::any('/', function () {
    return view('index_ph');
});
*/
