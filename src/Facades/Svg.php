<?php 
namespace Pickering\SvgAware\Facades;

use Pickering\SVGAware\SvgAwareService;
use Illuminate\Support\Facades\Facade;

class Svg extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return SvgAwareService::class;
    }
}