<?php

namespace TypiCMS\Modules\Tags\Models;

use TypiCMS\Modules\Core\Models\BaseTranslation;

class TagTranslation extends BaseTranslation
{
    /**
     * get the parent model.
     */
    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Tags\Models\Tag', 'tag_id');
    }
}
