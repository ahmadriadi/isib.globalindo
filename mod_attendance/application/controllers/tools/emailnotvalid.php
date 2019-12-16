<?php

class Emailnotvalid extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Synchrondata_model', 'synchron');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->informemail();
    }

    function informemail() {

        $result = $this->synchron->get_emailnotvalid();
        $checkresult = ($result == '' or $result == NULL) ? 'empty' : 'exist';
        if ($checkresult !== 'empty') {
            $no = 0;
            foreach ($result as $row) {
                $no++;
                $fullname = $row['FullName'];
                $internalemail = $row['InternalEmail'];

                $table = "<tr>";
                $table.= "    <td>" . $no . "</td>";
                $table.= "    <td>" . $fullname . "</td>";
                $table.= "    <td>" . $internalemail . "</td>";
                $table.= "</tr>";


                $data['resultdata'][$no]['tabletr'] = $table;
            }

            
            //$data['images1'] = $this->session->userdata('sess_base_url').'public/theme/images/home.png';
            //$data['images2'] = $this->session->userdata('sess_base_url').'public/theme/images/personal.png';
            //$this->load->view('tools/reportemail',$data);
            
            $attachment = '/tmp/home.png';
            $attachment2 = '/tmp/personal.png';
            $html = $this->load->view('tools/reportemail', $data, true);
            $subject = 'WARNING - UPDATE EMAIL INTERNAL PADA APLIKASI EMPLOYEE CENTER';
            $from = "admintec@trias.loc";
            $to = "alluser@trias.loc";
            $message = $html;
            $cc = array(
                'doris@trias.loc',
                'basirin@trias.loc'
                 );


            $this->emailinternal('html', $subject, $from, $to, $cc, $message,$attachment,$attachment2);
             
        }
    }

    function emailinternal($mail_type, $subject, $from, $to, $cc, $message,$attachment,$attachment2) {
        $email_config = Array(
            'protocol' => 'smtp',
            'smtp_host' => '192.168.0.10',
            'smtp_port' => '25',
            'smtp_user' => 'admintec',
            'smtp_pass' => '123',
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n",
            'crlf' => "\r\n"
        );

        $this->load->library('email');
        $this->email->initialize($email_config);
        $this->email->set_mailtype($mail_type);
        $this->email->subject($subject);
        $this->email->from($from);
        $this->email->to($to);
        $this->email->cc($cc);
        $this->email->message($message);
        $this->email->attach($attachment);
        $this->email->attach($attachment2);

        $sent = $this->email->send();
        if ($sent) {
            echo "Mail has been sent <br/>";
        } else {
            echo "Mail cannot sent <br/>";
        }
    }

}
