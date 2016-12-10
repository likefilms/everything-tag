<?php

namespace TypiCMS\Modules\Tags\Repositories;

use Illuminate\Database\Eloquent\Model;
use TypiCMS\Modules\Core\Repositories\RepositoriesAbstract;

class EloquentTag extends RepositoriesAbstract implements TagInterface
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
