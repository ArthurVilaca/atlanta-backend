<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComponentPage extends Model
{
    protected $fillable = [
        'component_id',
        'page_id',
    ];

    public function getComponentPage($pageID)
    {
        $pageComponent = DB::table('component_pages')->where('page_id', $pageID)->first();

        return $pageComponent;
    }
}
