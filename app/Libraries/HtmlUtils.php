<?php
namespace App\Libraries;


class HtmlUtils {
    
    public function wrapErrorAlert($error) {
        return '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.$error.'</div>';
    }
    
}

