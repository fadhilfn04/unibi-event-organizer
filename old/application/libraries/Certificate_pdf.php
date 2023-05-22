<?php

include APPPATH . 'third_party/fpdf/fpdf.php';

class Certificate_pdf extends FPDF
{
    private $paper_width = 1300;
    private $paper_height = 859;
    function __construct() {
        parent::__construct('L', 'pt', [$this->paper_width, $this->paper_height]);
    }

    public function apply_data($peserta, $event, $penanggung_jawab, $kode_sertifikat)
    {
        $file_path = FCPATH . 'storage/images/certificate/template.jpg';
        if($event->file_sertifikat) {
            $file_path = FCPATH . 'storage/' . $event->file_sertifikat;
        }
        $this->AddPage();
        [$_type, $mime] = explode('/', strtoupper(mime_content_type($file_path)));

        $this->Image($file_path, 10, 20, $this->paper_width - 20, $this->paper_height - 30, $mime);

        $offset_y = 280;
        $paper_width = $this->paper_width - 70;
        $this->SetFont('Times', 'B', 14);

        $this->SetY(10);
        $this->SetX(10);
        $this->Cell($paper_width, 0, $kode_sertifikat, 0, 2);

        $this->SetY($offset_y);
        $this->SetFont('Times', 'B', 48);
        $this->Cell($paper_width, 0, $peserta->nama, 0, 2, 'C');

        $offset_y += 60;
        $this->SetY($offset_y);
        $this->SetFont('Times', 'IB', 24);
        $this->Cell($paper_width, 0, 'Sebagai Peserta', 0, 2, 'C');

        $offset_y += 40;
        $this->SetY($offset_y);
        $this->SetFont('Times', 'B', 32);
        $this->Cell($paper_width, 0, $event->nama, 0, 2, 'C');

        $offset_y += 34;
        foreach($this->word_wrap($event->tema) as $sentence)
        {
            $offset_y += 46;
            $this->SetY($offset_y);
            $this->SetFont('Times', 'B', 46);
            $this->Cell($paper_width, 0, trim($sentence), 0, 2, 'C');
        }

        $offset_y = 548;
        $this->SetY($offset_y);
        $this->SetFont('Times', '', 32);
        $this->Cell($paper_width, 0, "yang diselenggarakan oleh", 0, 2, 'C');

        $offset_y += 34;
        $this->SetY($offset_y);
        $this->SetFont('Times', 'B', 36);
        $this->Cell($paper_width, 0, "Fakultas Teknologi dan Informatika UNIBI", 0, 2, 'C');

        $offset_y += 40;
        $this->SetY($offset_y);
        $this->SetFont('Times', '', 32);
        $this->Cell($paper_width, 0, id_date_format($event->tanggal_selesai), 0, 2, 'C');

        $offset_y_ttd = $offset_y + 50;

        $total_penanggung_jawab = count($penanggung_jawab);
        foreach($penanggung_jawab as $index => $item)
        {
            $offset_y = $offset_y_ttd;
            $this->SetY($offset_y);
            $this->SetX($paper_width * $index / $total_penanggung_jawab);
            $this->SetFont('Times', 'I', 24);
            $this->Cell($paper_width / $total_penanggung_jawab, 0, $item->jabatan, 0, 2, 'C');
    
            $offset_y += 110;
            $this->SetY($offset_y);
            $this->SetX($paper_width * $index / $total_penanggung_jawab);
            $this->SetFont('Times', 'I', 24);
            $this->Cell($paper_width / $total_penanggung_jawab, 0, $item->nama, 0, 2, 'C');
        }
    }

    private function word_wrap($value, $max = 35)
    {
		$words = explode(' ', $value);
		$sentences = [];
		$current = 0;
		for($i = 0; $i < count($words); $i++)
		{
			if(!array_key_exists($current, $sentences))
			{
				$sentences[$current] = "";
			}
			if(strlen($sentences[$current]) <= 35)
			{
				$sentences[$current] .= trim($words[$i]) . ' ';
			}
			else
			{
				$i--;
				$current++;
			}
        }
        
        return array_values($sentences);
    }
}