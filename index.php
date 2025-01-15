<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$toolUploadFile = new uploadFileExcel();

$data = [];
if(!isset($_POST['submit'])) $toolUploadFile->render_ui();
if (isset($_POST['submit'])) $toolUploadFile->handleFile();

class uploadFileExcel
{
    public function __construct() {}

    public function render_ui()
    {
        require './interface/upload-file.php';
    }

    public function handleFile(){
        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['excel_file']['tmp_name'];
            $fileName = $_FILES['excel_file']['name'];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            if (in_array(strtolower($fileExtension), ['xlsx', 'xls'])) {
                try {
                    $spreadsheet = IOFactory::load($fileTmpPath);
                    $sheet = $spreadsheet->getActiveSheet();
                    $data = $sheet->toArray();
                    $dataExcel = [];
                    foreach ($data as $key => $row) {
                        if($key == 0 || empty($row[6])) continue;
                        $dataExcel[] = [
                            'tenKhachHang'  => $row[6] ?? '',
                            'soDienThoai'   => $row[7] ?? '', 
                            'diaChi'        => $row[5] ?? '', 
                            'thanhToan'     => $row[32] ?? 0,
                            'tenHang'       => $this->tinhTenHang($row),
                            'luongTinhTien' => $this->tinhLuongTinhTien($row),
                            'luongKhuyenMai'     => $this->tinhLuongKhuyenMai($row),
                            'soThung'       => (int) ($row[8] ?? $row[9] ?? $row[10] ?? $row[11] ?? $row[12] ?? 0), 
                            'mau'           => $this->tinhMau($row), 
                            'manAo'         => $row[13],
                            'giaTien'           => $row[14],
                            'thanhTien'     => $row[16],
                            'vatTu_M'       => (int) $row[17] ?? 0,
                            'vatTu_C1'       => (int) $row[18] ?? 0,
                            'vatTu_TAO'       => (int) $row[19] ?? 0,
                            'vatTu_ZEO'       => (int) $row[20] ?? 0,
                            'lapPhieu'      => $row[2] ?? '',
                        ];
                    }
    
                    $this->render_hoa_don($dataExcel);
                    
                } catch (Exception $e) {
                    echo "Lỗi khi đọc file Excel: " . $e->getMessage();
                }
            } else {
                echo "Chỉ được phép upload file Excel (.xlsx, .xls).";
            }
        } else {
            echo "Lỗi khi tải file lên.";
        }
    }

    public function tinhTenHang($row){
        if(!empty($row[8])) return 'Tôm sú giống Tân Nguyên';
        if(!empty($row[9])) return 'Tôm sú giống Tân Nguyên cộng';
        if(!empty($row[10])) return 'Tôm sú giống Tân Nguyên 68';
        if(!empty($row[11])) return 'Tôm thẻ giống Tân Nguyên';
        if(!empty($row[12])) return 'Tôm thẻ giống Tân Nguyên cộng';
    }

    public function tinhMau($row){
        if(!empty($row[8])) return '14000';
        if(!empty($row[9])) return '12000';
        if(!empty($row[10])) return '6000';
        if(!empty($row[11])) return '14000';
        if(!empty($row[12])) return '12000';
    }

    public function tinhLuongKhuyenMai($row){
        $khuyenMai = !empty($row[14]) ? strval(str_replace('.', '', $row[15])) : 1;
        $gia = !empty($row[13]) ? strval(str_replace('.', '', $row[14])) : 1;
        return (int) round($khuyenMai/$gia);
    }

    public function tinhLuongTinhTien($row){
        $luongKhuyenMai = $this->tinhLuongKhuyenMai($row);
        $tongThung = $row[8] ?? $row[9] ?? $row[10] ?? $row[11] ?? $row[12] ?? 0;

        if($tongThung == 0) return 0;
        $luongTinhTien = (int) $tongThung - $luongKhuyenMai;
        return $luongTinhTien;
    }

    public function render_hoa_don($dataExcel){
        require './interface/invoice.php';
    }
}
