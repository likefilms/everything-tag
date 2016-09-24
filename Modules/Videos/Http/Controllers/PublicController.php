<?php

namespace TypiCMS\Modules\Videos\Http\Controllers;


use Illuminate\Http\Request;
use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Videos\Repositories\VideoInterface;

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
    public function show($slug)
    {
        $model = $this->repository->bySlug($slug);

        return view('videos::public.show')
            ->with(compact('model'));
    }

    public function oembed($slug)
    {
        $model = $this->repository->bySlug($slug);

        return view('videos::public.oembed')
            ->with(compact('model'));
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

        return view('videos::public.edit')
            ->with(compact('model'));
    }

    public function json_labels($slug)
    {
        $model = $this->repository->bySlug($slug);

        echo $model->labels;
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
