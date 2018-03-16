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
            ->where('client_id', $userID)
            ->get();

        return $pages;
    }

    public function getComponentsPages()
    {
        $components = DB::table('pages')
            ->join('component_pages', 'component_pages.page_id', '=', 'pages.id')
            ->join('components', 'component_pages.component_id', '=', 'components.id')
            ->join('config_components', 'components.id', '=', 'config_components.component_id')
            ->get();
        
        return $components;
    }
}
