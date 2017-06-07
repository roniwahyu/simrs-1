<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once CLASSES_DIR  . 'apicall.php';
require_once CLASSES_DIR  . 'pasien.php';
require_once CLASSES_DIR  . 'jenispasien.php';

class KelolaPasien extends CI_Controller
{   
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('default_setting');
        $this->load->model('kelola/m_kelolapasien');
        $this->session->set_userdata('navbar_status', 'kelola');
        setcookie("pageLimit",15, time()+86400, "/","", 0);   //---------------Taruh ini di login dan setting
        setcookie("pageSort", "DESC", time()+86400, "/","", 0);   //---------------Taruh ini di login dan setting
    }
    
    public function index()
    {   
        $this->page(1);
    }

    public function page($page=null)
    {   
        $pasien = new Pasien();
        //$url="pasien";
        if(!isset($page)){
            $page=1;
        }
        $title['title']="Kelola Pasien";
        $limit = $_COOKIE["pageLimit"];
        $sort = $_COOKIE["pageSort"];
        if(!isset($page)){ $page = 1; }
        if(!isset($limit)){ 
            $limit = $this->default_setting->pagination('LIMIT'); 
        }
        if(!isset($sort)){ 
            $sort = $this->default_setting->pagination('SORT'); 
        }
        //$data = $entity->getAll($url, $page, $limit, $sort);
        $data = $pasien->getData($sort, $page, $limit);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('/kelola/kelolapasien', $data);
        $this->load->view('footer');
    }

    public function detil($id=null)
    {   
        $pasien = new Pasien();
        //$url="pasien";
        $title['title']="Kelola Pasien";
        
        $data = $pasien->getOne($id);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('/kelola/kelolapasien', $data);
        $this->load->view('footer');
    }


    public function search($search=null)
    {   
        $search = $this->input->post('search');
        $pasien = new Pasien();
        $title['title']="Kelola Pasien";
        
        $data = $pasien->searchData($search);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('/kelola/kelolapasien', $data);
        $this->load->view('footer');
    }
    
    public function insertData() {
        $pasien = new Pasien();
        $affectedRow = $pasien->postData();
        $this->pesan("Tambah", $affectedRow);
        redirect('/kelola/kelolapasien', 'refresh');
    }

    public function editData($id) {
        $pasien = new Pasien();
        $affectedRow = $pasien->editData($id);
        $this->pesan("Edit", $affectedRow);
        redirect('/kelola/kelolapasien', 'refresh');
    }

    public function deleteData($id) {
        $pasien = new Pasien();
        $affectedRow = $pasien->deleteData($id);
        $this->pesan("Hapus", $affectedRow);
        redirect('/kelola/kelolapasien', 'refresh');
    }

    public function test()
    {   
        $data = $this->m_kelolapasien->getData();
        var_dump($data);
        echo "<br><br><br><br><br><br>";
        $this->load->view('/tests/testDumpKelolaPasien', $data);
    }

    public function pesan($metode, $affectedRow) {
        $this->session->set_flashdata('metode', $metode);
        if ($affectedRow == 1) {

            $this->session->set_flashdata('pesan', 'berhasil');
        } elseif ($affectedRow == 0 and $metode == "ubah") {
            $this->session->set_flashdata('pesan', 'berhasil');
        } else {
            $this->session->set_flashdata('pesan', 'gagal');
        }
    }
}