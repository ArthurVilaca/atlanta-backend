<?php 
namespace App\Service;

use Illuminate\Http\Request;
use App\Component;
use App\ConfigComponent;

class ComponentService extends Service
{
    private $component;
    private $configComponent;

    public function __construct()
    {
        $this->component = new Component();
        $this->configComponent = new ConfigComponent();
    }

    public function createComponent(Request $request)
    {
       $componentCreate = $this->component->create([
            'name' => $request->get('name'),
            'label' => $request->get('label'),
        ]);

        return $componentCreate;
    }

    public function createConfigComponent(Request $request, $componentId)
    {
        $configComponentCreate = $this->configComponent->create([
            'name_config' => $request->get('name_config'),
            'text1' => $request->get('text1'),
            'text2' => $request->get('text2'),
            'text3' => $request->get('text3'),
            'text4' => $request->get('text4'),
            'text5' => $request->get('text5'),
            'image1' => $request->get('image1'),
            'image2' => $request->get('image2'),
            'image3' => $request->get('image3'),
            'background_color' => $request->get('background_color'),
            'min_height' => $request->get('min_height'),
            'can_edit_background_image' => $request->get('can_edit_background_image'),
            'can_edit_background_color' => $request->get('can_edit_background_color'),
            'component_id' => $componentId,
        ]);

        return $configComponentCreate;
    }
}
?>