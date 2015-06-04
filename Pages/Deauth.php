<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\Blipfoto\Pages {

        /**
         * Default class to serve the homepage
         */
        class Deauth extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($blipfoto = \Idno\Core\site()->plugins()->get('Blipfoto')) {
                    if ($user = \Idno\Core\site()->session()->currentUser()) {
                        if ($remove = $this->getInput('remove')) {
                            if (is_array($user->blipfoto)) {
                                if (array_key_exists($remove, $user->blipfoto)) {
                                    unset($user->blipfoto[$remove]);
                                }
                            } else {
                                $user->blipfoto = false;
                            }
                        } else {
                            $user->blipfoto = false;
                        }
                        $user->save();
                        \Idno\Core\site()->session()->refreshSessionUser($user);
                        if (!empty($user->link_callback)) {
                            error_log($user->link_callback);
                            $this->forward($user->link_callback); exit;
                        }
                    }
                }
                $this->forward($_SERVER['HTTP_REFERER']);
            }

            function postContent() {
                $this->getContent();
            }

        }

    }