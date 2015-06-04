<div class="row">

    <div class="col-md-10 col-md-offset-1">
        <?=$this->draw('account/menu')?>
        <h1>Blipfoto</h1>

    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
    <?php

            if (!empty(\Idno\Core\site()->config()->blipfoto['client_id']) && !empty(\Idno\Core\site()->config()->blipfoto['client_secret'])) {

                ?>
        <form action="<?= \Idno\Core\site()->config()->getDisplayURL() ?>account/blipfoto/" class="form-horizontal" method="post">
            <?php
              if (empty(\Idno\Core\site()->session()->currentUser()->blipfoto)) {
            ?>

            <div class="control-group">
                <div class="controls-config">

	                <div class="row">
	                <div class="col-md-7">
                    <p>
                        Easily share updates, posts, and pictures to Blipfoto. </p>
                    <p>
	                        With Blipfoto connected, you can cross-post content that you publish publicly on your site.
                    </p>

                    
                    <div class="social">
				     <p>
                     <a href="<?= $vars['oauth_url'] ?>" class="connect tw">
 Connect Blipfoto</a>
                     </p>
					</div>
					

                </div>
            </div>
                </div>
            </div>
            
            <?php

				} else if (!\Idno\Core\site()->config()->multipleSyndicationAccounts()) {

            ?>
                  <div class="control-group">
                      <div class="controls-config">
	                    <div class="row">
						<div class="col-md-7">
                          <p>
                              Your account is currently connected to Blipfoto. Public content that you publish here
                              can be cross-posted to your Blipfoto account.
                          </p>


						<div class="social">
                          <p>
                              <input type="hidden" name="remove" value="1" />
                              <button type="submit" class="connect tw connected">Disconnect Blipfoto</button>
                          </p>
						</div>
                          
                      </div>
                  </div>
                      </div>
                  </div>


            <?php

              } else {
              
              ?>
              		<div class="control-group">
                      <div class="controls-config">
	                    <div class="row">
						<div class="col-md-7">
                          <p>
							You have connected the below accounts to Blipfoto. Public content that you publish here
                              can be cross-posted to your Blipfoto account.
                          </p>

						<?php

                                        if ($accounts = \Idno\Core\site()->syndication()->getServiceAccounts('blipfoto')) {

                                            foreach ($accounts as $account) {

                                                ?>

                                                <div class="social">
                                                <p>
                                                    <input type="hidden" name="remove" class="form-control" value="<?= $account['username'] ?>"/>
                                                    <button type="submit"
                                                            class="connect tw connected">
 @<?= $account['username'] ?> (Disconnect)</button>
                                                </p>
                                                </div>
                                            <?php

                                            }

                                        }

                                    ?>
                                                
                          <p>
                                        <a href="<?= $vars['oauth_url'] ?>" class=""><i class="fa fa-plus"></i> Add another Blipfoto account</a>
                                    </p>
                      </div>
                  </div>
                      </div>
              		</div>

              <?php
              
              }
              
            ?>
            
            <?= \Idno\Core\site()->actions()->signForm('/account/blipfoto/')?>
            
        </form>
                    <?php

            } else {

                if (\Idno\Core\site()->session()->currentUser()->isAdmin()) {

                    ?>
                                  		<div class="control-group">
                      <div class="controls-config">
	                    <div class="row">
						<div class="col-md-7">
                    <p>
                        Before you can begin connecting to Blipfoto, you need to set it up.
                    </p>
                    <p>
                        <a href="<?= \Idno\Core\site()->config()->getDisplayURL() ?>admin/blipfoto/">Click here to begin
                            Blipfoto configuration.</a>
                    </p>
                <?php

                } else {

                    ?>
                    <p>
                        The administrator has not finished setting up Blipfoto on this site.
                        Please come back later.
                    </p>
                    </div>
                    </div>
                    </div>
                    </div>
                
                <?php

                }

            }

        ?>
    </div>
</div>
