<?php 
namespace Pickering\SvgAware\View\Components;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Pickering\SvgAware\SvgAwareService;
use Closure;

class SvgAwareComponent extends Component
{
    public function __construct(private string $src) 
    {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return function ($data)
        {
            $attr = $data['attributes']->getAttributes();

            return (new SvgAwareService())->render($this->src,$attr);
        };
    }
}
