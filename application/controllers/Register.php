<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *'); //Since flutter is not a static url

class Register extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

    public function get_user(){
        $this->load->model("Register_Model");

        $data = $this->Register_Model->get_user();

        $output = json_encode($data);
        echo $output;
    }

    public function generate_verification_code(){

        $CHARS_LENGTH = 8;

        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars_len = strlen($chars);
        $order = '';
        $code = '';


        for ($i = 0; $i < $CHARS_LENGTH; $i++){
            //generate at least one uppercase
            if ($i == 0) {
                $order[$i] = chr(rand(65, 90));
             //generate at least one lowercase
            } else if ($i == 1) {
                $order[$i] = chr(rand(97,122));
            //generate at least one number (0-9)
            } else if ($i == 2) {
                $order[$i] = chr(rand(48,57));
            } else {
                $order[$i] = $chars[rand(0, $chars_len) - 1];
            }
        }

        $code = str_shuffle($order);
        
        return $code;
    }

    public function send_email_verification(){
        $this->load->library('email');
        $code = $this->generate_verification_code();
        $this->load->config('email');
    
        $from = $this->config->item('smtp_user');
        $to = 'marvinray.dalida@tup.edu.ph';
        $subject = 'Pahiream verification code';
        $message = $code;

        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            echo 'Email sent';
            $output = array('code' => $code);
            echo json_encode($output);
        } else {
            show_error($this->email->print_debugger());
        }

    }

    //FOR TESTING
    public function insert_user(){
        
        $this->load->model("Register_Model");
        $data = $this->Register_Model->insert_user();
    }

    public function get_post(){
        $input = $this->input->post();

        //log_message('Debug', 'lumabas = '. $output);
        
        if(isset($input['fname'])){
            log_message('Debug', 'post = '.print_r($input, true));
        }
        else
            log_message('Debug', 'geegee ;pds = '.print_r($input, true));
    }
}
