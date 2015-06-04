<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\Blipfoto\Pages {

        /**
         * Default class to serve the homepage
         */
        class Admin extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->adminGatekeeper(); // Admins only
                $t = \Idno\Core\site()->template();
                $body = $t->draw('admin/blipfoto');
                $t->__(array('title' => 'Blipfoto', 'body' => $body))->drawPage();
            }

            function postContent() {
                $this->adminGatekeeper(); // Admins only
                $client_id = trim($this->getInput('client_id'));
                $client_secret = trim($this->getInput('client_secret'));
                \Idno\Core\site()->config->config['blipfoto'] = array(
                    'client_id' => $client_id,
                    'client_secret' => $client_secret
                );
                \Idno\Core\site()->config()->save();
                \Idno\Core\site()->session()->addMessage('Your Blipfoto application details were saved.');
                $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'admin/blipfoto/');
            }

        }

    }