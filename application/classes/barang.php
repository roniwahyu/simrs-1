<?php
require_once CLASSES_DIR  . 'dbconnection.php';

class Barang{

    public function getData($sort, $page, $limitItemPage)
    {   
        $db=new DB;
        $conn=$db->connect();
        $page=($page*$limitItemPage)-$limitItemPage;
        $query =
        "SELECT
        barang.barang_id AS barang_id,
        barang.nama_barang AS nama_barang,
        barang.grup_barang_id AS grup_barang_id,
        grup_barang.nama_grup_barang AS grup_barang,
        barang.satuan_id AS satuan_id,
        satuan.nama_satuan AS satuan,
        barang.tanggal_pencatatan AS tanggal_pencatatan
        FROM
        barang
        INNER JOIN grup_barang ON barang.grup_barang_id = grup_barang.grup_barang_id
        INNER JOIN satuan ON barang.satuan_id = satuan.satuan_id
        ORDER BY `barang`.`barang_id` 
        $sort LIMIT $page,$limitItemPage";
        $result = $conn->query($query);
        
        $sql = $conn->query("SELECT COUNT(*) FROM barang");
        $row = $sql->fetch_row();
        $count = $row[0];
        $totalData = $count;
        $totalPages = ceil($totalData/$limitItemPage);
        $data = array("data"=>$result, "currentPage"=>$page/$limitItemPage+1, "totalPages"=>$totalPages, "totalData"=>$totalData);

        return $data;
    }

    public function getOne($id)
    {   
        $db=new DB;
        $conn=$db->connect();
        $query =
        "SELECT
        barang.barang_id AS barang_id,
        barang.nama_barang AS nama_barang,
        barang.grup_barang_id AS grup_barang_id,
        grup_barang.nama_grup_barang AS grup_barang,
        barang.satuan_id AS satuan_id,
        satuan.nama_satuan AS satuan,
        barang.tanggal_pencatatan AS tanggal_pencatatan
        FROM
        barang
        INNER JOIN grup_barang ON barang.grup_barang_id = grup_barang.grup_barang_id
        INNER JOIN satuan ON barang.satuan_id = satuan.satuan_id
        WHERE
        barang.barang_id = $id";
        $result = $conn->query($query);
        $data = array("data"=>$result);
        
        return $data;
    }

    public function postData()
    {   
        $db=new DB;
        $conn=$db->connect();

        $nama_barang    = $_POST['nama_barang'];
        $grup_barang_id = $_POST['grup_barang_id'];
        $satuan_id      = $_POST['satuan_id'];

        $query =
        "INSERT
        INTO barang(nama_barang, grup_barang_id, satuan_id)
        VALUES ('$nama_barang', '$grup_barang_id', '$satuan_id')
        ";
        $result = $conn->query($query);
        return $result;
    }

    public function editData($id)
    {   
        $db=new DB;
        $conn=$db->connect();

        $nama_barang    = $_POST['nama_barang'];
        $grup_barang_id = $_POST['grup_barang_id'];
        $satuan_id      = $_POST['satuan_id'];

        $query =
        "UPDATE `barang` SET `nama_barang` = '$nama_barang', `grup_barang_id` = '$grup_barang_id', `satuan_id` = '$satuan_id' WHERE `barang`.`barang_id` = $id
        ";
        $result = $conn->query($query);
        return $result;
    }

    public function deleteData($id)
    {
        $db=new DB;
        $conn=$db->connect();
        $query ="DELETE FROM `barang` WHERE `barang`.`barang_id` = $id";
        $result = $conn->query($query);
        return $result;
        
    }

    public function searchData($search)
    {   
        $db=new DB;
        $conn=$db->connect();

        $query = 
        "SELECT
        barang.barang_id AS barang_id,
        barang.nama_barang AS nama_barang,
        barang.grup_barang_id AS grup_barang_id,
        grup_barang.nama_grup_barang AS grup_barang,
        barang.satuan_id AS satuan_id,
        satuan.nama_satuan AS satuan,
        barang.tanggal_pencatatan AS tanggal_pencatatan
        FROM
        barang
        INNER JOIN grup_barang ON barang.grup_barang_id = grup_barang.grup_barang_id
        INNER JOIN satuan ON barang.satuan_id = satuan.satuan_id
        WHERE
        barang.nama_barang LIKE '%$search%'
        ORDER BY `barang`.`barang_id` ASC";

        $result = $conn->query($query);
        $data = array("data"=>$result);
        
        return $data;
    }
}
?>