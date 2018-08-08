<?php

namespace App\Http\Controllers\REST\Images;

use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\Auth;

use App\Image as Image;
use App\User;
use App\ImageCategory;
use App\ImageTranslation;
use App\ImageCategoryTranslation;
use Intervention\Image\Facades\Image as I_Image;

class ImageController extends Controller
{
    private $img_upload_folder;
    private $img_thumbnail_upload_folder;

    public function __construct()
    {
        $path = base_path(config('app.upload_path'));
        $img_path = base_path(config('app.image_upload_path'));
        $img_thumbnails_path = base_path(config('app.image_thumbnail_upload_path'));

        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
        if (!file_exists($img_path)) {
            mkdir($img_path, 0777);
        }
        if (!file_exists($img_thumbnails_path)) {
            mkdir($img_thumbnails_path, 0777);
        }

        $this->img_upload_folder = $img_path;
        $this->img_thumbnail_upload_folder = $img_thumbnails_path;
    }

    //REST

    /**
     * Display a listing of the resource.
     *
     * @param  string $lang =null
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index($lang = null, Request $request) //$offset = 0, $limit = 9
    {
        /*
        $offset = $request['offset'];
        $limit = $request['limit'];
        $direction = $request['direction'];

        if (empty($offset) || !is_integer(intval($offset)) || $offset < 0)
            $offset = 0;
        if (empty($limit) || !is_integer(intval($limit)) || $limit < 1)
            $limit = 9;

        if (isset($direction)) {
            if ($direction === 'next') {
                $offset += $limit;
            } elseif ($direction === 'prev') {
                $offset -= $limit;
            }
            //refresh is default
        }

        if ($offset < 0)
            $offset = 0;
        if ($limit < 1)
            $limit = 1;
        */

        $img_batch_data = $this->get_images_by_offset($request, true);

        $images = $img_batch_data['images'];
        $count_images = $img_batch_data['count_images'];
        $offset = $img_batch_data['offset'];
        $limit = $img_batch_data['limit'];

        if (isset($request) && $request->ajax()) {
            $img_array = [];
            if (isset($images)) {
                foreach ($images as $image) {
                    if (!is_null($image)) {
                        strlen($image->title)>15 ? $multidot = '...' : $multidot = '';
                        $img_array[] =
                            '<div class="col-xs-12 col-md-4 ajax-item">
                                <div class="hover-menu">
                                    <a href="'.url(\Lang::getLocale(). '/REST/images/' . $image->id, null, env('HTTPS')).'" class="hover-menu-item"><i class="fa fa-search"
                                                                           aria-hidden="true"></i></a>
                                    <a href="'.url(\Lang::getLocale(). '/REST/images/' . $image->id . '/edit', null, env('HTTPS')).'" class="hover-menu-item"><i class="fa fa-pencil"
                                                                           aria-hidden="true"></i></a>
                                    <form method="POST" action="'.url(\Lang::getLocale(). '/REST/images/' . $image->id, null, env('HTTPS')).'" accept-charset="UTF-8" id="destroy-form" class="contact-form" data-ajax="true" data-ajax-id="image">
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button type="submit" class="hover-menu-item"><i
                                                    class="fa fa-times" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                    <p class="hover-menu-item">'.substr($image->title,0,15).$multidot.'</p>
                                </div>
                                <a href="'. url(\Lang::getLocale(). '/REST/images/' . $image->id, null, env('HTTPS')) .'"
                                   class="stretch"></a>
                                <img src="'.$image['image_data'].'"
                                     alt="'.$image->title.'"/>
                            </div>';
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('pages/admin.imgpageloaded') . ' | ' . $offset . '-' . ($count_images + $offset) . ', ' . $count_images,
                'mdata' => ['offset' => $offset, 'limit' => $limit, 'images' => $img_array, 'count_images' => $count_images]
            ]);
        }
        $categories = app('App\Http\Controllers\REST\Images\ImageCategoryController')->index();
        return view('admin.REST.images.index', ['offset' => $offset, 'limit' => $limit, 'images' => $images, 'count_images' => $count_images, 'categories' => $categories]);
    }

