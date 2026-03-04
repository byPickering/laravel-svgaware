<?php
it('throws an exception if the SVG file cannot be found',function()
{
    $this->expectException(\RuntimeException::class);
    $this->svg->render("does_not_exist");
});

it('throws an exception if the SVG source is malformed',function()
{
    $this->expectException(\RuntimeException::class);
    $r = $this->svg->render("add.malformed");
});

it('throws an exception if the SVG source does not contain root svg tag',function()
{
    $this->expectException(\RuntimeException::class);
    $this->svg->render("add.no_root");
});

it('throws an exception if the directive expression contains invalid attributes',function()
{
    $this->expectException(\InvalidArgumentException::class);
    $this->svg->directiveCall("add , [class => fill-red-500]");
});

it('throws an exception if the attributes passed to the directive is not an array',function()
{
    $this->expectException(\InvalidArgumentException::class);
    $this->svg->directiveCall("add , class => fill-red-500");

});

