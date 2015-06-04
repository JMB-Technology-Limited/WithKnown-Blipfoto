<?php

    /**
     * Plugin administration
     */

    namespace IdnoPlugins\Blipfoto\Pages {

        /**
         * Default class to serve the homepage
         */
        class Callback extends \Idno\Common\Page
        {

            function get()
            {
                $this->gatekeeper(); // Logged-in users only



                if ($token = $this->getInput('code')) {
                    if ($blipfoto = \Idno\Core\site()->plugins()->get('Blipfoto')) {
                        $blipfotoAPI = $blipfoto->connect();
						$oauth = new \Blipfoto\Api\OAuth($blipfotoAPI, \Blipfoto\Api\Client::URI_AUTHORIZE);

						try {
							$access_token = $oauth->getToken($oauth->getAuthorizationCode());
							$blipfotoAPI->accessToken($access_token['access_token']);

							$user = \Idno\Core\site()->session()->currentUser();
							\Idno\Core\site()->syndication()->registerServiceAccount('blipfoto', $access_token['username'], $access_token['username']);
							$user->blipfoto[$access_token['username']] = array('access_token' => $access_token['access_token'],  'username' => $access_token['username']);
							$user->save();
							\Idno\Core\site()->session()->addMessage('Your Blipfoto credentials were saved.');


						} catch (\Blipfoto\Exceptions\OAuthException $e) {
							// handle OAuth errors here
							\Idno\Core\site()->session()->addMessage('Your Blipfoto credentials could not be saved.');
						}


                        $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'account/blipfoto');
                    }
                }
            }

        }

    }
