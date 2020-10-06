<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

class Qrcodes extends CI_Controller {
    
    public function index() {       
		$qrCode = new QrCode('Life is too short to be generating QR codes');
		
		header('Content-Type: '.$qrCode->getContentType());
		echo $qrCode->writeString();
    }
}