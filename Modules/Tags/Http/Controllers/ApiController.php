<?php

namespace TypiCMS\Modules\Tags\Http\Controllers;

use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Tags\Models\Tag;
use TypiCMS\Modules\Tags\Repositories\TagInterface as Repository;

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
    public function store()
    {
        $model = $this->repository->create(Request::all());
        $error = $model ? false : true;

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
    public function update()
    {
        $updated = $this->repository->update(Request::all());

        return response()->json([
            'error' => !$updated,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \TypiCMS\Modules\Tags\Models\Tag $tag
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tag $tag)
    {
        $deleted = $this->repository->delete($tag);

        return response()->json([
            'error' => !$deleted,
        ]);
    }

    public function upload_file(Request $request) {
        if (Request::file('image')->isValid()) {
            $extension = Request::file('image')->getClientOriginalExtension();
            $fileName = rand(11111,99999).'.'.$extension;
            $destinationPath = 'uploads/tags';
            $path = Request::file('image')->move($destinationPath, $fileName);
            
            echo $fileName;
        } else {
            return false;
        }
    }

    public function spfng_uri_encode() {
        $id = rand(1,100);
        $strtotime = "now";

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
      echo $hash;
    }
}
