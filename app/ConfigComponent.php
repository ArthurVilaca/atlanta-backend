<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ConfigComponent extends Model
{
    protected $fillable = [
        'name_config',
        'text1',
        'text2',
        'text3',
        'text4',
        'text5',
        'image1',
        'image2',
        'image3',
        'background_color',
        'min_height',
        'can_edit_background_image',
        'can_edit_background_color',
        'component_id'
    ];

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
