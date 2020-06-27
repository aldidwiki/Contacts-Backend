<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Contacts extends RestController
{
    private $uploadPath, $serverIP, $uploadURL;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ContactsModel');

        $this->uploadPath = 'upload/pictures/';
        $this->serverIP = 'http://192.168.1.12/';
        $this->uploadURL = $this->serverIP . 'rest/' . $this->uploadPath;
    }

    public function index_get()
    {
        $keywords = $this->get('keywords');

        if ($keywords === null) {
            $contact = $this->ContactsModel->getContacts();
        } else {
            $contact = $this->ContactsModel->searchContacts($keywords);
        }
        if ($contact) {
            $this->response([
                'status' => true,
                'result' => $contact
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'No Data Found'
            ], RestController::HTTP_NOT_FOUND);
        }
    }

    public function index_delete()
    {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'Provide an ID'
            ], RestController::HTTP_BAD_REQUEST);
        } else {
            if ($this->ContactsModel->deleteContacts($id) > 0) {
                $this->response([
                    'status' => true,
                    'message' => $id . ' Deleted'
                ], RestController::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'ID not Found'
                ], RestController::HTTP_BAD_REQUEST);
            }
        }
    }

    public function upload($avatar)
    {
        if (!empty($_FILES['avatar']['name'])) {
            $fileInfo = pathinfo($_FILES['avatar']['name']);
            $filePath = $this->uploadPath . $fileInfo['basename'];

            move_uploaded_file($_FILES['avatar']['tmp_name'], $filePath);
        }

        if (!empty($avatar)) {
            $fileURL = $this->uploadURL;
        } else {
            $fileURL = "Image Null";
        }

        return $fileURL;
    }

    public function index_post()
    {
        $avatar = $this->post('avatar');
        $data = [
            'nama' => $this->post('nama'),
            'nomor' => $this->post('nomor'),
            'alamat' => $this->post('alamat'),
            'avatar' => $this->upload($avatar) . $avatar
        ];

        if ($this->ContactsModel->insertContacts($data) > 0) {
            $this->response([
                'status' => true,
                'message' => 'Data Inserted',
                'result' => $data
            ], RestController::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to Insert Data'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');
        $avatar = $this->put('avatar');
        $data = [
            'nama' => $this->put('nama'),
            'nomor' => $this->put('nomor'),
            'alamat' => $this->put('alamat'),
            'avatar' => $this->upload($avatar) . $avatar
        ];

        if ($this->ContactsModel->updateContacts($data, $id) > 0) {
            $this->response([
                'status' => true,
                'message' => 'Data Edited',
                'result' => $data
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to Update Data'
            ], RestController::HTTP_BAD_REQUEST);
        }
    }
}
