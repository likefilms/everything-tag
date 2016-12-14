<?php

namespace TypiCMS\Modules\Videos\Http\Controllers;


use Illuminate\Http\Request;
use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Videos\Repositories\VideoInterface;
use TypiCMS\Modules\Tags\Repositories\TagInterface;
use TypiCMS\Modules\Tags\Models\Tag;

class PublicController extends BasePublicController
{
    public function __construct(VideoInterface $video)
    {
        parent::__construct($video);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user_id = $request->user()->id;
        $models = $this->repository->allBy("user_id",$user_id);

        return view('videos::public.index')
            ->with(compact('models'));
    }

    /**
     * Show news.
     *
     * @return \Illuminate\View\View
     */
    public function show($slug, TagInterface $tags)
    {
        $model = $this->repository->bySlug($slug);
        $yt = false;
        $vm = false;
        $type_page = "video";

        $video_tags = array();

        if(!empty($tags->allBy("video_id", $model->id))) {
          foreach ($tags->allBy("video_id", $model->id) as $tag) {

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

          // Сортируем массив
          $video_start = array();
          foreach ($video_tags as $key => $v) {
            $video_start[$key] = $v['start'];
          }

          array_multisort($video_start, SORT_NUMERIC, $video_tags);
        }

        $model->labels = json_encode($video_tags);

        //if($slug == '396l1jfo' || $slug == '3zxgz7b9' || $slug == 'cgjxlhvw') {
            if(strripos($model->name, "yt_") !== false) {
              $yt = true;
              $model->name = substr($model->name, 3);
            }
            if(strripos($model->name, "vm_") !== false) {
              $vm = true;
              $model->name = substr($model->name, 3);
            }
            $view = 'videos::public.new_show';
       // } else {
           // $view = 'videos::public.show';
       // }

        return view($view)
            ->with(compact('model','yt', 'vm', 'type_page'));
    }

    public function oembed($slug)
    {
        $model = $this->repository->bySlug($slug);
        $yt = false;
        $vm = false;

        if(strripos($model->name, "yt_") !== false) {
          $yt = true;
          $model->name = substr($model->name, 3);
        }
        if(strripos($model->name, "vm_") !== false) {
          $vm = true;
          $model->name = substr($model->name, 3);
        }

        return view('videos::public.oembed')
            ->with(compact('model','yt', 'vm'));
    }

    public function create(Request $request)
    {
        $model = $this->repository->getModel();

        $last = $this->repository->latest(1);
        $id = $last[0]->id + 1;
        $slug = $this->spfng_uri_encode($id,'now');

        $user_id = $request->user()->id;

        if(!isset($_GET['file'])) return redirect('/');

        $file = $_GET['file'];

        return view('videos::public.create')
            ->with(compact('model','slug','user_id','file'));
    }

    public function edit($slug)
    {
        $model = $this->repository->bySlug($slug);

        $view = 'videos::public.new_edit';

        return view($view)
            ->with(compact('model'));
    }

        /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Videos\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $video = $this->repository->create($request->all());

       // return $this->redirect($request, $video);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Videos\Models\Video            $video
     * @param \TypiCMS\Modules\Videos\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Video $video, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $video);
    }

    public function json_labels($slug, TagInterface $tags)
    {
        $model = $this->repository->bySlug($slug);

        $video_tags = array();

        if(!empty($tags->allBy("video_id", $model->id))) {
          foreach ($tags->allBy("video_id", $model->id) as $tag) {

            $video_tags[] = array(
              'id' => $tag->id,
              'type' => $tag->type,
              'name' => $tag->title,
              'link' => $tag->link,
              'start' => $tag->start,
              'end' => $tag->end,
              'data' => $tag->image,
              'svg' => $tag->svg,
              'left' => $tag->left,
              'top' => $tag->top,
              'width' => $tag->width,
              'height' => $tag->height
            );
          }

          // Сортируем массив
          $video_start = array();
          foreach ($video_tags as $key => $v) {
            $video_start[$key] = $v['start'];
          }

          array_multisort($video_start, SORT_NUMERIC, $video_tags);

          echo json_encode($video_tags);
        } else {
          echo "";
        }
    }

    public function delete($slug)
    {   
        $video = $this->repository->bySlug($slug);
        $deleted = $this->repository->delete($video);

        return redirect('en/video');
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
