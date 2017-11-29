# Image-Panel

Le script fonctionne de la manière suivante :

php imagepanel.php [options] lien1 [lien2 [...]] base

    Le script affiche les images qu'il aura trouvé sur la sortie standard sous forme de mosaïque.

Les options sont toujours placées avant les liens :

Option 	Description
-g      La ou les images générées doivent être en GIF (.GIF ou .gif)
-j 	    La ou les images générées doivent être en JPEG (.JPEG, .jpeg, .JPG ou .jpg)
-l 	    L'argument suivant est le nombre maximum d'images incrustées dans la méta-image
-n 	    Afficher sous les images le nom de celles-ci (sans l'extension)
-N 	    Afficher sous les images le nom de celles-ci (avec l'extension)
-p 	    La ou les images générées doivent être en PNG (.PNG ou .png)
-s 	    Trier les images par ordre alphabétique