    public function get_images_by_offset(Request $request, $thumbnails = true) {
        $offset = $request['offset'];
        $limit = $request['limit'];
        $direction = $request['direction'];

        if (empty($offset) || !is_integer(intval($offset)) || $offset < 0)
            $offset = 0;
        if (empty($limit) || !is_integer(intval($limit)) || $limit < 1)
            $limit = 9;

        if (isset($direction)) {
            if ($direction === 'next') {
                $offset += $limit;
            } elseif ($direction === 'prev') {
                $offset -= $limit;
            }
            //refresh is default
        }

        if ($offset < 0)
            $offset = 0;
        if ($limit < 1)
            $limit = 1;

        $images = $this->batch($offset, $limit, $thumbnails);
        $count_images = count($images);

        return ['images' => $images, 'count_images' => $count_images, 'offset' => $offset, 'limit' => $limit];
    }

    public function batch($offset = 0, $limit = 6, $thumbnails = true)
    {
        //return Auth::user()->images->offset($offset)->limit(6);
        $user = Auth::user();
        if ($user->isAdmin()) {
            $images = Image::offset($offset)->limit($limit)->orderBy('updated_at', 'desc')->get();
        } else {
            $images = Image::where('user_id', $user->id)->orWhere('public', '=',
                1)->offset($offset)->limit($limit)->orderBy('updated_at', 'desc')->get();
            /*
            if (\Lang::getLocale() !== config('app.fallback_locale')) {
            }
            */
        }
        //get ids of images from sql query
        $ids = [];
        foreach ($images as $image) {
            $ids[] = $image->id;
        }
        /*
        $ids = array_map(function($e) {
            return is_object($e) ? $e->id : $e['id'];
        }, $images);
        */
        $images_data = HelperController::getImages(\Lang::getLocale(), $ids, $thumbnails);

        //translate if neccessary
        foreach ($images as $image) {
            $image_data = null;
            foreach ($images_data as $key => $img) {
                if ($key === $image->id)
                    $image_data = $img;
            }

            $trans = $image->translation($image->id, \Lang::getLocale());
            if (isset($trans)) {
                if (isset($trans[0]['attributes']['title']))
                    $image->title = $trans[0]['attributes']['title'];
                if (isset($trans[0]['attributes']['comment']))
                    $image->comment = $trans[0]['attributes']['comment'];
            }
            $image['image_data'] = $image_data;
        }
        /*
        $imagesarray = [];
        foreach ($images as $item) {
            $imagesarray[] = $item->id;
        }
        */
        //$images = HelperController::getImage($imagesarray);

        return $images;
    }

