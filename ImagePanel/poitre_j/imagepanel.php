#!/usr/bin/env php
<?php
// imagepanel.php for  in /home/julienp36/poitre_j/DPHP/ImagePanel/poitre_j
// 
// Made by POITREAU Julien
// Login   <poitre_j@etna-alternance.net>
// 
// Started on  Fri Nov  3 10:00:19 2017 POITREAU Julien
// Last update Fri Nov  3 10:43:48 2017 POITREAU Julien
//

$url = $argv[1];
$url_header = @get_headers($url);

if (!$url_header || $url_header[0] == '404')
  echo "Erreur: nous n'avons pas eu accès à la page ".$argv[1]." Page inexistante ou inatteignable";
else
  {
    $content = file_get_contents($argv[1]);
    preg_match_all("/(?<=src=\")([^\"])+(png|jpg|gif)/",$content, $images);
    $length = count($images[0]);
    $counter = 0;
    while ($counter < $length)
      {
	echo $images[0][$counter]."\n";
	++$counter;
      }
  }
?>