<?php

namespace IdnoPlugins\InstagramShim\Pages
{

    class Account extends \Idno\Common\Page
    {

        function getContent()
        {
            $this->gatekeeper(); // Logged-in users only

            $t = \Idno\Core\site()->template();
            $body = $t->draw('account/instagramshim');
            $t->__(['title' => 'Instagram Shim', 'body' => $body])->drawPage();
        }

        function postContent()
        {
            $this->gatekeeper(); // Logged-in users only
            $user = \Idno\Core\Idno::site()->session()->currentUser();
            $user->instagram_account = $this->getInput('account_id');
            $user->save();
            \Idno\Core\Idno::site()->session()->addMessage('Your Instagram Shim settings have been saved.');
            $this->forward(\Idno\Core\Idno::site()->config()->getDisplayURL() . 'account/instagramshim/');
        }
    }

}