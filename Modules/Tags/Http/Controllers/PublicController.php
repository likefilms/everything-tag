<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Tags\Repositories\TagInterface;
use TypiCMS\Modules\Videos\Repositories\VideoInterface;

class PublicController extends BasePublicController
{
    public function __construct(TagInterface $tag)
    {
        parent::__construct($tag);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $models = $this->repository->all();

        return view('tags::public.index')
            ->with(compact('models'));
    }

    /**
     * Show news.
     *
     * @return \Illuminate\View\View
     */
    public function show($slug, VideoInterface $video)
    {
        $model_tag = $this->repository->bySlug($slug);
        $model = $video->byId($model_tag->video_id);

        $yt = false;
        $vm = false;
        $type_page = "tag";

        $video_tags = array();

        if(!empty($this->repository->allBy("video_id", $model->id))) {
          foreach ($this->repository->allBy("video_id", $model->id) as $tag) {

            $video_tags[] = array(
              'type' => $tag->type,
              'name' => $tag->title,
              'link' => $tag->link,
              'start' => $tag->start,
              'end' => $tag->end,
              'end' => $tag->end,
              'data' => $tag->image,
              'data' => $tag->image,
              'left' => $tag->left,
              'top' => $tag->top,
              'width' => $tag->width,
              'height' => $tag->height
            );
          }
        }

        $model->labels = json_encode($video_tags);

        if(strripos($model->name, "yt_") !== false) {
          $yt = true;
          $model->name = substr($model->name, 3);
        }
        if(strripos($model->name, "vm_") !== false) {
          $vm = true;
          $model->name = substr($model->name, 3);
        }
        $view = 'videos::public.new_show';

        return view($view)
            ->with(compact('model','yt', 'vm', 'type_page', 'model_tag'));
    }
}
