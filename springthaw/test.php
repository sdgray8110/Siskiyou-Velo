<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="docroot/css/calendar.css" />
<title>Nordstrom Test</title>

<style>
    a, img {border:none; outline:none;}
    #productGrid li {display:block; width:170px; height:330px; margin:5px; float:left;}
    #productGrid li a.thumb {border:1px solid #ccc; display:block; padding:2px;}
    #productGrid li a.thumb img {display:block;}
    #productGrid li p, #productGrid li h5 {font-size:11px; font-family:arial, helvetica, san-serif; margin:0; padding:0;}
    .filters .clear {display:none;}
</style>

</head>

<body>

<p class="filters"><a href="#" rel="docroot/js/nordstromProducts2.json">Filter 1</a> | <a href="#" rel="docroot/js/nordstromProducts3.json">Filter2</a> | <a class="clear" href="#" rel="docroot/js/nordstromProducts.json">Clear Filters</a></p>

<ul id="productGrid"></ul>

<script src="http://ajax.microsoft.com/ajax/jQuery/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="docroot/js/nordstrom.js" type="text/javascript"></script>
</body>
</html>