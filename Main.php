<?php

    namespace IdnoPlugins\Blipfoto {

        class Main extends \Idno\Common\Plugin
        {

            function registerPages()
            {
                // Auth URL
                \Idno\Core\site()->addPageHandler('blipfoto/auth', '\IdnoPlugins\Blipfoto\Pages\Auth');
                // Deauth URL
                \Idno\Core\site()->addPageHandler('blipfoto/deauth', '\IdnoPlugins\Blipfoto\Pages\Deauth');
                // Register the callback URL
                \Idno\Core\site()->addPageHandler('blipfoto/callback', '\IdnoPlugins\Blipfoto\Pages\Callback');
                // Register admin settings
                \Idno\Core\site()->addPageHandler('admin/blipfoto', '\IdnoPlugins\Blipfoto\Pages\Admin');
                // Register settings page
                \Idno\Core\site()->addPageHandler('account/blipfoto', '\IdnoPlugins\Blipfoto\Pages\Account');

                /** Template extensions */
                // Add menu items to account & administration screens
                \Idno\Core\site()->template()->extendTemplate('admin/menu/items', 'admin/blipfoto/menu');
                \Idno\Core\site()->template()->extendTemplate('account/menu/items', 'account/blipfoto/menu');
                \Idno\Core\site()->template()->extendTemplate('onboarding/connect/networks', 'onboarding/connect/blipfoto');
            }

            function registerEventHooks()
            {

                \Idno\Core\site()->syndication()->registerService('blipfoto', function () {
                    return $this->hasBlipfoto();
                }, array('image'));

                if ($this->hasBlipfoto()) {
                    if (is_array(\Idno\Core\site()->session()->currentUser()->blipfoto)) {
                        foreach(\Idno\Core\site()->session()->currentUser()->blipfoto as $username => $details) {
                            if (!in_array($username, ['user_token','user_secret','screen_name'])) {
                                \Idno\Core\site()->syndication()->registerServiceAccount('blipfoto', $username, $username);
                            }
                        }
                        if (array_key_exists('user_token', \Idno\Core\site()->session()->currentUser()->blipfoto)) {
                            \Idno\Core\site()->syndication()->registerServiceAccount('blipfoto', \Idno\Core\site()->session()->currentUser()->blipfoto['screen_name'], \Idno\Core\site()->session()->currentUser()->blipfoto['screen_name']);
                        }
                    }
                }


                // Push "images" to Blipfoto
                \Idno\Core\site()->addEventHook('post/image/blipfoto', function (\Idno\Core\Event $event) {
                    if ($this->hasBlipfoto()) {
                        $eventdata = $event->data();
                        $object     = $eventdata['object'];
                        if (!empty($eventdata['syndication_account'])) {
                            $blipfotoAPI  = $this->connect($eventdata['syndication_account']);
                        } else {
                            $blipfotoAPI  = $this->connect();
                        }

                        $title = $object->getTitle();
                        if (mb_strlen($title) > 50) { // Trim status down if required
							$title = substr($title, 0, 46) . ' ...';
                        }

						$description = $object->getBody();

						// The whole point of Blip is that there can only be one ....
						$attachment = array_shift($object->getAttachments());

						if (!$attachment) {
							return null;
						}

						$fileData = (array)\Idno\Entities\File::getByID($attachment['_id']);

						if (!$fileData) {
							return null;
						}

						// TODO Do we have to make local copy of file like other extensions do just so other storage backends work?

						$media = $fileData['internal_filename'];

						$response = null;
						  try {
							  $response = $blipfotoAPI->post('entry',array(
								  'title'=>$title,
								  'description'=>$description,
							  ),array(
								  'image'=>$media,
							  ));

							  $entryID = $response->data('entry.entry_id');

								if (!empty($entryID)) {
											$object->setPosseLink('blipfoto', 'https://www.polaroidblipfoto.com/entry/' . $entryID, 'blipfoto');
											// TODO 'blipfoto' should really be the user username
											$object->save();

								} else {
									\Idno\Core\site()->logging()->log("Bad Response from Blipfoto: " . var_export($response->data(),true));

								}


						  } catch (\Exception $e) {
							  \Idno\Core\site()->logging()->log($e);
							  if ($response) {
								  \Idno\Core\site()->logging()->log($response->data());
							  }
						  }





                    }
                });
            }

            /**
             * Retrieve the OAuth authentication URL for the API
             * @return string
             */
            function getAuthURL()
            {

				$client = $this->connect();
				if ($client) {
					$oauth = new \Blipfoto\Api\OAuth($client, \Blipfoto\Api\Client::URI_AUTHORIZE);
					$oauth->authorize(\Idno\Core\site()->config()->url . 'blipfoto/callback', \Blipfoto\Api\Client::SCOPE_READ_WRITE);

					// The above code actually does the Header redirect and exit itself. So it doesn't matter what we do here, as this will never run!

				}
                return '';
            }

            /**
             * Returns a new Blipfoto OAuth connection object, if credentials have been added through administration
             * and it's possible to connect
             *
             * @param $username If supplied, attempts to connect with this username
             * @return bool|\tmhOAuth
             */
            function connect($username = false)
            {
				require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Exceptions/BaseException.php');
				require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Exceptions/FileException.php');
				require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Exceptions/ApiResponseException.php');
				require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Exceptions/InvalidResponseException.php');
				require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Exceptions/NetworkException.php');
				require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Exceptions/OAuthException.php');
				require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Traits/Helper.php');
                require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Api/File.php');
                require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Api/Client.php');
                require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Api/OAuth.php');
                require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Api/Request.php');
                require_once(dirname(__FILE__) . '/external/blipfoto-sdk/Blipfoto/Api/Response.php');
                if (!empty(\Idno\Core\site()->config()->blipfoto)) {

                    $blipfotoAPI = new \Blipfoto\Api\Client(\Idno\Core\site()->config()->blipfoto['client_id'], \Idno\Core\site()->config()->blipfoto['client_secret']);

					if (!empty($username) && !empty(\Idno\Core\site()->session()->currentUser()->blipfoto[$username])) {
						$blipfotoAPI->accessToken(\Idno\Core\site()->session()->currentUser()->blipfoto[$username]['access_token']);
					}

					return $blipfotoAPI;
                }

                return false;
            }

            /**
             * Can the current user use Blipfoto?
             * @return bool
             */
            function hasBlipfoto()
            {
                if (!\Idno\Core\site()->session()->currentUser()) {
                    return false;
                }
                if (!empty(\Idno\Core\site()->session()->currentUser()->blipfoto)) {
                    if (is_array(\Idno\Core\site()->session()->currentUser()->blipfoto)) {
                        $accounts = 0;
                        foreach(\Idno\Core\site()->session()->currentUser()->blipfoto as $username => $value) {
                            if ($username != 'user_token') {
                                $accounts++;
                            }
                        }
                        if ($accounts > 0) {
                            return true;
                        }
                    }
                    return true;
                }

                return false;
            }

        }

    }
