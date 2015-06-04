<?php

    if (empty(\Idno\Core\site()->session()->currentUser()->blipfoto)) {
        $login_url = \Idno\Core\site()->config()->getDisplayURL() . 'blipfoto/auth';
    } else {
        $login_url = \Idno\Core\site()->config()->getDisplayURL() . 'blipfoto/deauth';
    }

?>
<div class="social">
    <a href="<?= $login_url ?>" class="connect tw <?php

        if (!empty(\Idno\Core\site()->session()->currentUser()->blipfoto)) {
            echo 'connected';
        }

    ?>" target="_top">Blipfoto<?php

            if (!empty(\Idno\Core\site()->session()->currentUser()->blipfoto)) {
                echo ' - connected!';
            }

        ?></a>
    <label class="control-label">Share pictures, updates, and posts to Blipfoto.</label>
</div>
