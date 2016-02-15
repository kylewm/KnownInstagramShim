<?php

    namespace IdnoPlugins\InstagramShim {

        class Main extends \Idno\Common\Plugin
        {

            const INSTAGRAM_REGEX = '/https?:\/\/(?:www\.)?instagram.com\/p\/([a-zA-Z0-9_\-]+)/i';

            function registerPages()
            {
                // Auth URL
                \Idno\Core\Idno::site()->addPageHandler('account/instagramshim', '\IdnoPlugins\InstagramShim\Pages\Account');

                // Template extensions
                \Idno\Core\Idno::site()->template()->extendTemplate('account/menu/items', 'account/instagramshim/menu');

            }

            function registerEventHooks()
            {
                \Idno\Core\Idno::site()->addEventHook('indiepub/post/start', function (\Idno\Core\Event $evt) {
                    $data = $evt->data();
                    $page = $data['page'];
                    // unfortunately micropub and Known use "syndication" to mean two different things, so
                    // we'll stash the incoming syndication value somewhere safe.
                    $syndication = $page->getInput('syndication');
                    \Idno\Core\Idno::site()->logging()->log("started indiepub with syndication input: $syndication", LOGLEVEL_DEBUG);
                    if (!empty($syndication)) {
                        $page->setInput('igshim_syndication', $syndication);
                    }
                });
                \Idno\Core\Idno::site()->addEventHook('indiepub/post/success', function (\Idno\Core\Event $evt) {
                    $data = $evt->data();
                    $page = $data['page'];
                    $object = $data['object'];
                    $syndication = $page->getInput('igshim_syndication');
                    \Idno\Core\Idno::site()->logging()->log("finished indiepub with syndication input: $syndication", LOGLEVEL_DEBUG);

                    $changed = false;
                    if (!empty($syndication)) {
                        if (!is_array($syndication)) {
                            $syndication = [$syndication];
                        }
                        foreach ($syndication as $url) {
                            if (preg_match(self::INSTAGRAM_REGEX, $url, $matches)) {
                                $user = \Idno\Core\Idno::site()->session()->currentUser();
                                $account = '';
                                if (!empty($user) && !empty($user->instagram_account)) {
                                    $account = $user->instagram_account;
                                }
                                // remove low-quality posse links
                                unset($object->posse['www.instagram.com']);
                                unset($object->posse['instagram.com']);
                                $object->setPosseLink('instagram', $url, $account, $matches[1], $account);
                                $changed = true;
                            }
                        }
                    }
                    if ($changed) {
                        $object->save();
                    }
                });
            }
        }
    }
