<?php
/* 
 * getRTListItems
 *
 * @author YJ Tso
 * @copyright Copyright 2013, YJ Tso
 * @package getRTImages
 *
 * Returns values from html li elements in a specified TV
 * and formats based on &tpl Chunk. 
 *
 * [[getRTListItems]]
 * // nothing. &tv property is required
 *
 * Where [[*myTV]] contains:
 * <ul>
 * <li>This key| That value|another value</li>
 * <li>Second item| 2nd value</li>
 * <li>Third item| 3rd value</li>
 * </ul>
 *
 * [[getRTListItems?tv=`myTV`]]
 * // a php array
 * 
 * [[getRTListItems?tv=`myTV`&tpl=`myTpl`]]
 * Where [[$myTpl]] contains: "[[+value.1]] - [[+value.2]] - [[+value.3]]"
 * // "This key - That value - another value"
 * "Second item - 2nd value - "
 * "Third item - 3rd value - "
 *
 * Optionally set &listValueSeparator and &trim properties
 */
$id = $modx->getOption('id',$scriptProperties,$modx->resource->id);
$tv = $modx->getOption('tv',$scriptProperties,null);
$tpl = $modx->getOption('tpl',$scriptProperties,null);
$lvs = $modx->getOption('listValueSeparator',$scriptProperties,'|');
$trim = $modx->getOption('trim',$scriptProperties,true);
$outputSeparator = $modx->getOption('outputSeparator',$scriptProperties,PHP_EOL);
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

//extract list items
$doc = new DOMDocument();
$doc->loadHTML($html);
$items = $doc->getElementsByTagName('li');

//if no list items, return nothing
if ($items->length === 0) return;

//get values
$c=0;
foreach ($items as $item) {
    $values = explode($lvs,$item->nodeValue);
    $v = 1;
    foreach ($values as $val) {
        if ($trim) $val = trim($val);
        $arr[$c]['value.'.$v] = $val;
        $v++;
    }
    $c++;
}

//if this isn't an array then something went wrong
if (!is_array($arr)) return;

//output
if ($dump) return print_r($arr);

$i = 0;
foreach ($arr as $a) {
    if ($i == $limit) break;
    $output[] = $modx->getChunk($tpl,$a); 
    $i++;
}

return implode($outputSeparator,$output);
