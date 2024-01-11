<?php

namespace ZakariaTlilani\TextTemplate;

use Laravel\Nova\Fields\Field;

class Template extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'title-template';

    /**
     * @param $category
     * @return TitleTemplate
     */
    public function category($category)
    {
        return $this->withMeta([
            'category' => $category
        ]);
    }

    /**
     * @param $endpoint
     * @return TitleTemplate
     */
    public function endpoint($endpoint)
    {
        return $this->withMeta([
            'endpoint' => $endpoint
        ]);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return void
     */
    protected function fillAttributeFromRequest($request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            foreach (json_decode($request->name) as $key => $item) {
                $name[$key] = implode(' ', $item);
                $tempName[$key] = implode('(^)', $item);
            }
            $model->name = $name;
            $model->temp_name = $tempName;
        } elseif(isset($request->withoutTemplate)) {
            foreach(json_decode($request->withoutTemplate) as $key => $item) {
                $withoutTemplate[$key] = $item;
            }
            $model->name = $withoutTemplate;
        }
    }
}
