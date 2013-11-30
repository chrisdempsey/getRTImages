----------------------------
Addon: getRTImages
----------------------------
Version: 1.2.0-beta1
Since: November 28, 2013
Author: YJ Tso, Sepia River <info@sepiariver.com>
License: GNU GPLv2 (or later at your option)

Returns values from html img elements in a specified TV
and formats based on &tpl Chunk. Useful for slideshows.
Can optionally sort img elements by data-index or another 
specified attribute.

Also includes separate snippet to extract list items.

Thanks for using MODx Revolution.

YJ Tso
yj@modx.com

----------------------------
USAGE
----------------------------

<code>
[[getRTImages]]
// nothing. &tv property is required

Where [[*myTV]] contains:
<div>
<p><img src="this.png" alt="png" data-index="2"></p>
<a><img src="that.jpg"></a>
<img src="first.gif" data-index="1">
</div>

[[getRTImages?tv=`myTV`]]
// a php array
 
[[getRTImages?tv=`myTV`&tpl=`myTpl`]]
Where [[$myTpl]] contains: [[+src]]"[[+alt]]"
// that.jpg""
first.gif""
this.png"png"
</code>