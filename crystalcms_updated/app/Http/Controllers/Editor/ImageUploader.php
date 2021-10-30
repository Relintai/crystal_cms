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

use App\Models\ImageUploads;

//these will not be needed
use App\Models\ContentDataImage;
use App\Models\Pages;

class ImageUploader extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $info = $request->session()->get('info', false);

        $image_data = ImageUploads::all();

        return Theme::AdminView('image_uploader/list', [
                'image_data' => $image_data,
                'info' => $info]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function store(Request $request)
    {
        //TODO check if user has RBAC_Permission to really edit this image
        $width = $request->input('width', false);

        if (!$width || !is_numeric($width))
        {
            Log::critical("ImageUploader->store: The upload form's width property is missing!");
            return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
        }

        $width = intval($width);

        if ($request->hasFile('image'))
        {
            if (!$request->file('image')->isValid()) 
            {
                Log::critical('ImageUploader->store: File upload was unsuccessful!');

                $image->save();

                return redirect($page->url);
            }

            $image = new ImageUploads();

            $file = $request->file('image');

            $dh = new DirectoryHelper();

            $nn = "";
            $n = explode('.', $file->getClientOriginalName());
            for ($i = 0; $i < count($n) - 1; $i++)
            {
                $nn .= $n[$i];
            }

            $name = $dh->getUniqueImageFileName(public_path() . "/img/uploaded/", 'jpg', $nn, "image_");

            if (!$name)
            {
                return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
            }

            $filesystem = new \Illuminate\Filesystem\Filesystem();

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
                Log::warning('ImageUploader->store: imageHelper was unable to open the image! Image will not be deleted form the tmp directory! image name: ' . $name . ' errors: ' . json_encode($imageHelper->getErrors()));
                return redirect()->back()->withErrors(trans('errors.internal_error_try_again'));
            }

            if ($imageHelper->getWidth() > $width)
            {
                $imageHelper->shrinkKeepAspectRatioSave($width, public_path("/img/uploaded/" . $name), 'jpg');
            }
            else
            {
                $filesystem->copy(base_path('tmp/' . $name), public_path("/img/uploaded/" . $name));
            }

            $filesystem->delete(base_path('tmp/' . $name));

            $image->image = $name;
            $image->save();
        }

        return redirect('editor/image_uploader')->with('info', trans('admin.success'));
    }

    public function delete(Request $request)
    {
        $image_id = $request->input('image_id');

        $image = ImageUploads::findOrFail($image_id);


        $filesystem = new \Illuminate\Filesystem\Filesystem();

        if ($filesystem->exists(public_path('img/uploaded/' . $image->image)))
        {
            $filesystem->delete(public_path('img/uploaded/' . $image->image));
        }

        $image->delete();

        return redirect()->back()->with('info', trans('admin.success'));
    }
}
