<?php

namespace App\Services\QrCodes;

use chillerlan\Settings\SettingsContainerInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\Output\QRGdImagePNG;

class ChillerlanServices {
    
    public function test() {
        SettingsContainerInterface::class;
        $qr = new QRCode();
        $options = new QROptions();
        
        
        $options->version = 1;
        
    }
    
    
}