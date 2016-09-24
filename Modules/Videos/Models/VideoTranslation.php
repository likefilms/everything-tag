<?php

namespace TypiCMS\Modules\Videos\Models;

use TypiCMS\Modules\Core\Models\BaseTranslation;

class VideoTranslation extends BaseTranslation
{
    /**
     * get the parent model.
     */
    public function owner()
    {
        return $this->belongsTo('TypiCMS\Modules\Videos\Models\Video', 'video_id');
    }
}
