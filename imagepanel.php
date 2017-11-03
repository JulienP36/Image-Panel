#!/usr/bin/env php
<?php
// imagepanel.php for  in /home/julienp36/poitre_j/DPHP/ImagePanel/poitre_j
// 
// Made by POITREAU Julien
// Login   <poitre_j@etna-alternance.net>
// 
// Started on  Fri Nov  3 10:00:19 2017 POITREAU Julien
// Last update Fri Nov  3 14:56:39 2017 POITREAU Julien
//

$option = $argv[1];

$counter = 1;
while ($counter < $argc)
  {
    $ok = 0;
    if (file_exists($argv[$counter]) == true)
      $ok = 1;
    $url = $argv[$counter];
    $url_header = @get_headers($url);
    if ((!$url_header || $url_header[0] == '404') && ($ok == 0))
      echo "Erreur: Pas de fichier ou d'url valide : ".$argv[$counter]."\n";
    else
      $ok = 1;
    if ($ok == 1)
      {
	$content = file_get_contents($argv[$counter]);
	preg_match_all("/(?<=src=\")([^\"])+(png|jpg|gif)/",$content, $images);
	$length = count($images[0]);
	$counter1 = 0;
	while ($counter1 < $length)
	  {
	    $image = imagecreatefrompng($images[0][$counter1]);
	    echo $images[0][$counter1]."\n";
	    ++$counter1;
	  }
      }
    ++$counter;
  }
imagepng($image, "test.png");
?>