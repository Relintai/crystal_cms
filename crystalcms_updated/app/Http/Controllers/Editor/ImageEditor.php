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
use App\Models\ContentDataImage;
use App\Models\Pages;

class ImageEditor extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($page_id, $image_id, Request $request)
    {
        $info = $request->session()->get('info', false);

        $image_data = ContentDataImage::findOrFail($image_id);

        return Theme::AdminView('image_editor/editor', [
                'image_data' => $image_data,
                'info' => $info,
                'page_id' => $page_id,
                'image_id' => $image_id]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function store(Request $request)
    {
        //TODO check if user has RBAC_Permission to really edit this image

        $alt = $request->input('alt');

        $page_id = $request->input('page_id');
        $image_id = $request->input('image_id');

        $image = ContentDataImage::findOrFail($image_id);
        $page = Pages::findOrFail($page_id);

        $image->alt = $alt;

        if ($request->hasFile('image'))
        {
            if (!$request->file('image')->isValid()) 
            {
                Log::critical('imageEditor->store: File upload was unsuccessful!');

                $image->save();

                return redirect($page->url);
            }

            $file = $request->file('image');

            $dh = new DirectoryHelper();

            $name = $dh->getUniqueImageFileName(public_path() . "/img/images/full/", 'jpg', $alt, "image_");

            if (!$name)
            {
                return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
            }

            $filesystem = new \Illuminate\Filesystem\Filesystem();

            if ($image->image_full && $filesystem->exists(public_path("/img/images/full/" . $image->image_full)))
            {
                $filesystem->delete(public_path() . "/img/images/full/" . $image->image_full);
            }

            if ($image->image_small && $filesystem->exists(public_path("/img/images/small/" . $image->image_small)))
            {
                $filesystem->delete(public_path() . "/img/images/small/" . $image->image_small);
            }

            //if the tmp directory contains an image with this name
            //this can happen if the previous upload was bad
            if ($filesystem->exists(base_path('tmp'), $name))
            {
                $filesystem->delete(base_path('tmp'), $name);
            }

            $file->move(base_path('tmp'), $name);

            $imageHelper = new ImageHelper();

            $imageHelper->loadImage(base_path('tmp/' . $name));

            if (!$imageHelper->isLoaded())
            {
                Log::warning('ImageEditor->store: imageHelper was unable to open the image! Image will not be deleted form the tmp directory! image name: ' . $name . ' errors: ' . json_encode($imageHelper->getErrors()));
                return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
            }

            if ($imageHelper->getWidth() > 1400)
            {
                $imageHelper->shrinkKeepAspectRatioSave(1400, public_path("/img/images/full/" . $name), 'jpg');
            }
            else
            {
                $filesystem->copy(base_path('tmp/' . $name), public_path("/img/images/full/" . $name));
            }

            if ($imageHelper->getWidth() > 1000)
            {
                $imageHelper->shrinkKeepAspectRatioSave(1000, public_path("/img/images/small/" . $name), 'jpg');
            }
            else
            {
                $filesystem->copy(base_path('tmp/' . $name), public_path("/img/images/small/" . $name));
            }

            $filesystem->delete(base_path('tmp/' . $name));

            $image->image_small = $name;
            $image->image_full = $name;
        }

        $image->save();

        return redirect($page->url);
    }
}
