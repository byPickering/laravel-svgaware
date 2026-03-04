<?php 
namespace Pickering\SvgAware;

use Illuminate\Support\Facades\File;
use DOMDocument;
use DOMElement;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class SvgAwareService
{

    function directiveCall($expression)
    {
        $args = explode(",",$expression,2);
        $attr = [];

        if(isset($args[1]))
        {
            if(!$this->validateDirectiveAttributes($args[1]))
            throw new InvalidArgumentException("Attributes passed to the directive are not in a valid format.");
            
           try {
                $attr = eval("return {$args[1]};");
            } catch (Throwable $e) {
                throw new InvalidArgumentException("Failed to parse directive attributes: " . $e->getMessage(), 0, $e);
            }
            if(!is_array($attr))
            throw new InvalidArgumentException("Attribute compilation failed. Please verify the directive's formatting.");
            
        }

        
        $svgName = trim($args[0]);
        return $this->call($svgName,$attr);
    }

    private function call(string &$name, array &$attributes = [], bool $asElement = false): string | DOMElement
    {
        $fullPath = $this->buildPath($name);
        $svgContent = $this->getContent($fullPath);

        $svgContent = $this->replaceTags($svgContent, $attributes);

        $contextDOM = new DOMDocument();
        $svgElement = $this->convertToElement($svgContent,$contextDOM);

        $this->purgeAttributes($svgElement);
        $this->injectAttributes($svgElement,$attributes);

        
        if($asElement)
        return $svgElement;

        // Return string if return type is un
        return $contextDOM->saveHTML($svgElement);

    }

    function render(string $name, array $attributes = []): string 
    {
        return $this->call($name,$attributes);
    }

    function renderAsElement(string $name, array $attributes = []): DOMElement
    {
        return $this->call($name,$attributes,true);
    }

    private function buildPath(string $name):string 
    {
        $configRoot = config("svgaware.root");
        $configPrepend = config("svgaware.prepend");
        $configAppend = config("svgaware.append");

        return rtrim($configRoot,"/")."/".$configPrepend.$name.$configAppend;
    }

    private function getContent(string $fullPath): string
    {
        if(!File::exists($fullPath))
        throw new RuntimeException("Could not find file at: \"{$fullPath}\". Please verify the path exists and is readable.");

        return File::get($fullPath);
    }

    private function replaceTags(string $svgContent, array &$attributes):string
    {
        $tagMatches = [];
        preg_match_all("/\{([a-zA-Z0-9_]+)\}/",$svgContent,$tagMatches);
        $tagMatchesClean = $tagMatches[1] ?? [];

        if(count($tagMatchesClean) == 0)
        return $svgContent;

        foreach($tagMatchesClean as $index => $tag)
        {
            if(!isset($attributes[$tag]))
            continue;

            $svgContent = str_replace("{{$tag}}"," {$attributes[$tag]} ",$svgContent);
            unset($attributes[$tag]);
        }

        return $this->purgeUnusedTags($svgContent, $tagMatches[0]);
    }

    private function purgeUnusedTags(string $svgContent, array $tagMatches)
    {
        if(count($tagMatches) == 0)
        return $svgContent;

        foreach($tagMatches as $index => $tag)
        {
            $svgContent = str_replace($tag,"",$svgContent);
        }

        return $svgContent;
    }

    private function convertToElement(string $svgContent, DOMDocument &$contextDOM): DOMElement
    {
        libxml_use_internal_errors(true);
        $isConverted = $contextDOM->loadHTML("<div>{$svgContent}</div>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $libErrors = libxml_get_errors();
        libxml_clear_errors();

        if(!$isConverted)
        throw new RuntimeException("Unable to convert SVG to a valid element. Please verify the XML structure.");

        foreach($libErrors as $error)
        {
            if($error->code == 76 || $error->code == 77 || $error->code == 85)
            throw new RuntimeException("Unable to convert SVG to a valid element due to a syntax error. Please verify the XML structure.");
        }

        $svgElements = $contextDOM->getElementsByTagName("svg");

        if($svgElements->count() === 0 )
        throw new RuntimeException("SVG parsing failed: No root <svg> element detected in the provided content.");

        return $svgElements->item(0);

    }

    private function purgeAttributes(&$svgElement): void 
    {
        if (!config('svgaware.purge', true))
        return;
        
        foreach (config('svgaware.purge_list', []) as $attribute) 
        {
            if ($svgElement->hasAttribute($attribute)) 
            $svgElement->removeAttribute($attribute);
        }
    }

    private function injectAttributes($svgElement, array $attributes): void
    {
        foreach ($attributes as $key => $value) 
        $svgElement->setAttribute($key, (string) $value);
    }


    private function validateDirectiveAttributes(string $attr): bool 
    {
        $attr = trim($attr);
        if(!str_starts_with($attr,"[") && !str_ends_with($attr,"]"))
        return false;

        return true;
    }
}