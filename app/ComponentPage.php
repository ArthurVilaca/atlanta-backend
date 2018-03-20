<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ComponentPage extends Model
{
    protected $fillable = [
        'component_id',
        'page_id',
    ];

    public function getComponentPage($pageID)
    {
        $pageComponent = DB::table('component_pages')
            ->where('page_id', $pageID)
            ->first();

        return $pageComponent;
    }

    public function getPageComponent($componentID)
    {
        $pageComponent = DB::table('component_pages')
            ->where('component_id', $componentID)
            ->first();

        return $pageComponent;
    }
}
