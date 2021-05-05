<?php
// API CONTROLLER

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';

use chriskacerguis\RestServer\RestController;

class ApiSecretsantaController extends RestController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Secretsanta_model');
    }

    public function index_get()
    {
        $participants = $this->Secretsanta_model->get_participants();
        $this->response($participants, 200);
    }

    public function addParticipant_post()
    {
        $data = [
            "firstName" => $this->input->post('firstName'),
            "lastName" => $this->input->post('lastName'),
            "eMail" => $this->input->post('eMail'),
        ];
        $participant = $this->Secretsanta_model->add_participant($data);
        if ($participant > 0) {
            $this->response([
                'status' => true,
                'message' => 'Participant registered successfuly'
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Participant not registered'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function findParticipant_get($id)
    {
        $participant = $this->Secretsanta_model->find_participant($id);
        $this->response($participant, 200);
    }

    public function updateParticipant_put($id)
    {
        $data = [
            "firstName" => $this->put('firstName'),
            "lastName" => $this->put('lastName'),
            "eMail" => $this->put('eMail'),
        ];
        $participant = $this->Secretsanta_model->update_participant($id, $data);
        if ($participant > 0) {
            $this->response([
                'status' => true,
                'message' => 'Participant updated'
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Participant update failed'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function deleteParticipant_delete($id)
    {
        $participant = $this->Secretsanta_model->delete_participant($id);
        if ($participant > 0) {
            $this->response([
                'status' => true,
                'message' => 'Participant deleted'
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Participant delete failed'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}
