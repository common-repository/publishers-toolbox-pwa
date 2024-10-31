<?php
    
    use PT\frontend\PublishersToolboxPwaFrontend;
    
    $themeBody = (new PublishersToolboxPwaFrontend(PUBLISHERS_TOOLBOX_PLUGIN_NAME, PUBLISHERS_TOOLBOX_PLUGIN_VERSION))->previewQueryUri();
    
    echo $themeBody['body'];
