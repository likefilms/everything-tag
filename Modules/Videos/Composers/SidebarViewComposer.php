<?php

namespace TypiCMS\Modules\Videos\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('global.menus.content'), function (SidebarGroup $group) {
            $group->addItem(trans('videos::global.name'), function (SidebarItem $item) {
                $item->id = 'videos';
                $item->icon = config('typicms.videos.sidebar.icon');
                $item->weight = config('typicms.videos.sidebar.weight');
                $item->route('admin::index-videos');
                $item->append('admin::create-video');
                $item->authorize(
                    Gate::allows('index-videos')
                );
            });
        });
    }
}
