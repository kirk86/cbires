<?php

/**
 * @author Mario Shtika
 * @copyright 2013
 */

function CrawlImagesFromUrl($url)
{
	// add http if not exists
	$url = addhttp($url);
	
	// extract domain
	$parse_url = parse_url($url);
	$domain = $parse_url['host'];
	
	// add http after domain
	$url = addhttp($domain);
	
	// Create DOM from URL or file 
	$html = file_get_html($url); //(it supports invalid html)

	// Find all images 
	foreach($html->find('img') as $element) 
	{
		if (false !== strpos($element,'://'))
		{
			$images[] = $element->src;
		}
		else
		{
			$images[] = $url."/".$element->src;
		}
	}
	
	if(isset($images))
	{
		return $images;
	}
}

function addhttp($url)
{
    if (!preg_match("~^(?:f|ht)tps?://~i", $url))
	{
        $url = "http://" . $url;
    }
    return $url;
}