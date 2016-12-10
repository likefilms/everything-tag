<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Tags\Http\Requests\FormRequest;
use TypiCMS\Modules\Tags\Models\Tag;
use TypiCMS\Modules\Tags\Repositories\TagInterface;
use Illuminate\Http\Request;

class AdminController extends BaseAdminController
{
    public function __construct(TagInterface $tag)
    {
        parent::__construct($tag);
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

        return view('tags::admin.index');
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

        if(isset($last[0])) {
            $id = $last[0]->id + 1;
        } else {
            $id = 1;
        }

        $slug = $this->spfng_uri_encode($id,'now');

        $user_id = $request->user()->id;

        return view('tags::admin.create')
            ->with(compact('model','slug','user_id'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Tags\Models\Tag $tag
     *
     * @return \Illuminate\View\View
     */
    public function edit(Tag $tag)
    {
        return view('tags::admin.edit')
            ->with(['model' => $tag]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Tags\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $tag = $this->repository->create($request->all());

        return $this->redirect($request, $tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Tags\Models\Tag            $tag
     * @param \TypiCMS\Modules\Tags\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Tag $tag, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $tag);
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
