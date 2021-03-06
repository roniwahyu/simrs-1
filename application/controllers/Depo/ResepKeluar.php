<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once CLASSES_DIR  . 'pengguna.php';
require_once CLASSES_DIR  . 'detiltransaksi.php';
require_once CLASSES_DIR  . 'transaksiobat.php';

class ResepKeluar extends CI_Controller
{   
    private $unit_id=2;
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->model('default_setting');
        $this->load->model('penggunaModel');
        $this->load->model('transaksiObatModel');
        $this->load->model('detilTransaksiModel');
        $this->session->set_userdata('navbar_status', 'resepkeluar');
        $pengguna = new Pengguna();
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
        $title['title']="Riwayat Resep Keluar";
        $status = 'sudah_dilayani';
        $limit = $_COOKIE["pageLimit"];
        $sort = $_COOKIE["pageSort"];

        if(!isset($page)){ $page = 1; }
        if(!isset($limit)){ $limit = $this->default_setting->pagination('LIMIT'); }
        if(!isset($sort)){ $sort = $this->default_setting->pagination('SORT');  }

        $data = $this->transaksiObatModel->riwayatResepKeluar($sort,$page,$limit);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('/depo/riwayatresepkeluar', $data);
        $this->load->view('footer');
    }

    public function detilTransaksi($nomorTransaksi)
    {   
        $title['title']="Detil Permintaan Masuk";
        $data = $this->detilTransaksiModel->detilResep($nomorTransaksi);
        $this->load->view('header',$title);
        $this->load->view('navbar');
        $this->load->view('/depo/detilresep', $data);
        $this->load->view('footer');
    }

    public function printresep($nomorTransaksi)
    {   
        $title['title']="Print Resep";
        $data = $this->detilTransaksiModel->printResep($nomorTransaksi);
        $this->load->view('header',$title);
        $this->load->view('depo/printresep',$data);
    }
}