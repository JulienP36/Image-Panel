#!/usr/bin/env php
<?php
// imagepanel.php for  in /home/julienp36/poitre_j/DPHP/ImagePanel/poitre_j
// 
// Made by POITREAU Julien
// Login   <poitre_j@etna-alternance.net>
// 
// Started on  Fri Nov  3 10:00:19 2017 POITREAU Julien
// Last update Sat Nov  4 10:07:13 2017 POITREAU Julien
//

function find_width($number)
{
  $subnumber = intval(sqrt($number - 1)) + 1;
  while (intval(sqrt($number)) != $subnumber)
    {
      ++$number;
    }
  return (sqrt($number));
}

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
while ($counter < $argc - 1)
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
	$content = "";
	$content = file_get_contents($argv[$counter]);
	if ($content == "")
	  {
	    echo "Erreur: Impossible de récupérer le contenu...\n";
	    return (0);
	  }
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
	$text_color = imagecolorallocate($canvas , 255, 255, 255);
	while (($counter1 < $max_images_amount) && ($counter1 < $length - 1))
	  {
	    $extension = pathinfo($images[0][$counter1]);
	    if ($n == 1)
	      preg_match('/([^\/]+)(?=\.\w+$)/', $images[0][$counter1], $name);
	    if ($N == 1)
	      preg_match('/([^\/][\d\w\.]+)$(?<=(?:.jpeg)|(?:.png)|(?:.gif))/', $images[0][$counter1], $name);
	    if ($extension['extension'] == "png")
	      $image = imagecreatefrompng($images[0][$counter1]);
	    if ($extension['extension'] == "jpeg")
	      $image = imagecreatefromjpeg($images[0][$counter1]);
	    if ($extension['extension'] == "gif")
	      $image = imagecreatefromgif($images[0][$counter1]);
	    if (($l == 1) && ($max_images_amount < $length))
	      $new_length = (800 / find_width($max_images_amount));
	    else
	      $new_length = (800 / find_width($length));
	    if (is_bool($image))
	      {
		echo "Erreur: Impossible de créer l'image...\n";
		return (0);
	      }
	    if (imagesx($image) >= imagesy($image))
	      {
		$reduction = (($new_length * 100) / imagesx($image));
		$new_heigth = ((imagesy($image) * $reduction) / 100);
	      }
	    else
	      {
		$reduction = (($new_length * 100) / imagesy($image));
		$new_heigth = ((imagesx($image) * $reduction) / 100);
	      }
	    imagecopyresized($canvas, $image, $x_pos, $y_pos, 0, 0, $new_length, $new_heigth, imagesx($image), imagesy($image));
	    if (($n ==1) || ($N == 1))
	      imagestring($canvas, 1, $x_pos, $y_pos, $name[0],$text_color);
	    echo $images[0][$counter1]."\n";
	    ++$counter1;
	    $x_pos += $new_length;
	    if ($x_pos >= 800)
	      {
		$x_pos = 0;
		$y_pos += $new_length;
	      }
	  }
      }
    if ($g == 1)
      imagegif($canvas, $argv[$argc - 1].($counter - 1).".gif");
    if ($j == 1)
      imagejpeg($canvas, $argv[$argc - 1].($counter - 1).".jpeg");
    if ($p == 1)
      imagepng($canvas, $argv[$argc - 1].($counter - 1).".png");
    ++$counter;
    if ($counter < $argc - 1)
      imagedestroy($canvas);
  }
?>