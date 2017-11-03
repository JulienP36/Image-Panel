#!/usr/bin/env php
<?php
// imagepanel.php for  in /home/julienp36/poitre_j/DPHP/ImagePanel/poitre_j
// 
// Made by POITREAU Julien
// Login   <poitre_j@etna-alternance.net>
// 
// Started on  Fri Nov  3 10:00:19 2017 POITREAU Julien
// Last update Fri Nov  3 20:40:07 2017 POITREAU Julien
//

function is_url($url)
{
  $pattern = '|^http(s)?://[a-z0-9]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i';
  if (preg_match($pattern, $url) > 0)
    return (true);
  else
    return (false);
}

function option_is_valid($option)
{
  $length = strlen($option);
  $c = 1;
  while ($c < $length)
    {
      if (($option[$c] == 'g') || ($option[$c] == 'j') || ($option[$c] == 'l'))
	$ok = 1;
      else if (($option[$c] == 'n') || ($option[$c] == 'N'))
	$ok = 1;
      else if (($option[$c] == 's') || ($option[$c] == 'p'))
	$ok = 1;
      else
	return (false);
      ++$c;
    }
  return (true);
}
$g = 0;
$j = 0;
$l = 0;
$n = 0;
$N = 0;
$p = 0;
$s = 0;
$validate = 1;

$option = $argv[1];
if ($option[0] == '-')
  {
    if (option_is_valid($option))
      {
	echo "Option(s) valide(s)\n";
	$length = strlen($option);
	$cntr = 1;
	while ($cntr < $length)
	  {
	    if ($option[$cntr] == 'g')
	      $g = 1;
	    else if ($option[$cntr] == 'j')
	      $j = 1;
	    else if ($option[$cntr] == 'l')
	      $l = 1;
	    else if ($option[$cntr] == 'n')
	      $n = 1;
	    else if ($option[$cntr] == 'N')
	      $N = 1;
	    else if ($option[$cntr] == 'p')
	      $p = 1;
	    else if ($option[$cntr] == 's')
	      $s = 1;
	    ++$cntr;
	  }
      }
    else
      {
	$validate = 0;
	echo "Options(s) invalide(s)\n";
      }
$counter = 2;
}
else
  {
    echo "Pas d'options\n";
    $counter = 1;
  }
echo "g:".$g." j:".$j." l:".$l." n:".$n." N:".$N." p:".$p." s:".$s."\n";
if ($l == 1)
  {
    if (is_numeric($argv[2]) == false)
      {
	echo "Erreur: l'argument ".$argv[2]."n'est pas un nombre\n";
	$validate = 0;
      }
    $max_images_amount = $argv[2];
    $counter = 3;
  }
if ($validate == 1)
while ($counter < $argc)
  {
    $ok = 0;
    if (file_exists($argv[$counter]) == true)
      {
	if (is_dir($argv[$counter]) == true)
	  {
	    echo "Erreur: ".$argv[$counter]." est un répertoire\n";
	    return (0);
	  }
	else if (is_readable($argv[$counter]) == false)
	  {
	    echo "Erreur: ".$argv[$counter]." est interdit à la lecture\n";
	    return (0);
	  }
	$ok = 1;
      }
    $url = $argv[$counter];
    if (is_url($url))
      {
	$url_headr = get_headers($url);
      }
    else
      {
	$ok = 0;
	$url_headr[0] = "404";
      }
    if (($url_headr[0] == "404") && ($ok == 0))
      echo "Erreur: Pas de fichier ou d'url valide : ".$argv[$counter]."\n";
    else
      $ok = 1;
    if ($ok == 1)
      {
	$content = file_get_contents($argv[$counter]);
	preg_match_all("/(?<=src=\")([^\"])+(png|jpg|gif)/",$content, $images);
	if ($s == 1)
	  {
	    sort($images[0]);
	  }
	$length = count($images[0]);
	if ($l == 0)
	  {
	    $max_images_amount = $length;
	  }
	$counter1 = 0;
	$canvas = imagecreatetruecolor(800, 800);
	$x_pos = 0;
	$y_pos = 0;
	while (($counter1 < $max_images_amount) && ($counter1 < $length - 1))
	  {
	    $extension = pathinfo($images[0][$counter1]);
	    if ($extension['extension'] == "png")
	      $image = imagecreatefrompng($images[0][$counter1]);
	    if ($extension['extension'] == "jpeg")
	      $image = imagecreatefromjpeg($images[0][$counter1]);
	    if ($extension['extension'] == "gif")
	      $image = imagecreatefromgif($images[0][$counter1]);;
	    imagecopyresized($canvas, $image, $x_pos, $y_pos, 0, 0, 100, 80, imagesx($image), imagesy($image));
	    echo $images[0][$counter1]."\n";
	    ++$counter1;
	    $x_pos += 100;
	    if ($x_pos == 800)
	      {
		$x_pos = 0;
		$y_pos += 100;
	      }
	  }
      }
    if ($g == 1)
      imagegif($canvas, "test".$counter.".gif");
    if ($j == 1)
      imagejpeg($canvas, "test".$counter.".jpeg");
    if ($p == 1)
      imagepng($canvas, "test".$counter.".png");
    ++$counter;
  }
imagedestroy($canvas);
?>