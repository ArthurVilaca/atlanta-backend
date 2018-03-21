<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Page extends Model
{
    protected $fillable = [
        'name',
        'url',
        'status',
        'client_id',
        'file',
    ];

    public function getPagesByIdUser($userID)
    {
        $pages = DB::table('pages')
            ->select('id', 'name', 'url', 'file', 'status', 'client_id')
            ->where('client_id', $userID)
            ->get();

        return $pages;
    }

    public function getComponentsPages($pageID)
    {
        $objectReturn = new \stdClass();
        $component = DB::table('components')
            ->select('components.id', 'components.name', 'components.label')
            ->leftJoin('component_pages', 'component_pages.component_id', '=', 'components.id')
            ->leftJoin('pages', 'component_pages.page_id', '=', 'pages.id')
            ->where('pages.id', $pageID)
            ->get();
                    
        return $component;
    }

    public function getConfigComponentPage($componentID)
    {
        $configComponent = DB::table('config_components')
            ->where('component_id', $componentID)
            ->first();
            /*
            DB::table('pages')
            ->join('component_pages', 'component_pages.page_id', '=', 'pages.id')
            ->join('components', 'component_pages.component_id', '=', 'components.id')
            ->join('config_components', 'components.id', '=', 'config_components.component_id')
            ->where('pages.id', $pageID)
            ->get();
            */

        return $configComponent;
    }
}
