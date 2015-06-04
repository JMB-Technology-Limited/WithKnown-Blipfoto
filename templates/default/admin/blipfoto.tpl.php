<div class="row">

    <div class="col-md-10 col-md-offset-1">
        <?=$this->draw('admin/menu')?>
        <h1>Blipfoto configuration</h1>

    </div>

</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <form action="<?=\Idno\Core\site()->config()->getDisplayURL()?>admin/blipfoto/" class="form-horizontal" method="post">
            <div class="control-group">
                <div class="controls-config">
                    <p>
                        To begin using Blipfoto, <a href="https://www.polaroidblipfoto.com/developer/apps" target="_blank">create a new application in
                            the Blipfoto developer portal</a>.</p>
                    <p>
                        The website URL should be set to:
                    </p>
                    <p>
                        <input type="text" name="ignore" class="form-control" value="<?=\Idno\Core\site()->config()->url ?>" />
                    </p>
					<p>
                        The Redirect URL should be set to:
                    </p>
                    <p>
                        <input type="text" name="ignore" class="form-control" value="<?=\Idno\Core\site()->config()->url . 'blipfoto/callback'?>" />
                    </p>

                </div>
            </div>
                        
            <div class="controls-group">
	                <p>
                        Once you've finished, fill in the details below:
                    </p>
                <label class="control-label" for="api-key">Client ID</label>

                    <input type="text" id="api-key" placeholder="Consumer key" class="form-control" name="client_id" value="<?=htmlspecialchars(\Idno\Core\site()->config()->blipfoto['client_id'])?>" >


            
                <label class="control-label" for="api-secret">Client secret</label>

                    <input type="text" id="api-secret" placeholder="Consumer secret" class="form-control" name="client_secret" value="<?=htmlspecialchars(\Idno\Core\site()->config()->blipfoto['client_secret'])?>" >
   
            </div>     	            
          <div class="controls-group">
	          <p>
                        After the Blipfoto application is configured, site users must authenticate their Blipfoto account under Settings.
                    </p>

          </div>  
            
            <div>
                <div class="controls-save">
                    <button type="submit" class="btn btn-primary">Save settings</button>
                </div>
            </div>
            <?= \Idno\Core\site()->actions()->signForm('/admin/blipfoto/')?>
        </form>
    </div>
</div>
