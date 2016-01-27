<?php

    namespace IdnoPlugins\InstagramShim {

        class Main extends \Idno\Common\Plugin
        {

            function registerPages()
            {
            }

            function registerEventHooks()
            {
                \Idno\Core\Idno::site()->addEventHook('indiepub/post/start', function (\Idno\Core\Event $evt) {
                    $data = $evt->data();
                    $page = $data['page'];
                    // unfortunately micropub and Known use "syndication" to mean two different things, so
                    // we'll stash the incoming syndication value somewhere safe.
                    $syndication = $page->getInput('syndication');
                    if (!empty($syndication)) {
                        $page->setInput('igshim_syndication', $syndication);
                    }
                });
                \Idno\Core\Idno::site()->addEventHook('indiepub/post/success', function (\Idno\Core\Event $evt) {
                    $data = $evt->data();
                    $page = $data['page'];
                    $object = $data['object'];

                    $syndication = $page->getInput('igshim_syndication');
                    if (!empty($syndication)) {
                        if (!is_array($syndication)) {
                            $syndication = [$syndication];
                        }
                        foreach ($syndication as $url) {
                            if (preg_match('/https?:\/\/(?:www\.)?instagram.com\/p\/([a-zA-Z0-9_\-]+)/i', $url, $matches)) {
                                $object->setPosseLink('instagram', $url, 'kylewmahan', $matches[1], 'kylewmahan');
                            }
                        }
                    }
                });
            }
        }
    }
