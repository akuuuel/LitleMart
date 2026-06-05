<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class HelpController extends Controller {
    public function index() {
        return $this->view('help.index', ['title' => 'Pusat Bantuan - LitleMart']);
    }

    public function career() {
        return $this->view('help.career', ['title' => 'Karir - LitleMart']);
    }

    public function contact() {
        return $this->view('help.contact', ['title' => 'Hubungi Kami - LitleMart']);
    }

    public function terms() {
        return $this->view('help.terms', ['title' => 'Syarat & Ketentuan - LitleMart']);
    }

    public function privacy() {
        return $this->view('help.privacy', ['title' => 'Kebijakan Privasi - LitleMart']);
    }

    public function vendorHelp() {
        return $this->view('vendor.help', ['title' => 'Vendor Help Center - LitleMart']);
    }

    public function sendCv() {
        $name = $_POST['name'] ?? '';
        $position = $_POST['position'] ?? '';
        $cvLink = $_POST['cv_link'] ?? '';
        $intro = $_POST['intro'] ?? '';

        $waNumber = '6285343869700'; // Nomor tujuan hardcoded di backend demi keamanan
        $message = "Halo Tim Karir LitleMart, saya ingin melamar pekerjaan.\n\n"
                 . "*Nama:* {$name}\n"
                 . "*Posisi:* {$position}\n"
                 . "*Link CV/Portofolio:* {$cvLink}\n"
                 . "*Perkenalan:* {$intro}";

        $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($message);
        
        return $this->redirect($waUrl);
    }

    public function askCulture() {
        $name = $_POST['name'] ?? '';
        $question = $_POST['question'] ?? '';

        $waNumber = '6285343869700';
        $message = "Halo LitleMart, saya {$name} ingin bertanya tentang budaya kerja.\n\n"
                 . "*Pertanyaan:* {$question}";

        $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode($message);
        
        return $this->redirect($waUrl);
    }
}
