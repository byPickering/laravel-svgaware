<?php
// ./tests/TestCase.php
namespace Tests;
use Orchestra\Testbench\Concerns\WithWorkbench; 
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithWorkbench; 
}
