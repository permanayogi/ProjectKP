<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Laporansuratkeluarmodel;
use App\Models\Suratkeluarmodel;
use App\Models\ProfilSekolahModel;
use TCPDF;

class Laporansuratkeluar extends BaseController
{
    protected $laporanSuratKeluarModel;

    public function __construct()
    {
        $this->laporanSuratKeluarModel = new Laporansuratkeluarmodel();
    }

    public function index()
    {
        $data = [
            'title' => 'Laporan Surat Keluar',
        ];
        return view('admin/laporansuratkeluar', $data);
    }

    public function getTabelLaporan()
    {
        $start_date = $_POST['startDate'];
        $end_date = $_POST['endDate'];
        $laporanSuratKeluarModel = $this->laporanSuratKeluarModel;
        $column_order = array('', 'tanggal_surat', 'no_surat', 'no_agenda', 'kepada');
        $column_search = array('no_surat', 'no_agenda', 'kepada');
        $order = array('tanggal_surat' => 'ASC');
        $list = $laporanSuratKeluarModel->get_datatables('surat_keluar', $column_order, $column_search, $order, $start_date, $end_date);
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $lists) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = date("d-m-Y", strtotime($lists->tanggal_surat));
            $row[] = $lists->no_surat;
            $row[] = $lists->no_agenda;
            $row[] = $lists->kepada;
            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : null,
            "recordsTotal" => $laporanSuratKeluarModel->count_all('surat_keluar', $start_date, $end_date),
            "recordsFiltered" => $laporanSuratKeluarModel->count_filtered('surat_keluar', $column_order, $column_search, $order, $start_date, $end_date),
            "data" => $data,
            "start_date" => $start_date,
            "end_date" => $end_date,
        );
        echo json_encode($output);
    }
    public function cetakLaporan()
    {
        $laporanSuratKeluarModel = $this->laporanSuratKeluarModel;
        $startDate = $this->request->getPost('startDate');
        $endDate = $this->request->getPost('endDate');
        $data = [
            'title' => 'Laporan Surat Keluar',
            'data' => $laporanSuratKeluarModel->getLaporan($startDate, $endDate),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
        $html = view('admin/cetaklaporansk', $data);

        $pdf = new PDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Admin');
        $pdf->SetTitle('Laporan Surat Keluar - ' . date('d F Y'));
        $pdf->SetSubject('Laporan Surat Keluar');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 14, '', true);
        // $pdf->setPrintHeader(false);
        // $pdf->setPrintFooter(false);
        $pdf->addPage();

        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(277, 4, 'LAPORAN SURAT KELUAR', 0, 1, 'C');
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(277, 4, 'Periode : ' . date("d-F-Y", strtotime($startDate)) . ' s/d ' . date("d-F-Y", strtotime($endDate)), 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetFont('helvetica', '', 10);
        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        //line ini penting
        $this->response->setContentType('application/pdf');
        //Close and output PDF document
        $pdf->Output('laporan surat keluar.pdf', 'I');
    }
}

class PDF extends TCPDF
{
    public function Header()
    {
        $profilSekolahModel = new ProfilSekolahModel();
        $data = $profilSekolahModel->getData();
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(277, 8, $data['nama_sekolah'], 0, 1, 'C');
        $this->SetAutoPageBreak(true, 0);
        $this->SetFont('helvetica', '', 10);
        $this->Cell(277, 4, $data['alamat_sekolah'], 0, 1, 'C');
        $this->Cell(277, 4, 'Telp: ' . $data['telpon'] . ' Kode Pos: ' . $data['kode_pos'], 0, 1, 'C');
        $this->Ln(3);
        $this->writeHTML("<hr>", true, false, false, false, '');
    }
    public function Footer()
    {
        $this->Cell(277, 5, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
