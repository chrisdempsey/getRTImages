<?php
/* 
 * getRTImages
 *
 * @author YJ Tso
 * @copyright Copyright 2013, YJ Tso
 *
 * Returns values from html img elements in a specified TV
 * and formats based on &tpl Chunk. 
 *
 * [[getRTImages]]
 * // nothing. &tv property is required
 *
 * Where [[*myTV]] contains:
 * <div>
 * <p><img src="this.png" alt="png" data-index="2"></p>
 * <a><img src="that.jpg"></a>
 * <img src="first.gif" data-index="1">
 * </div>
 *
 * [[getRTImages?tv=`myTV`]]
 * // a php array
 * 
 * [[getRTImages?tv=`myTV`&tpl=`myTpl`]]
 * Where [[$myTpl]] contains: [[+src]]"[[+alt]]"
 * // that.jpg""
 * first.gif""
 * this.png"png"
 */
$id = $modx->getOption('id',$scriptProperties,$modx->resource->id);
$tv = $modx->getOption('tv',$scriptProperties,null);
$tpl = $modx->getOption('tpl',$scriptProperties,null);
$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,PHP_EOL);
$sort = $modx->getOption('sort',$scriptProperties,'ASC');
$indexAttr = $modx->getOption('indexAttr',$scriptProperties,'data-index');
$limit = $modx->getOption('limit',$scriptProperties,10);

//fail silently if no TV specified, or less likely, ID is empty
if (!$tv || empty($tv) || empty($id)) return; 

//dump results if no tpl
$dump = (!$tpl || empty($tpl)) ? true : false;

//get resource
$res = $modx->getObject('modResource', $id);

//check it
if (!$res) return;

//get TV
$html = $res->getTVValue($tv);

//if empty, escape
if (!$html || empty($html)) return;

//extract images
$doc = new DOMDocument();
$doc->loadHTML($html);
$images = $doc->getElementsByTagName('img');

//if no image elements, return nothing
if ($images->length === 0) return;

//get attributes
foreach ($images as $image) {
    
     $items[] = array(
        'index' => $image->getAttribute($indexAttr),
        'src' => $image->getAttribute('src'),
        'alt' => $image->getAttribute('alt'),
        'title' => $image->getAttribute('title'),
    );

}

//if this isn't an array then something went wrong
if (!is_array($items)) return;

//sort
if (strtoupper($sort) === 'ASC') asort($items);
if (strtoupper($sort) === 'DESC') arsort($items);
if (strtolower($sort) === 'natural') natsort($items);
if (strtoupper($sort) === 'RAND' || strtolower($sort) === 'random') shuffle($items);

//output
if ($dump) return print_r($items);

$i = 0;
foreach ($items as $item) {
    if ($i == $limit) break;
    $output[] = $modx->getChunk($tpl,$item); 
    $i++;
}

return implode($outputSeparator,$output);
