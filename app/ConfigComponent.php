<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigComponent extends Model
{
    protected $fillable = [
        'name',
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
}
