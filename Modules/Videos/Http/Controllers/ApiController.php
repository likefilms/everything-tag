<?php

namespace TypiCMS\Modules\Videos\Http\Controllers;

use Illuminate\Support\Facades\Request;
use TypiCMS\Modules\Core\Http\Controllers\BaseApiController;
use TypiCMS\Modules\Videos\Models\Video;
use TypiCMS\Modules\Videos\Repositories\VideoInterface as Repository;

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

    public function analytics() {

        echo 'test';
    }
}
