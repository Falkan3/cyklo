<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App;
use Illuminate\Http\Request;
use App\Image;

class HelperController extends Controller
{
    /* --------------------- Static --------------------- */

    /**
     * Handle an incoming request.
     *
     * @param  $lang
     * @param Request $request
     * @return mixed
     */
    public static function switchLanguage($lang = null, $new_lang = null, Request $request)
    {
        // Make sure locale exists.
        if (isset($new_lang)) {
            $locale = $request->segment(1);
            if (array_key_exists($locale, config('app.locales'))) {
                $locale = $new_lang;
                if(array_key_exists($locale, config('app.locales'))) {
                    $segments = explode('/', \URL::previous());
                    $segments[3] = $locale;
                    $newUrl = implode('/', $segments);
                    if (array_key_exists('QUERY_STRING', $_SERVER))
                        $newUrl .= '?' . $_SERVER['QUERY_STRING'];
                    App::setLocale($locale);
                }
            }
        }

        return redirect(url($newUrl, env('HTTPS')));
        //return redirect()->route('index');
    }

    static function placeholderImage()
    {
        return asset('images/els/placeholder.jpg', env('HTTPS'));
    }

    /* --------------------- /Static --------------------- */

    /* --------------------- Images --------------------- */

    public static function getImage($lang = null, $id, $get_thumbnail = false)
    {
        try {
            $return = null;
            //if (!is_array($id))
            $image = Image::findOrFail($id);
            $path = base_path(config('app.image_upload_path')) . $image['location'];
            if (!empty($image['location_thumb'])) {
                $path_thumbnail = base_path(config('app.image_thumbnail_upload_path')) . $image['location_thumb'];
            }

            if (Auth::guest()) {
                if ($image['public'] === 1) {
                    if ($get_thumbnail && !empty($path_thumbnail)) {
                        if (file_exists($path_thumbnail)) {
                            $return = readfile($path_thumbnail);
                        } else {
                            $return = readfile($path);
                        }
                    } else {
                        $return = readfile($path);
                    }
                } else {
                    $return = __('pages/admin.noaccess');
                }
            } elseif ($image['user_id'] === Auth::user()->id || Auth::user()->isAdmin() || $image['public'] === 1) {
                if ($get_thumbnail && !empty($path_thumbnail)) {
                    if (file_exists($path_thumbnail)) {
                        $return = readfile($path_thumbnail);
                    } else {
                        $return = readfile($path);
                    }
                } else {
                    $return = readfile($path);
                }

                /*
                $type = "image/jpeg";
                header('Content-Type:' . $type);
                header('Content-Length: ' . filesize($path));
                */
            } else {
                $return = __('pages/admin.noaccess');
                /*
                $return['error'] = true;
                $return['message'] = __('pages/admin.noaccess');
                */
            }

            /*
              else {
                  $images = Image::whereIn('id', $id)->get();
                  foreach ($images as $item) {
                      if (Auth::user()->isAdmin() || $item->public === 1 || $item->user_id === Auth::user()->id) {
                          $tempitem = [];
                          $path = base_path('resources/uploads/images/') . $item->location;
                          $type = "image/jpeg";
                          header('Content-Type:' . $type);
                          header('Content-Length: ' . filesize($path));
                          $tempimg = readfile($path);
                          if($tempimg !== null) {
                              $tempitem['image'] = $tempimg;
                              $return['images'][] = $tempitem;
                          }
                          //src="{{ url('/images/test.jpg') }}"
                      } else {
                          //$tempitem['error'] = true;
                          //$tempitem['message'] = __('pages/admin.noaccess');
                      }

                  }
              }
              */
        } catch (\Exception $ex) {
            $return = __('pages/admin.imagefetcherror') . ' | ' . $ex->getMessage();
            /*
            $return['error'] = true;
            $return['message'] = __('pages/admin.imagefetcherror');
            */
        }

        return $return;
    }

    public static function getImages($lang = null, $ids, $get_thumbnails = true)
    {
        if (is_string($ids))
            $ids = explode(',', $ids);

        try {
            $return = [];
            $user = Auth::user();

            $images = Image::whereIn('id', $ids)->get();
            foreach ($images as $image) {
                $path = base_path(config('app.image_upload_path')) . $image['location'];
                if (!empty($image['location_thumb'])) {
                    $path_thumbnail = base_path(config('app.image_thumbnail_upload_path')) . $image['location_thumb'];
                }

                if (Auth::guest()) {
                    if ($image['public'] === 1) {
                        if ($get_thumbnails && !empty($path_thumbnail)) {
                            if (file_exists($path_thumbnail)) {
                                $return[$image['id']] = "data: " . mime_content_type($path_thumbnail) . ';base64,' . base64_encode(file_get_contents($path_thumbnail));
                            } else {
                                $return[$image['id']] = self::placeholderImage();
                            }
                        } else {
                            if (file_exists($path)) {
                                $return[$image['id']] = "data: " . mime_content_type($path) . ';base64,' . base64_encode(file_get_contents($path));
                            } else {
                                $return[$image['id']] = self::placeholderImage();
                            }
                        }
                    } else {
                        $return[$image['id']] = self::placeholderImage();
                        //$return[$image['id']] = __('pages/admin.noaccess');
                    }
                } elseif ($image['user_id'] === $user->id || $user->isAdmin() || $image['public'] === 1) {
                    /*
                    $type = "image/jpeg";
                    header('Content-Type:' . $type);
                    header('Content-Length: ' . filesize($path));
                    */
                    if ($get_thumbnails && !empty($path_thumbnail)) {
                        if (file_exists($path_thumbnail)) {
                            $return[$image['id']] = "data: " . mime_content_type($path_thumbnail) . ';base64,' . base64_encode(file_get_contents($path_thumbnail));
                        } else {
                            $return[$image['id']] = self::placeholderImage();
                        }
                    } else {
                        if (file_exists($path)) {
                            $return[$image['id']] = "data: " . mime_content_type($path) . ';base64,' . base64_encode(file_get_contents($path));
                        } else {
                            $return[$image['id']] = self::placeholderImage();
                        }
                    }

                } else {
                    $return[$image['id']] = self::placeholderImage();
                    //$return = __('pages/admin.noaccess');
                }
            }
        } catch (\Exception $ex) {
            $return = $ex->getMessage();
        }

        return $return;
    }

    /* --------------------- /Images --------------------- */

    /* --------------------- Products --------------------- */

    /**
     * Return the list of products (API)
     *
     * @param  string $lang =null
     * @param Request $request
     * @return array
     */
    public function listProducts($lang = null)
    {
        return [];
    }

    /**
     * Return the list of product categories (API)
     *
     * @param  string $lang =null
     * @param Request $request
     * @return array
     */
    public function listProductCategories($lang = null)
    {
        $categories = app('App\Http\Controllers\REST\Products\ProductCategoryController')->index();

        //get ids of categories
        $ids = [];
        foreach ($categories as $item) {
            $ids[] = $item->image_id;
        }

        $images = self::getImages(\Lang::getLocale(), $ids);

        //add image data to each item before returning the categories list
        foreach ($categories as $category) {
            $image_data = null;
            foreach ($images as $key => $img) {
                if ($key === $category->image_id)
                    $image_data = $img;
            }

            $category['image_data'] = $image_data;
        }

        //find categories without images and add a placeholder image
        foreach ($categories as $category) {
            if (empty($category['image_data'])) {
                $category['image_data'] = self::placeholderImage();
            }
        }

        return $categories;
    }

    /* --------------------- /Products --------------------- */
}
