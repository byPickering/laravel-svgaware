<?php
it('strips whitespace from the icon name when called by a directive',function()
{
    $result = $this->svg->directiveCall('add , ["class" => "fill-green-500"]');
    expect($result)->toEqual(state("add.class"));
});