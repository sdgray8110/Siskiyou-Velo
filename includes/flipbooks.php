<?php
$doc = new DOMDocument();
$doc->load( '../includes/flipbooks.xml');
  
$catalogs = $doc->getElementsByTagName("catalog");
foreach( $catalogs as $catalog ) {
  
  $names = $catalog->getElementsByTagName("name");
  $name = $names->item(0)->nodeValue;
  
  $links= $catalog->getElementsByTagName( "link" );
  $link= $links->item(0)->nodeValue;
  
  
  echo '<li><a target="_blank" href="'.$link.'">'.$name.'</a>';
}
?> 