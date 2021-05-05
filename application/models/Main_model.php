<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_model extends CI_Model
{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function all()
  { 
    // RETURN ALL THE REGISTERS FROM THE DATA BASE TO BUILD THE VIEW'S TABLE
    $query = "select * from secretSanta";
    $sql = $this->db->query($query);
    $this->total = $sql->num_rows();
    $data = $sql->result_array();
    return $data;
  }

  public function save()
  {
    // SAVE PARTICIPANT FROM FORM
    $firstName = $this->input->post('firstName');
    $lastName = $this->input->post('lastName');
    $eMail = $this->input->post('eMail');

    $query = "insert into secretSanta(firstName,lastName,eMail) value ('$firstName','$lastName','$eMail')";
    $save = $this->db->query($query);

    $row = "<tr><td>2</td><td>$firstName</td><td>$lastName</td><td>$eMail</td></tr>";

    $response = array("row" => $row);

    return $response;
  }

  public function upload($file)
  {
    // IMPORT THE CONTENT OF THE FILE TO THE DATA BASE
    $file = "./uploads/$file";
    $query = "load data local infile '" . $file . "' into table secretSanta fields terminated by ',' enclosed by '\"' lines terminated by '\n' (firstName,lastName,eMail)";
    $load = $this->db->query($query);
    $data = $this->all();
    return $data;
  }

  public function clear()
  {
    // DELETE ALL DATA FROM TABLE
    $query = "truncate secretSanta";
    $clear = $this->db->query($query);
    return true;
  }

  public function shuffle()
  {
    // GET ALL PARTICIPANTS 
    $santas = $this->all();

    if ($this->total > 1) { // IF THERE ARE MORE THAN ONE PARTICIPANT CONTINUE WITH THE PROCESS
      $participants = $santas; // COPY THE ARRAY SO WE CAN CREATE RELATIONS 
      $list = array();
      foreach ($santas as $key => $santa) {
        $assigned = false; // INITIALIZE FLAG
        while (!$assigned) {
          $random = rand(0, sizeof($participants) - 1); // CREATING RANDOM NUMBERS TO IMPROVE THE PROBABILITY TO CHOSE A DIFFERENT PERSON
          if ($santa['lastName'] !== $participants[$random]['lastName']) { 
            // ASSIGN THE RELATION IF THE LAST NAME IS DIFFERENT, WITH THIS CONDITION THE CRITERIA "NOT SAME FAMILY" AND "NOT SELF" IS ACCOMPLISHED
            $santas[$key]['pair'] = $participants[$random]['firstName'] . " " . $participants[$random]['lastName'];
            unset($participants[$random]); // DELETE THE ASSIGNED PARTICIPANT SO IT WOULD NOT REPEAT
            $participants = array_values($participants); // REORDER INDEXES
            $assigned = true;
          } else {
            if (sizeof($participants) == 1) { 
              // IF THERE'S JUST ONE LEFT AND BY ITSELF, WE TAKE THE FIRST PARTICIPANT ASSIGNED AND RE-ASSIGN IT TO THE LAST ONE
              $santas[$key]['pair'] = $santas[0]['pair'];
              $santas[0]['pair'] = $participants[$random]['firstName'] . " " . $participants[$random]['lastName'];
              $assigned = true;
            }
          }
        }
      }
      $table = "";
      foreach ($santas as $santa) {
        $table .= "<tr><td>" . $santa['firstName'] . " " . $santa['lastName'] . "</td><td>" . $santa['pair'] . "</td></tr>";
      }
      $response = array("status" => 1, "table" => $table);
    } else { // RETURN STATUS 0 BECAUSE IT'S NOT POSIBLE TO CREATE A RELATIONAL LIST WITH JUST ONE PERSON
      $response = array("status" => 0, "table" => "");
    }
    return $response;
  }
}
