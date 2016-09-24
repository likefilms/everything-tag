<?php

namespace TypiCMS\Modules\Videos\Repositories;

use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

class EloquentVideo extends RepositoriesAbstract implements VideoInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
