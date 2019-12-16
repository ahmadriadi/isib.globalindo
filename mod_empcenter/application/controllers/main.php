<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('hits_model', 'hits');
        $this->is_logged_in();
        $this->hits_info();
    }

    public function index() {
        $page['title'] = "Main Page";
        $page['fullname'] = $this->session->userdata('sess_fullname');;
        //$page['content'] = "blog";
        //$page['widget'] = "widget";
        //$page['posts'] = $this->posts->get_allposts(10);
        //$page['recent'] = $this->recently->get_post();
        //$page['comment'] = $this->commented->get_post();
        $this->load->view('template', $page);
    }

    public function missing() {
        $page['title'] = "404 Page Not Found";
        $page['content'] = "missing";
        $this->load->view('template', $page);
    }

    public function language($lang) {
        $page['title'] = "Language";
        $this->load->view('template', $page);
    }

    function is_logged_in() {
        if ($this->session->userdata('sess_logged_in') != TRUE) {
            redirect('login', 'refresh');
        }
    }

    public function hits_info() {
        $info = array(
            'remote_addr' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'uri_string' => $this->uri->uri_string(),
            'referrer' => $this->agent->referrer()
        );
        $this->hits->insert($info);
    }

}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
