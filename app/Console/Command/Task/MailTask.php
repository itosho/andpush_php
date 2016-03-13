<?php
App::uses('CakeEmail', 'Network/Email');

/**
 * MailTask is uses for Shell to send mail
 * 
 * @access public
 * @author itosho
 * @copyright itosho All Rights Reserved
 * @version 1.0.0
 * @category Tasks
 * @package Commond/Tasks
 *         
 */
class MailTask extends Shell
{

    public $template;

    protected $viewVars = array();

    private $config = "default";
    
    /**
     * send mail
     * 
     * @param string $subject            
     * @param string $content            
     * @param array $attachment            
     */
    public function send($subject, $content = null, $attachment = null)
    {
        $Email = new CakeEmail($this->config);
        $Email->subject($subject);
        $Email->emailFormat(CakeEmail::MESSAGE_TEXT);
        
        if($this->template) {
            $Email->template($this->template);
        }

        if (count($this->viewVars) > 0) {
            $Email->viewVars($this->viewVars);
        }

        $Email->send($content);

        echo mb_convert_encoding($Email->message(CakeEmail::MESSAGE_TEXT), 'sjis');
    }
    
}