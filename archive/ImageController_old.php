<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use App\User;
use Auth;

class ImageControllerOld extends Controller
{
    //REST

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($lang=null, Request $request) //$offset = 0, $limit = 6
    {
        $offset = 0;
        $limit = 6;
        if(isset($request['offset']))
            $offset = $request['offset'];
        if(isset($request['limit']))
            $limit = $request['limit'];

        $images = $this->batch($offset, $limit);
        return view('admin.REST.images.index', ['images' => $images]);
    }

    public function batch($offset = 0, $limit = 6)
    {
        //return Auth::user()->images->offset($offset)->limit(6);
        if (Auth::user()->isAdmin()) {
            $images = Image::offset($offset)->limit($limit)->get();
        } else {
            $images = Image::where('user_id', Auth::user()->id)->orWhere('public', '=',
                1)->offset($offset)->limit($limit)->get();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.REST.images.create');
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
            $name = $file->getClientOriginalName();
            $extension = $file->extension();

            $user = Auth::user();
            $path = base_path(config('app.image_upload_path'));
        } catch (\Exception $ex) {
            return back()->with(['error' => __('system.invalidfile')])->withInput();
        }

        if (isset($file) && $file->isValid()) {
            if ($file->getSize() < 350000) {
                try {
                    if (substr($file->getMimeType(), 0, 5) == 'image') {
                        /*
                        if (!file_exists($path . $name) {
                            mkdir($path . $name, 0777, true);
                        }
                        */
                        $file->move($path, md5($name . date('l jS F Y h:i:s A')) . "." . $extension);

                        $new = new Image;
                        $new->user_id = $user->id;
                        $new->name = substr($name, 0, 25);
                        $new->title = substr($request['imgtitle'], 0, 25);
                        $new->comment = substr($request['imgcomment'], 0, 150);
                        $public = $request['public'];
                        if (is_null($public)) {
                            $new->public = 0;
                        } else {
                            $new->public = 1;
                        }
                        $new->location = md5($name . date('l jS F Y h:i:s A')) . "." . $extension;
                        $new->save();

                        return back()->with(['message' => __('system.successuploadimg')]);
                    } else {
                        return back()->with(['error' => __('system.fileisntimg')])->withInput();
                    }
                } catch(\Exception $ex) {
                    return back()->with(['error' => __('system.invalidfile')])->withInput();
                }
            } else {
                return back()->with(['error' => __('system.filetoobig') . " (max 350000 B)"])->withInput();
            }
        } else {
            return back()->with(['error' => __('system.invalidfile')])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($lang=null, $id)
    {
        $user = Auth::user();
        $image = Image::findOrFail($id);
        if ($image->user_id === $user->id || $user->isAdmin()) {
            return view('admin.REST.images.show', ['images' => $id]);
        }
        //HelperController::getImage(null,$id);
        //$user_id = Auth::user()->id;
        //$image = Image::where('id', $id)->where('user_id', $user_id)->get();

        /*
        if (isset($image) && !empty($image)) {
            HelperController::getImage(null,$id);
            //return view('admin.REST.images.show', ['image' => $image]);
        } else {
            return back()->with(['error' => __('system.imgnotfound')]);
        }
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $lang=null
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang=null,$id)
    {
        //$photo = Image::findOrFail($id);
        $image = HelperController::getImage(null, $id);
        if (isset($image) && !empty($image)) {
            return view('admin.REST.images.edit', ['image' => $image]);
        } else {
            return back()->with(['error' => __('system.imgnotfound')]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string $lang=null
     * @param  int $id
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update($lang=null, $id, Request $request)
    {
        $file = $request->file('image');
        if (isset($file)) {
            $name = $file->getClientOriginalName();
            $extension = $file->extension();
        }
        $user = Auth::user();
        $path = base_path(config('app.image_upload_path'));

        $new = Image::findOrFail($id);
        if ($user->id === $new->user_id || $user->isAdmin()) {
            $new->user_id = $user->id;
            $new->title = substr($request['imgtitle'], 0, 25);
            $new->comment = substr($request['imgcomment'], 0, 150);
            $public = $request['public'];
            if (is_null($public)) {
                $new->public = 0;
            } else {
                $new->public = 1;
            }
            if (isset($file)) {
                if ($file->isValid()) {
                    if ($file->getSize() < 350000) {
                        if (substr($file->getMimeType(), 0, 5) == 'image') {
                            /*
                            if (!file_exists('user_photos/' . $user->name . "/Photos/")) {
                                mkdir('user_photos/' . $user->name . "/Photos/", 0777, true);
                            }*/
                            $new->name = substr($name, 0, 25);
                            $file->move($path, md5($name . date('l jS F Y h:i:s A')) . "." . $extension);

                            try {
                                //Destroy old image
                                if (file_exists($new->location)) {
                                    unlink($new->location);
                                }
                            } catch (\Exception $ex) {

                            }

                            $new->location = md5($name . date('l jS F Y h:i:s A')) . "." . $extension;
                        } else {
                            return back()->with(['error' => __('system.fileisntimg')]);
                        }
                    } else {
                        return back()->with(['error' => __('system.filetoobig') . " (max 350000 B)"]);
                    }
                }
            } else {
                return back()->with(['error' => __('system.invalidfile')])->withInput();
            }

            $new->save();
            return back()->with(['message' => __('system.successeditimg')]);
        }
        else {
            return back()->with(['error' => __('system.noaccess')])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($lang=null, $id)
    {
        $path = base_path(config('app.image_upload_path'));
        $user = Auth::user();
        if (is_array($id)) {
            $image = Image::whereIn('id', $id);
            foreach ($image as $item) {
                if ($image->user_id === $user->id || $user->isAdmin()) {
                    try {
                        Image::destroy($item);
                        if (file_exists($path . $image->location)) {
                            unlink($path . $image->location);
                        }
                    } catch (\Exception $ex) {
                        return back()->with(['error' => __('system.imgdeletederror')]);
                    }
                } else {
                    return back()->with(['error' => __('system.noaccess')]);
                }

                return back()->with('message', __('system.imgdeleted'));
            }
        } else {
            try {
                $image = Image::findOrFail($id);
                if ($image->user_id === $user->id || $user->isAdmin()) {
                    Image::destroy($id);
                    if (file_exists($path . $image->location)) {
                        unlink($path . $image->location);
                    }
                } else {
                    return back()->with(['error' => __('system.noaccess')]);
                }

                return back()->with('message', __('system.imgdeleted'));
            } catch (\Exception $ex) {
                return back()->with(['error' => __('system.imgdeletederror')]);
            }
        }
    }
}