    public function getImagesForImageSelector(Request $request) {
        $images_data = $this->get_images_by_offset($request, true);
        $images = $images_data['images'];

        if (isset($request) && $request->ajax()) {
            $img_array = [];
            if (isset($images)) {
                foreach ($images as $image) {
                    if (!is_null($image)) {
                        strlen($image->title)>15 ? $multidot = '...' : $multidot = '';
                        $img_array[] =
                            '<div class="col-xs-12 col-sm-4 col-md-15 ajax-item">
                                <div class="box-image">
                                    <button type="button" class="tools" data-index="image_selector" data-val="' . $image->id . '" title="' . $image->title . '"></button>
                                    <img src="' . $image['image_data'] . '" alt="' . $image->title . '" />
                                </div>
                            </div>';
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('pages/admin.imgpageloaded') . ' | ' . $images_data['offset'] . '-' . ($images_data['count_images'] + $images_data['offset']) . ', ' . $images_data['count_images'],
                'mdata' => ['offset' => $images_data['offset'], 'limit' => $images_data['limit'], 'images' => $img_array, 'count_images' => $images_data['count_images']]
            ]);
        }

        return $images_data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ImageCategory::all();
        return view('admin.REST.images.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $file = $request->file('imgfile');
            if(isset($file) && $file->isValid()) {
                $name = $file->getClientOriginalName();
                $extension = $file->extension();
            }

            $user = Auth::user();
            $path = $this->img_upload_folder;
            $path_thumbnail = $this->img_thumbnail_upload_folder;
        } catch (\Exception $ex) {
            return back()->with(['error' => __('pages/admin.invalidfile')])->withInput();
        }

        if (isset($file) && $file->isValid()) {
            if ($file->getSize() < 650000) {
                try {
                    if (substr($file->getMimeType(), 0, 5) == 'image') {
                        /*
                        if (!file_exists($path . $name) {
                            mkdir($path . $name, 0777, true);
                        }
                        */

                        //create file
                        $filename = md5($name . date('l jS F Y h:i:s A'));
                        //$file->move($path, $filename . "." . $extension);
                        $image = I_Image::make($file->getRealPath());
                        $image->save($path . $filename . "." . $extension);
                        //create thumbnail
                        $filename_thumb = $filename . "_thumb";
                        $thumbnail = $this->createThumbnail($image);
                        $has_thumbnail = !empty($thumbnail);
                        if($has_thumbnail) {
                            //$thumbnail->move($path_thumbnail, $filename_thumb . $extension);
                            $thumbnail->save($path_thumbnail . $filename_thumb . '.' . $extension);
                        }

                        $new = new Image;
                        $new->user_id = $user->id;
                        $new->name = substr($name, 0, 25);
                        if(isset($request['imgtitle']))
                            $new->title = substr($request['imgtitle'], 0, 25);
                        else
                            $new->title = '(Untitled)';
                        $new->comment = substr($request['imgcomment'], 0, 150);
                        $new->image_category_id = $request['imgcategory'];
                        $public = $request['public'];
                        if ($public === 1) {
                            $new->public = 1;
                        } else {
                            $new->public = 0;
                        }
                        $new->location = $filename . "." . $extension;
                        if($has_thumbnail) {
                            $new->location_thumb = $filename_thumb . "." . $extension;
                        } else {
                            $new->location_thumb = null;
                        }

                        $new->save();

                        //insert localizations
                        foreach (config('app.locales') as $key => $locale) {
                            if ($key !== config('app.fallback_locale')) {
                                if (isset($request['imgtitle_' . $key]) || isset($request['imgcomment_' . $key])) {
                                    $trans = new ImageTranslation;
                                    $trans->image_id = $new->id;
                                    $trans->language = $key;

                                    if (isset($request['imgtitle_' . $key])) {
                                        $trans->title = $request['imgtitle_' . $key];
                                    }
                                    if (isset($request['imgcomment_' . $key])) {
                                        $trans->comment = $request['imgcomment_' . $key];
                                    }
                                    $trans->save();
                                }
                            }
                        }

                        return back()->with(['message' => __('pages/admin.successuploadimg')]);
                    } else {
                        return back()->with(['error' => __('pages/admin.fileisntimg')])->withInput();
                    }
                } catch (\Exception $ex) {
                    return back()->with(['error' => __('pages/admin.invalidfile') . ' | ' . $ex->getMessage()])->withInput();
                }
            } else {
                return back()->with(['error' => __('pages/admin.filetoobig') . " (max 650000 B)"])->withInput();
            }
        } else {
            return back()->with(['error' => __('pages/admin.invalidfile')])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string $lang =null
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($lang = null, $id)
    {
        $user = Auth::user();
        $image = Image::find($id);
        if (isset($image)) {
            if ($image->user_id === $user->id || $user->isAdmin()) {
                if (\Lang::getLocale() !== config('app.fallback_locale')) {
                    $trans = $image->translation($image->id, \Lang::getLocale());
                    if (isset($trans)) {
                        if (isset($trans[0]['attributes']['title']))
                            $image->title = $trans[0]['attributes']['title'];
                        if (isset($trans[0]['attributes']['comment']))
                            $image->comment = $trans[0]['attributes']['comment'];
                    }
                }
                return view('admin.REST.images.show', ['image' => $image]);
            } else {
                return redirect(url(\Lang::getLocale() . '/REST/images/', null, env('HTTPS')))->with(['error' => __('pages/admin.noaccess')]);
            }
        } else {
            return redirect(url(\Lang::getLocale() . '/REST/images/', null, env('HTTPS')))->with(['error' => __('pages/admin.imgnotfound')]);
        }
        //HelperController::getImage(null,$id);
        //$user_id = Auth::user()->id;
        //$image = Image::where('id', $id)->where('user_id', $user_id)->get();

        /*
        if (isset($image) && !empty($image)) {
            HelperController::getImage(null,$id);
            //return view('admin.REST.images.show', ['image' => $image]);
        } else {
            return back()->with(['error' => __('pages/admin.imgnotfound')]);
        }
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $lang =null
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang = null, $id)
    {
        $user = Auth::user();
        $categories = ImageCategory::all();
        $image = Image::find($id);
        $trans_array = [];
        //$image['src'] = HelperController::getImage(null, $id);
        if (isset($image) && !empty($image)) {
            if ($image->user_id === $user->id || $user->isAdmin()) {
                foreach (config('app.locales') as $key => $locale) {
                    if ($key != config('app.fallback_locale')) {
                        $trans = $image->translation($image->id, $key);
                        if (isset($trans) && isset($trans[0])) {
                            $trans_array[$key] = $trans[0]['attributes'];
                        }
                    }
                }
                return view('admin.REST.images.edit', ['image' => $image, 'categories' => $categories, 'translations' => $trans_array]);
            } else {
                return redirect(url(\Lang::getLocale() . '/REST/images/', null, env('HTTPS')))->with(['error' => __('pages/admin.noaccess')]);
            }
        } else {
            return back()->with(['error' => __('pages/admin.imgnotfound')]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($lang = null, $id, Request $request)
    {
        try {
            $file = $request->file('imgfile');
        } catch (\Exception $ex) {
            return back()->with(['error' => __('pages/admin.invalidfile')])->withInput();
        }

        $user = Auth::user();
        $path = $this->img_upload_folder;
        $path_thumbnail = $this->img_thumbnail_upload_folder;
        $old = Image::find($id);

        if (isset($file) && $file->isValid()) {
            $name = $file->getClientOriginalName();
            $extension = $file->extension();

            if ($file->getSize() < 650000) {
                try {
                    if (substr($file->getMimeType(), 0, 5) === 'image') {
                        if (file_exists($path . $old->location)) {
                            unlink($path . $old->location);
                        }
                        if (file_exists($path_thumbnail . $old->location_thumb)) {
                            unlink($path_thumbnail . $old->location_thumb);
                        }
                        //$file->move($path, md5($name . date('l jS F Y h:i:s A')) . "." . $extension);

                        //create file
                        $filename = md5($name . date('l jS F Y h:i:s A'));
                        $image = I_Image::make($file->getRealPath());
                        $image->save($path . $filename . "." . $extension);
                        //create thumbnail
                        $filename_thumb = $filename . "_thumb";
                        $thumbnail = $this->createThumbnail($image);
                        $has_thumbnail = !empty($thumbnail);
                        if($has_thumbnail) {
                            //$thumbnail->move($path_thumbnail, $filename_thumb . $extension);
                            $thumbnail->save($path_thumbnail . $filename_thumb . '.' . $extension);
                        }

                        if (isset($old)) {
                            $old->name = substr($name, 0, 25);
                            $old->location = $filename . "." . $extension;
                            if($has_thumbnail) {
                                $old->location_thumb = $filename_thumb . "." . $extension;
                            } else {
                                $old->location_thumb = null;
                            }
                        }
                    } else {
                        return back()->with(['error' => __('pages/admin.fileisntimg')])->withInput();
                    }
                } catch (\Exception $ex) {
                    return back()->with(['error' => __('pages/admin.invalidfile')])->withInput();
                }
            } else {
                return back()->with(['error' => __('pages/admin.filetoobig') . " (max 650000 B)"])->withInput();
            }
        }

        if (isset($old)) {
            //$old->user_id = $user->id;
            //$old->name = substr($name, 0, 25);
            $old->title = substr($request['imgtitle'], 0, 25);
            $old->comment = substr($request['imgcomment'], 0, 150);
            $old->image_category_id = $request['imgcategory'];
            $public = $request['public'];
            if (is_null($public)) {
                $old->public = 0;
            } else {
                $old->public = 1;
            }
            //$old->location = md5($name . date('l jS F Y h:i:s A')) . "." . $extension;

            $old->save();

            //insert localizations
            foreach (config('app.locales') as $key => $locale) {
                if ($key !== config('app.fallback_locale')) {
                    if (isset($request['imgtitle_' . $key]) || isset($request['imgcomment_' . $key])) {
                        $trans = ImageTranslation::firstOrNew(['image_id' => $old->id, 'language' => $key]);
                        $trans->image_id = $old->id;
                        $trans->language = $key;

                        if (isset($request['imgtitle_' . $key])) {
                            $trans->title = $request['imgtitle_' . $key];
                        }
                        if (isset($request['imgcomment_' . $key])) {
                            $trans->comment = $request['imgcomment_' . $key];
                        }
                        $trans->save();
                    }
                }
            }

            return back()->with(['message' => __('pages/admin.successeditimg')]);
        } else {
            return back()->with(['error' => __('pages/admin.imagedoesntexist')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $lang =null
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang = null, $id, Request $request)
    {
        $path = $this->img_upload_folder;
        $path_thumbnail = $this->img_thumbnail_upload_folder;
        $user = Auth::user();
        $ajax = isset($request) && $request->ajax();
        if (is_array($id)) {
            $image = Image::whereIn('id', $id);
            foreach ($image as $item) {
                if ($image->user_id === $user->id || $user->isAdmin()) {
                    try {
                        Image::destroy($item);
                        if (file_exists($path . $image->location)) {
                            unlink($path . $image->location);
                        }
                        if (file_exists($path_thumbnail . $image->location_thumb)) {
                            unlink($path_thumbnail . $image->location_thumb);
                        }
                    } catch (\Exception $ex) {
                        if ($ajax) {
                            return response()->json([
                                'success' => false,
                                'message' => [__('pages/admin.imgdeletederror')]
                            ]);
                        }
                        return back()->with(['error' => __('pages/admin.imgdeletederror')]);
                    }
                } else {
                    if ($ajax) {
                        return response()->json([
                            'success' => false,
                            'message' => [__('pages/admin.noaccess')]
                        ]);
                    }
                    return back()->with(['error' => __('pages/admin.noaccess')]);
                }

                if ($ajax) {
                    return response()->json([
                        'success' => true,
                        'message' => [__('pages/admin.imgdeleted')]
                    ]);
                }
                return back()->with('message', __('pages/admin.imgdeleted'));
            }
        } else {
            try {
                $image = Image::findOrFail($id);
                if ($image->user_id === $user->id || $user->isAdmin()) {
                    Image::destroy($id);
                    if (file_exists($path . $image->location)) {
                        unlink($path . $image->location);
                    }
                    if (file_exists($path_thumbnail . $image->location_thumb)) {
                        unlink($path_thumbnail . $image->location_thumb);
                    }
                } else {
                    if ($ajax) {
                        return response()->json([
                            'success' => false,
                            'message' => [__('pages/admin.noaccess')]
                        ]);
                    }
                    return back()->with(['error' => __('pages/admin.noaccess')]);
                }

                if ($ajax) {
                    return response()->json([
                        'success' => true,
                        'message' => [__('pages/admin.imgdeleted')]
                    ]);
                }
                return back()->with('message', __('pages/admin.imgdeleted'));
            } catch (\Exception $ex) {
                if ($ajax) {
                    return response()->json([
                        'success' => false,
                        'message' => [__('pages/admin.imgdeletederror')]
                    ]);
                }
                return back()->with(['error' => __('pages/admin.imgdeletederror')]);
            }
        }
        return back()->with(['error' => __('pages/admin.imgdeletederror')]);
    }

    /**
     * @param $src_image
     * @return I_Image
     */
    public function createThumbnail($src_image) {
        $new = $src_image;
        //$mdata = getimagesize($src_image);

        /*
        switch(strtolower($mdata['mime']))
        {
            case 'image/png':
                $img = imagecreatefrompng($src_image);
                $new = imagecreatetruecolor($newWidth,$newHeight); //imagecreatetruecolor($mdata[0],$mdata[1]);
                imagecopyresized($new, $img, 0, 0, 0, 0, $newWidth, $newHeight, $mdata[0],$mdata[1]);//imagecopy($new,$img,0,0,0,0,$mdata[0],$mdata[1]);
                header('Content-Type: image/png');
                break;
            case 'image/jpeg':
                $img = imagecreatefromjpeg($src_image);
                $new = imagecreatetruecolor($newWidth,$newHeight);
                imagecopyresized($new, $img, 0, 0, 0, 0, $newWidth, $newHeight, $mdata[0],$mdata[1]);
                header('Content-Type: image/jpeg');
                break;
            case 'image/gif':
                $img = imagecreatefromgif($src_image);
                $new = imagecreatetruecolor($newWidth,$newHeight);
                imagecopyresized($new, $img, 0, 0, 0, 0, $newWidth, $newHeight, $mdata[0],$mdata[1]);
                header('Content-Type: image/gif');
                break;
            default:
                break;
        }

        //imagejpeg($new,$pathToSave.$file_name);
        //imagedestroy($new);
        */

        //$new = I_Image::make($new);

        /*
        $oldWidth = $new->width();
        $oldHeight = $new->height();
        $aspectRatio = $oldWidth / $oldHeight;
        */
        $newWidth = $new->width();
        $newHeight = $new->height();

        if($newWidth > 320) {
            $newWidth *= 0.2;
            $newHeight *= 0.2;

            $new->resize($newWidth, $newHeight);
        }

        //$image->fit(240, 157)->save(public_path('images/blog/' . $filename . '-thumbs.jpg'));

        return $new;
    }
}
