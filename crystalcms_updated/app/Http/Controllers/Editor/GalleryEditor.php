<?php

namespace App\Http\Controllers\Editor;

use Illuminate\Http\Request;

use Log;
use Theme;

use App\Helpers\ImageHelper;
use App\Helpers\DirectoryHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\ContentDataGallery;
use App\Models\GalleryData;
use App\Models\Pages;

//this won't be needed after the class is finished
use App\Models\ContentDataImage;

class GalleryEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showData($page_id, $gallery_id, Request $request)
    {
        $info = $request->session()->get('info', false);

        $gallery_data = ContentDataGallery::findOrFail($gallery_id);

        return Theme::AdminView('gallery_editor/gallery', [
            'gallery_data' => $gallery_data,
            'info' => $info,
            'page_id' => $page_id,
            'gallery_id' => $gallery_id]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function storeData(Request $request)
    {
        $page_id = $request->input('page_id');
        $gallery_id = $request->input('gallery_id');

        $name = $request->input('name');
        $folder = $request->input('folder');
        $description = $request->input('description');

        $gallery = ContentDataGallery::findOrFail($gallery_id);
        $page = Pages::findOrFail($page_id);

        if ($gallery->folder != $folder)
        {
            $dh = new DirectoryHelper();

            $folder = $dh->sanitizeFolderName($folder);

            $filesystem = new \Illuminate\Filesystem\Filesystem();

            if ($gallery->folder && $filesystem->exists(public_path('img/gallery/' . $gallery->folder)))
            {
                if ($filesystem->exists(public_path('img/gallery/' . $folder)))
                {
                    Log::error('GalleryEditor->storeData: Folder already exists, returning error. folder: ' . $folder);
                    return redirect()->back()->withErrors(trans('errors.folder_already_exists'));
                }

                if (!rename(public_path('img/gallery/' . $gallery->folder), public_path('img/gallery/' . $folder)))
                {
                    Log::error('GalleryEditor->storeData: rename was unsuccessful. folder: ' . $folder . ' folder in db: ' . $gallery->folder);
                    return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
                }
            }
        }

        $gallery->name = $name;
        $gallery->folder = $folder;
        $gallery->description = $description;
        $gallery->save();

        return redirect($page->url);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showImageForm(Request $request, $page_id, $gallery_id, $gallery_data_id = null)
    {
        $info = $request->session()->get('info', false);

        $gallery_data = null;
        if ($gallery_data_id)
        {
            $gallery_data = GalleryData::find($gallery_data_id);

            if (!$gallery_data)
            {
                Log::warning('GalleryEditor->showImageForm: gallery_data_id does not exists. gallery_data_id: ' . $gallery_data_id);
                abort(404);
            }
        }

        return Theme::AdminView('gallery_editor/image_upload', [
            'info' => $info,
            'gallery_data' => $gallery_data,
            'page_id' => $page_id,
            'gallery_id' => $gallery_id]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function storeImage(Request $request)
    {
        //TODO check if user has RBAC_Permission to really edit this gallery

        $alt = $request->input('alt');

        $page_id = $request->input('page_id');
        $gallery_id = $request->input('gallery_id');
        $gallery_data_id = $request->input('gallery_data_id', false);
        $description = $request->input('description');

        $gallery = ContentDataGallery::findOrFail($gallery_id);
        $page = Pages::findOrFail($page_id);

        if (!$request->hasFile('image') && !$gallery_data_id)
        {
            Log::warning('GalleryEditor->storeImage: File upload was unsuccessful!');

            return redirect()->back()->withErrors(trans('errors.image_field_required'));
        }

        $new = true;
        $gallerydata = null;
        if ($gallery_data_id)
        {
            $gallerydata = GalleryData::findOrFail($gallery_data_id);
            $new = false;
        }
        else
        {
            $gallerydata = new GalleryData();
            $gallerydata->gallery_id = $gallery->id;

            //sort order
            $d = GalleryData::where('gallery_id', $gallery->id)->orderBy('sort_order', 'desc')->first();

            if ($d)
            {
                $gallerydata->sort_order = $d->sort_order + 1;
            }
            else
            {
                $gallerydata->sort_order = 1;
            }
        }

        $gallerydata->description = $description;

        if ($request->hasFile('image'))
        {
            if (!$request->file('image')->isValid()) 
            {
                Log::critical('GalleryEditor->storeImage: File upload was unsuccessful!');

                return redirect()->back()->withErrors(trans('errors.image_upload_unsuccessful_try_again'));
            }

            $file = $request->file('image');

            $dh = new DirectoryHelper();

            if (!$gallery->folder)
            {
                $gallery->folder = $dh->getUniqueDirectory(public_path('img/gallery/'), $gallery->name, 'gallery');
                $gallery->save();
            }

            $filesystem = new \Illuminate\Filesystem\Filesystem();

            if (!$filesystem->exists(public_path('img/gallery/' . $gallery->folder)))
            {
                $filesystem->makeDirectory(public_path('img/gallery/' . $gallery->folder));
                $filesystem->makeDirectory(public_path('img/gallery/' . $gallery->folder . '/' . 'thumb'));
                $filesystem->makeDirectory(public_path('img/gallery/' . $gallery->folder . '/' . 'big'));
            }

            $image_name = $dh->getUniqueImageFileName(public_path('img/gallery/' . $gallery->folder . '/thumb/'), 'jpg', $description, "image_");

            if (!$image_name)
            {
                return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
            }

            if ($gallerydata->image_thumb && $filesystem->exists(public_path('img/gallery/' . $gallery->folder . '/thumb/' . $gallerydata->image_thumb)))
            {
                $filesystem->delete(public_path('img/gallery/' . $gallery->folder . '/thumb/' . $gallerydata->image_thumb));
            }

            if ($gallerydata->image_big && $filesystem->exists(public_path('img/gallery/' . $gallery->folder . '/big/' . $gallerydata->image_big)))
            {
                $filesystem->delete(public_path('img/gallery/' . $gallery->folder . '/big/' . $gallerydata->image_big));
            }

            //if the tmp directory contains an image with this name
            //this can happen if the previous upload was bad
            if ($filesystem->exists(base_path('tmp'), $image_name))
            {
                $filesystem->delete(base_path('tmp'), $image_name);
            }

            $file->move(base_path('tmp'), $image_name);

            $imageHelper = new ImageHelper();

            $imageHelper->loadImage(base_path('tmp/' . $image_name));

            if (!$imageHelper->isLoaded())
            {
                Log::warning('GalleryEditor->store: imageHelper was unable to open the image! Image will not be deleted form the tmp directory! image name: ' . $name . ' errors: ' . json_encode($imageHelper->getErrors()));
                return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
            }

            if ($imageHelper->getWidth() > 1400)
            {
                $imageHelper->shrinkKeepAspectRatioSave(1400, public_path('img/gallery/' . $gallery->folder . '/big/' . $image_name), 'jpg');
            }
            else
            {
                $filesystem->copy(base_path('tmp/' . $image_name), public_path('img/gallery/' . $gallery->folder . '/big/' . $image_name));
            }

            $imageHelper->makeThumbnail(200, public_path('img/gallery/' . $gallery->folder . '/thumb/' . $image_name), 'jpg');


            $filesystem->delete(base_path('tmp/' . $image_name));

            $gallerydata->image_thumb = $image_name;
            $gallerydata->image_big = $image_name;
        }

        $gallerydata->save();

        if ($new)
        {
            return redirect($page->url);
        }
        
        return redirect('editor/gallery_editor/images/' . $page_id . '/' . $gallery_id);
    }

    public function showImageList(Request $request, $page_id, $gallery_id)
    {
        $info = $request->session()->get('info', false);

        $content_gallery_data = ContentDataGallery::findOrFail($gallery_id);
        $gallery_data = GalleryData::where('gallery_id', $gallery_id)->orderBy('sort_order', 'asc')->get();

        return Theme::AdminView('gallery_editor/images', [
            'content_gallery_data' => $content_gallery_data,
            'gallery_data' => $gallery_data,
            'info' => $info,
            'page_id' => $page_id,
            'gallery_id' => $gallery_id]);
    }

    public function deleteImage(Request $request)
    {
        $page_id = $request->input('page_id');
        $gallery_id = $request->input('gallery_id');
        $gallery_data_id = $request->input('gallery_data_id');

        $page = Pages::findOrFail($page_id);
        $gallery = ContentDataGallery::findOrFail($gallery_id);
        $gallery_data = GalleryData::findOrFail($gallery_data_id);

        GalleryData::where('gallery_id', $gallery_id)->where('sort_order', '>', $gallery_data->sort_order)
                ->decrement('sort_order', 1);

        $filesystem = new \Illuminate\Filesystem\Filesystem();

        if ($filesystem->exists(public_path('img/gallery/' . $gallery->folder . '/thumb/' . $gallery_data->image_thumb)))
        {
            $filesystem->delete(public_path('img/gallery/' . $gallery->folder . '/thumb/' . $gallery_data->image_thumb));
        }

        if ($filesystem->exists(public_path('img/gallery/' . $gallery->folder . '/big/' . $gallery_data->image_thumb)))
        {
            $filesystem->delete(public_path('img/gallery/' . $gallery->folder . '/big/' . $gallery_data->image_thumb));
        }

        $gallery_data->delete();

        return redirect()->back()->with('info', trans('admin.success'));
    }

    public function upImage(Request $request)
    {
        $page_id = $request->input('page_id');
        $gallery_id = $request->input('gallery_id');
        $gallery_data_id = $request->input('gallery_data_id');

        $page = Pages::findOrFail($page_id);
        $gallery = ContentDataGallery::findOrFail($gallery_id);
        $gallery_data = GalleryData::findOrFail($gallery_data_id);

        if ($gallery_data->sort_order == 1)
        {
            Log::warning("GalleryEditor->upImage: Image's sort order is already 0! gallery_data: " . $gallery_data->toJson());
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $upper = GalleryData::where('gallery_id', $gallery_id)->where('sort_order', $gallery_data->sort_order - 1)->first();

        if (!$upper)
        {
            Log::warning("GalleryEditor->upImage: sort_order -1 doesn't exists! gallery_data: " . $gallery_data->toJson());
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $upper->sort_order += 1;
        $gallery_data->sort_order -= 1;

        $upper->save();
        $gallery_data->save();

        return redirect()->back()->with('info', trans('admin.success'));
    }

    public function downImage(Request $request)
    {
        $page_id = $request->input('page_id');
        $gallery_id = $request->input('gallery_id');
        $gallery_data_id = $request->input('gallery_data_id');

        $page = Pages::findOrFail($page_id);
        $gallery = ContentDataGallery::findOrFail($gallery_id);
        $gallery_data = GalleryData::findOrFail($gallery_data_id);

        $highest = GalleryData::where('gallery_id', $gallery_id)->orderBy('sort_order', 'desc')->first();

        if (!$highest)
        {
            Log::warning("GalleryEditor->downImage: Highest doesn't exists, it means that the gallery is empty!");
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        if ($gallery_data->sort_order == $highest->sort_order)
        {
            Log::warning("GalleryEditor->downImage: Image's sort order is already at max! gallery_data: " . $gallery_data->toJson());
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $lower = GalleryData::where('gallery_id', $gallery_id)->where('sort_order', $gallery_data->sort_order + 1)->first();

        if (!$lower)
        {
            Log::warning("GalleryEditor->downImage: sort_order -1 doesn't exists! gallery_data: " . $gallery_data->toJson());
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $lower->sort_order -= 1;
        $gallery_data->sort_order += 1;

        $lower->save();
        $gallery_data->save();

        return redirect()->back()->with('info', trans('admin.success'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function showImageEditForm(Request $request, $page_id, $gallery_id, $gallery_data_id)
    {
        $info = $request->session()->get('info', false);

        $gallery_data = null;

        $gallery_data = GalleryData::find($gallery_data_id);

        if (!$gallery_data)
        {
            Log::warning('GalleryEditor->showImageForm: gallery_data_id does not exists. gallery_data_id: ' . $gallery_data_id);
            abort(404);
        }

        return Theme::AdminView('gallery_editor/image_edit', [
            'info' => $info,
            'gallery_data' => $gallery_data,
            'page_id' => $page_id,
            'gallery_id' => $gallery_id]);
    }
}