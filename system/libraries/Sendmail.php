<?php

/* Ahmad Riadi - 27-11-2014 */

/* setting gmail disini 

https://www.google.com/settings/security/lesssecureapps

*/


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sendmail {

    public function internalmail($sendto = '', $subject = '', $message = '', $cc = "") {

        $CI = & get_instance(); //instance ini digunakan untuk pengganti $this pada controller
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => '192.168.0.11',
            'smtp_port' => '25',
            'smtp_user' => 'admintec',
            'smtp_pass' => '123',
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n",
            'crlf' => "\n"
          //  'crlf' => "\r\n"
        );

        $CI->load->library('email');
        $CI->email->initialize($config);
        $CI->email->clear(TRUE);
        $CI->email->from('admintec@tis.loc', 'TIS SYSTEM');
        $CI->email->to($sendto);
        $CI->email->cc($cc);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!$CI->email->send()) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function externalmail($sendto = '', $subject = '', $message = '', $cc = "") {
        $CI = & get_instance(); //instance ini digunakan untuk pengganti $this pada controller
        $config = array(
            'protocol' => 'smtp',
            //'smtp_host' => 'ssl://smtp.googlemail.com',
	        'smtp_host' => 'ssl://smtp.gmail.com',  	
            'smtp_port' => '465',
            'smtp_user' => 'triasindrasaputra@gmail.com',
            'smtp_pass' => '*K4m4l*474#',
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n",
            'crlf' => "\n"
           // 'crlf' => "\r\n"
        );
        $reply_to = "triasindrasaputra@gmail.com";
        $CI->load->library('email');
        $CI->email->initialize($config);
        $CI->email->clear(TRUE);
        $CI->email->from('triasindrasaputra@gmail.com', 'TIS SYSTEM');
        $CI->email->to($sendto);
        $CI->email->cc($cc);
        $CI->email->reply_to($reply_to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!$CI->email->send()) {
            return FALSE;
        }
        return TRUE;
    }
    
    
       public function externalmail_custome($from,$password,$sendto = '', $subject = '', $message = '', $cc = "",$reply_to='') {
        $CI = & get_instance(); //instance ini digunakan untuk pengganti $this pada controller
        $config = array(
            'protocol' => 'smtp',
            //'smtp_host' => 'ssl://smtp.googlemail.com',
	        'smtp_host' => 'ssl://smtp.gmail.com',  	
            'smtp_port' => '465',
            'smtp_user' => $from,
            'smtp_pass' => $password,
            'mailtype' => 'html',
            'starttls' => true,
	    'charset'   => 'iso-8859-1',	
            'newline' => "\r\n",
            'crlf' => "\n"
            //'crlf' => "\r\n"
        );
        $CI->load->library('email');
        $CI->email->initialize($config);
        $CI->email->clear(TRUE);
        $CI->email->from($from, 'PT. TRIAS INDRA SAPUTRA');
        $CI->email->to($sendto);
        $CI->email->cc($cc);
        $CI->email->reply_to($reply_to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if (!$CI->email->send()) {
            return FALSE;
        }
        return TRUE;
    }
    
    
    

}
