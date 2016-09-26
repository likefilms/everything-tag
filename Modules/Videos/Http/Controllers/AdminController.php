<?php

namespace TypiCMS\Modules\Videos\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Videos\Http\Requests\FormRequest;
use TypiCMS\Modules\Videos\Models\Video;
use TypiCMS\Modules\Videos\Repositories\VideoInterface;
use Illuminate\Http\Request;

class AdminController extends BaseAdminController
{
    public function __construct(VideoInterface $video)
    {
        parent::__construct($video);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $models = $this->repository->all([], true);
        app('JavaScript')->put('models', $models);

        return view('videos::admin.index');
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $model = $this->repository->getModel();

        $last = $this->repository->latest(1);
        $id = $last[0]->id + 1;
        $slug = $this->spfng_uri_encode($id,'now');

        $user_id = $request->user()->id;

        return view('videos::admin.create')
            ->with(compact('model','slug','user_id'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Videos\Models\Video $video
     *
     * @return \Illuminate\View\View
     */
    public function edit(Video $video)
    {
        return view('videos::admin.edit')
            ->with(['model' => $video]);
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

        return $this->redirect($request, $video);
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

        $request->all();

        return $this->redirect($request, $video);
    }

    public function spfng_uri_encode($id = 0, $strtotime = '1970-01-01 00:00:00') {
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
