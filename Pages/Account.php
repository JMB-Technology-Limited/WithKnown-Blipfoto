<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\Blipfoto\Pages {

        /**
         * Default class to serve the homepage
         */
        class Account extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                /*if ($blipfoto = \Idno\Core\site()->plugins()->get('Blipfoto')) {
                    $oauth_url = $blipfoto->getAuthURL();
                }*/
                $oauth_url = \Idno\Core\site()->config()->getDisplayURL() . 'blipfoto/auth';
                $t = \Idno\Core\site()->template();
                $body = $t->__(array('oauth_url' => $oauth_url))->draw('account/blipfoto');
                $t->__(array('title' => 'Blipfoto', 'body' => $body))->drawPage();
            }

            function postContent() {
                $this->gatekeeper(); // Logged-in users only
                if (($this->getInput('remove'))) {
                    $user = \Idno\Core\site()->session()->currentUser();
                    $user->blipfoto = array();
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your Blipfoto settings have been removed from your account.');
                }
                $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'account/blipfoto/');
            }

        }

    }
