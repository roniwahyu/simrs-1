<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class ObatKeluar extends CI_Controller
{   
    private $unit_id=2;
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('penggunaModel');
        $this->load->model('pengeluaranBarangModel');
        $this->load->model('default_setting');
        $this->session->set_userdata('navbar_status', 'obatkeluardepo');
        if (!$this->penggunaModel->is_loggedin()){
            redirect('login');
        }
    }
    
    public function index()
    {   
        $_SESSION["tanggalAwal"]=null;
        $_SESSION["tanggalAkhir"]=null;
        $_SESSION["searchFarmasi"]=null;
        $this->page(1);
    }

    public function page($page=null)
    {   
        $title['title']="Riwayat Permintaan Masuk";
        $status = 'sudah_dilayani';

        $limit = $_COOKIE["pageLimit"];
        $sort = $_COOKIE["pageSort"];

        if(!isset($page)){ $page = 1; }
        if(!isset($limit)){ $limit = $this->default_setting->pagination('LIMIT'); }
        if(!isset($sort)){ $sort = $this->default_setting->pagination('SORT');  }

        $data = $this->pengeluaranBarangModel->riwayatPengeluaranStok($this->unit_id, $sort,$page,$limit);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('/depo/riwayatobatkeluar', $data);
        $this->load->view('footer');
    }
}