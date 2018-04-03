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

    public function createComponentByArray($array)
    {
       $componentCreate = $this->component->create([
            'name' => $array['name'],
            'label' => $array['label'],
        ]);
        return $componentCreate;
    }

    public function createConfigComponentByArray($array, $componentId)
    {
        $configComponentCreate = $this->configComponent->create([
            'name_config' => (isset($array['name_config'])) ? $array['name_config'] : '',
            'text1' => (isset($array['text1'])) ? $array['text1'] : '',
            'text2' => (isset($array['text2'])) ? $array['text2'] : '',
            'text3' => (isset($array['text3'])) ? $array['text3'] : '',
            'text4' => (isset($array['text4'])) ? $array['text4'] : '',
            'text5' => (isset($array['text5'])) ? $array['text5'] : '',
            'image1' => (isset($array['image1'])) ? $array['image1'] : '',
            'image2' => (isset($array['image2'])) ? $array['image2'] : '',
            'image3' => (isset($array['image3'])) ? $array['image3'] : '',
            'background_color' => (isset($array['background_color'])) ? $array['background_color'] : '',
            'min_height' => (isset($array['min_height'])) ? $array['min_height'] : '',
            'can_edit_background_image' => (isset($array['can_edit_background_image'])) ? $array['can_edit_background_image'] : '',
            'can_edit_background_color' => (isset($array['can_edit_background_color'])) ? $array['can_edit_background_color'] : '',
            'component_id' => $componentId,
        ]);
        return $configComponentCreate;
    }
}
?>