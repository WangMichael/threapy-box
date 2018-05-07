<?php

declare(strict_types=1);

namespace framework\template;

class template implements templateInterface{



    private $msg     = '';

    private $isError = true;


    public function render(String $templatePath, Array $containerVariable):String
    {
        // use a weird name to make sure the variable doesn't get overwritten
        ${'template-66f6181bcb4cff4cd38fbc804a036db6'} = $templatePath;

        foreach ($containerVariable AS $key => $value) {
            $$key = $value; // manual extract to allow for vars with dashes
        }

        unset($containerVariable);
        ob_start();
        require(${'template-66f6181bcb4cff4cd38fbc804a036db6'});
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }


    public function setErrorHandler() : void
    {
        set_error_handler(array($this, 'handleError'), E_ALL);
    }



    public function handleError($error_level, $error_message)
    {
        switch ($error_level) {
            case E_USER_ERROR:
            case E_USER_WARNING:
            case E_USER_NOTICE:
                if(!$this->msg){
                    $this->msg      = $error_message;
                    $this->isError  = $error_level !== E_USER_NOTICE;
                }
                break;
            default:
                return false; // normal error handler continue

        }
        return true;
    }


    public function drawErrorMsg() : string
    {
        if ($this->msg)
            return '<div class="msg'.($this->isError ? ' msg--error' : '').'">'.nl2br(htmlspecialchars($this->msg)).'</div>';
        return '';
    }

}