<?php 
use Pickering\SvgAware\Facades\Svg;

it('renders an SVG string when called via the directive call',function()
{
    $result = $this->svg->directiveCall("add");
    expect($result)->toEqual(state("add.clean"));
});

it('renders an SVG string when called via the render method',function()
{
    $result = $this->svg->render("add");
    expect($result)->toEqual(state("add.clean"));
});

it('renders an SVG string when called via the facade',function()
{
    $result = Svg::render("add");
    expect($result)->toEqual(state("add.clean"));
});

