<?php

require_once 'header.php';
require_once 'main.php';

$relUrl = '/phpSiteNews';


?>
<br />
<div class="container" style="width:500px;margin-left:auto;margin-right:auto;">
  <div class="form-group" style="border:1px solid #ddd8d8;border-radius:10px;padding:15px 25px;">
    <h4>Vous êtes sur votre compte "<?php if(isset($account_enabled)) { echo $account_enabled; } ?>"</h4>
    <p>Vous êtes : <span class="profil"><strong><?php if (isset($account_name)) { echo $account_name; } ?></strong></span></p>
    <p>Votre email est : <span class="profil"><strong><?php if (isset($account_email)) { echo $account_email; } ?></strong></span></p>
    <p>Vous êtes inscrit le : <span class="profil"><strong><?php if (isset($confirmed_at)) { echo $confirmed_at; } ?></strong></span></p>
  </div>
</div>
<?php


require_once 'footer.php';

?>
