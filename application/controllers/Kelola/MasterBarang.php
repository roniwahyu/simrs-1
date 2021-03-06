<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MasterBarang extends CI_Controller
{   
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('default_setting');
        $this->load->model('penggunaModel');
        $this->load->model('masterTabelModel');
        $this->load->model('barangModel');
        $this->session->set_userdata('navbar_status', 'kelola');
        if (!$this->penggunaModel->is_loggedin()){
            redirect('login');
        }
    }
    
    public function index()
    {   
        $this->page(1);
    }

    public function page($page=null)
    {   

        if(!isset($page)){
            $page=1;
        }
        $title['title']="Kelola Barang";
        $limit = $_COOKIE["pageLimit"];
        $sort = $_COOKIE["pageSort"];
        if(!isset($page)){ $page = 1; }
        if(!isset($limit)){ 
            $limit = $this->default_setting->pagination('LIMIT'); 
        }
        if(!isset($sort)){ 
            $sort = $this->default_setting->pagination('SORT'); 
        }
        $data = $this->barangModel->getData($sort, $page, $limit);
        $data['daftarGrupBarang'] = $this->masterTabelModel->getData("grup_barang");
        $data['daftarSatuan']     = $this->masterTabelModel->getData("satuan");
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('kelola/masterbarang', $data);
        $this->load->view('footer');
    }

    public function detil($id=null)
    {   
        
        $title['title']="Kelola Barang";
        
        $data = $this->barangModel->getOne($id);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('kelola/masterbarang', $data);
        $this->load->view('footer');
    }


    public function search($search=null)
    {   
        $search = $_POST['search'];
        
        
        $title['title']="Kelola Barang";
        
        $data = $this->barangModel->searchData($search);
        $data['daftarGrupBarang'] = $this->masterTabelModel->getData("grup_barang");
        $data['daftarSatuan']     = $this->masterTabelModel->getData("satuan");
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('kelola/masterbarang', $data);
        $this->load->view('footer');
    }
    
    public function insertData() {
        
        $affectedRow = $this->barangModel->postData();
        $this->pesan("Tambah", $affectedRow);
        redirect('kelola/masterbarang', 'refresh');
    }

    public function editData($id) {
        
        $affectedRow = $this->barangModel->editData($id);
        $this->pesan("Edit", $affectedRow);
        redirect('kelola/masterbarang', 'refresh');
    }

    public function deleteData($id) {
        
        $affectedRow = $this->barangModel->deleteData($id);
        $this->pesan("Hapus", $affectedRow);
        redirect('kelola/masterbarang', 'refresh');
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
