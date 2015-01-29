<?php
   echo '<h1>Répondre à un message privé</h1><br /><br />';

   $dest = (int) $_GET['id_expediteur'];
   ?>
   <form method="post" action="repondre_mp.php?action=repondremp&amp;dest=<?php echo $dest ?>" name="formulaire">
   <p>
   <label for="titre">Titre : </label><input type="text" size="80" id="titre" name="titre" />
   <br /><br />
   <textarea cols="80" rows="8" id="message" name="message"></textarea>
   <br />
   <input type="submit" name="submit" value="Envoyer" />
   <input type="reset" name="Effacer" value="Effacer"/>
   </p></form>
