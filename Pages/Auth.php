<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\Blipfoto\Pages {

        /**
         * Default class to serve the homepage
         */
        class Auth extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($blipfoto = \Idno\Core\site()->plugins()->get('Blipfoto')) {
                    $login_url = $blipfoto->getAuthURL();
                    if (!empty($login_url)) {
                        $this->forward($login_url); exit;
                    }
                }
                $this->forward($_SERVER['HTTP_REFERER']);
            }

            function postContent() {
                $this->getContent();
            }

        }

    }