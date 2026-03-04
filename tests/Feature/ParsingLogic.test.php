<?php 
it('injects custom attributes into the SVG tag',function()
{
    $result = $this->svg->render("add",["class" => "fill-green-500"]);
    expect($result)->toEqual(state("add.class"));
});

it('swaps specific tags within the SVG content',function()
{
    $result = $this->svg->render("add.tag",["tag" => "placeholder"]);
    expect($result)->toEqual(state("add.tag"));
});

it('removes all unused tags from the SVG',function()
{
    $result = $this->svg->render("add.tag");
    expect($result)->toEqual(state("add.clean"));
});

it('strips attributes that are defined in the purge list',function()
{
    $result = $this->svg->render("add.tag");
    expect($result)->toEqual(state("add.clean"));
});

it('resolves only the first SVG if multiple elements exist in a file',function()
{
    $result = $this->svg->render("add.multi_svg");
    expect($result)->toEqual(state("add.clean"));
});
