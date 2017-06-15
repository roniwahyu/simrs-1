<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once CLASSES_DIR  . 'gudang.php';
require_once CLASSES_DIR  . 'pengguna.php';
require_once CLASSES_DIR  . 'barang.php';
require_once CLASSES_DIR  . 'mastertabel.php';

class InfoStok extends CI_Controller
{   
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('default_setting');
        $this->session->set_userdata('navbar_status', 'infostok');
        $pengguna = new Pengguna();
        if (!$pengguna->is_loggedin()){
            redirect('login');
        }
    }

    public function index()
    {   
        $this->page(1);
    }

    public function page($page)
    {   
        $gudang = new Gudang();
        $unit_id = 3;
        $title['title']="Info Stok";
        $limit = $_COOKIE["pageLimit"];
        $sort = $_COOKIE["pageSort"];

        if(!isset($page)){ $page = 1; }
        if(!isset($limit)){ 
            $limit = $this->default_setting->pagination('LIMIT'); 
        }
        if(!isset($sort)){ 
            $sort = $this->default_setting->pagination('SORT'); 
        }

        $data = $gudang->infoStok($unit_id, $sort,$page,$limit);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('/farmasi/stokfarmasidepo', $data);
        $this->load->view('footer');
    }

    public function pesan($metode, $return) {
        $this->session->set_flashdata('metode', $metode);
        $this->session->set_flashdata('pesan', 'berhasil');
    }
}