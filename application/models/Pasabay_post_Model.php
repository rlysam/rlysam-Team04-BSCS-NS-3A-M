<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasabay_post_model extends CI_Model {

    private $db_table = "pasabay_post";

    public function get_post(){

        
        if($this->input->get('page') != null){
            $query = $this->db->get($this->db_table, 10, ($this->input->get('page') - 1) * 10);
        }
        else if($this->input->get('post_id') != null){
            $this->db->where('post_id',$_GET['post_id']);
            $query = $this->db->get($this->db_table);
            $result = $query -> result_array();

            return $result[0];
        }
        else{
            $query = $this->db->get($this->db_table);
        }

        return $query -> result_array();
    }

    public function create_post(){
        return $this->db->insert($this->db_table, $this->input->post());
    }

    public function insert_image_location($path){
        $file_extension = pathinfo($path, PATHINFO_EXTENSION);
        $url = "http://localhost/Team04-BSCS-NS-3A-M/pasabay_post/get_image/?path=";
        $input['image_location'] = $url . 'uploads/posts/pasabay/' . $this->db->insert_id() . "." . $file_extension;
        $new_path = 'uploads/posts/pasabay/' . $this->db->insert_id() . "." . $file_extension;
        $this->db->set($input);
        $this->db->where('post_id',$this->db->insert_id());
        $this->db->update($this->db_table);

        return $new_path;
    }
	
    public function deactivate_post($post_id){
        $this->db->set('status', 'deactivated');
        $this->db->where('post_id',$post_id);
        $this->db->update($this->db_table);

        return ($this->db->affected_rows() > 0) ? '200' : '409';
    }

    public function get_total_rows(){
        return $this->db->count_all($this->db_table);
    }

}
