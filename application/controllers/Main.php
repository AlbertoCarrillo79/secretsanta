<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
    $this->load->helper('url_helper');
    $this->load->model('main_model');
  }


  public function index()
  {
    // SHOW INITIAL DATA
    $data['secretsanta'] = $this->main_model->all();
    $this->load->view('templates/header');
    $this->load->view('main', $data);
    $this->load->view('templates/footer');
  }

  public function save()
  {
    // SAVE PARTICIPANT FROM FORM
    $response = $this->main_model->save();
    echo json_encode($response);
  }

  public function upload()
  {
    // UPLOAD LIST FROM FILE FORMAT: FIRST NAME, LAST NAME, EMAIL

    // LOAD LIBRARY TO UPLOAD FILES
    $this->load->library('upload');
    // UPLOAD FILES SETTINGS, VALIDATION AND INITIALIZATION
    $config['upload_path']          = './uploads/';
    $config['allowed_types']        = 'csv';
    $config['max_size']             = 100;
    $this->upload->initialize($config);

    if (!$this->upload->do_upload('file')) { // CATCH MESSAGE IF THERE'S SOME ERROR TO SHOW IN THE VIEW
      $error = array('error' => $this->upload->display_errors());
      $response = array("error" => $error, "table" => "", "status" => 0);
    } else { // ON SUCCESS, LOAD THE FILE AND BUILD THE BODY TABLE TO SHOW IT IN THE VIEW
      $participants = $this->main_model->upload($this->upload->data('file_name'));
      $table="";
      foreach ($participants as $participant) {
        $table.="<tr><td>".$participant['id']."</td><td>".$participant['firstName']."</td><td>".$participant['lastName']."</td><td>".$participant['eMail']."</td></tr>";
      }
      $response=array("error"=>"","table"=>$table,"status"=>1);
    }
    echo json_encode($response);
  }

  public function clear()
  { 
    // DELETE ALL DATA FROM TABLE
    $this->main_model->clear();
    echo 0;
  }

  public function shuffle()
  {
    // SHUFFLE THE LIST TO CREATE THE
    $response=$this->main_model->shuffle();
    echo json_encode($response);
  }
}
