<?php

$user = \Idno\Core\Idno::site()->session()->currentUser();
$account = $user ? $user->instagram_account : null;

?>

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <?=$this->draw('account/menu')?>
        <h1>Instagram Shim</h1>

    </div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form method="post">
            <label for="account_id">Instagram Username</label>
            This is optional and only used to annotate syndication links with the appropriate account name.
            <input type="text" class="form-control" name="account_id" id="account_id" placeholder="taylorswift" value="<?=$account ? $account : ''?>" />
            <button type="submit" class="btn btn-primary">Save</button>
            <?= \Idno\Core\site()->actions()->signForm('/account/instagramshim/')?>
        </form>
    </div>
</div>
