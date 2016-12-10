<?php

namespace TypiCMS\Modules\Videos\Http\Controllers;

use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Videos\Models\Video;
use TypiCMS\Modules\Videos\Repositories\VideoInterface as Repository;
use TypiCMS\Modules\Tags\Repositories\TagInterface;

class ApiController extends BaseApiController
{

    /**
     *  Array of endpoints that do not require authorization
     *  
     */
    protected $publicEndpoints = [];

    public function __construct(Repository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($labels, TagInterface $tags)
    {
      $model = $this->repository->create(Request::all());
      $error = $model ? false : true;


      if(!$error) {
        $labels = json_decode(Request::input("labels"));
        $this->update_tags($labels, $tags, $model->id);
      }
        return response()->json([
            'error' => $error,
            'model' => $model,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $model
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TagInterface $tags)
    {
        $labels = json_decode(Request::input("labels"));

        $this->update_tags($labels, $tags);

       // $updated = $this->repository->update(Request::all());

        /*return response()->json([
            'error' => !$updated,
        ]);*/
    }

    public function update_tags($labels, $tags, $video_id = false)
    {
      if(!empty($labels)) {
        foreach ($labels as $label) {

            if($video_id)
              $label->video_id = $video_id;

            // Обновляем тег
            if($tags->getFirstBy("id", $label->id)) {
              $label->en = array(
                'title' => $label->name
              );
              $tags->update(get_object_vars($label));

            // Создаем новый    
            } else { 

              $label->en = array(
                'title' => $label->name,
                'slug' => $this->spfng_uri_encode($label->id, 'now'),
                'status' => 1
              );
              
              if($label->svg != 1) {
                // Декодируем base64 в файл
                if(strlen($label->image) > 128) {     
                  $img = imagecreatefromstring(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $label->image)));
                } else {
                  $img = imagecreatefrompng($label->image);
                }

                // Создаем файл
                $filename = rand(11111,99999) . ".png";
                imagealphablending($img, true);
                imagesavealpha($img, true);
                imagepng($img, "uploads/tags/" . $filename);

                $label->image = $filename;
              }

              unset($label->id);
              unset($label->name);

              $tags->create(get_object_vars($label));
            }
        }
      } else {
        return false;
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Videos\Models\Video $video
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Video $video)
    {
        $deleted = $this->repository->delete($video);

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    public function upload_video(Request $request) {
        if (Request::file('name')->isValid()) {
            $extension = Request::file('name')->getClientOriginalExtension();
            $fileName = rand(11111,99999).'.'.$extension;
            $destinationPath = 'uploads/videos';
            $path = Request::file('name')->move($destinationPath, $fileName);
            
            echo $fileName;
        } else {
            return false;
        }
    }

    public function uploadYoutube(Request $request) {
        $link = Request::input('link');
        $type = Request::input('type');

        $uploaddir = 'uploads/videos/';
        $type = substr($type, 6);

        $fileName = rand(11111,99999) .'.'.$type;
        $uploadName = $uploaddir . $fileName;

        if (!copy($link, $uploadName)) {
            return false;
        } else {
            echo $fileName;
        }
    }

    protected function spfng_uri_encode($id = 0, $strtotime = '1970-01-01 00:00:00') {
      if (is_numeric($id) === false) {
        return false;
      }
      if (($strtotime = strtotime($strtotime)) === false) {
        return false;
      }
      $id = strrev($strtotime).(int) $id;
      $hash = base_convert($id, 10, 36);
      if (substr($id, 0, 1) === '0') {
        return substr_replace($hash, '_', rand(0, (strlen($hash) - 1)), 0);
      }
      return $hash;
    }
}
