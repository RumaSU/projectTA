<?php

namespace App\Services\QrCodes;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Label\Font\Font;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\ValidationException;

use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\GifWriter;
use Endroid\QrCode\Writer\EpsWriter;
use Endroid\QrCode\Writer\BinaryWriter;


use App\Libraries\Base64Helper;
use Endroid\QrCode\Exception\ValidationException as ExceptionValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Symfony\Component\Mime\MimeTypes;

class EndroidServices {
    
    protected const QR_CODE_ENCODING = 'UTF-8';
    protected const QR_CODE_WRITER = 'png';
    protected const QR_CODE_SIZE = 250;
    protected const QR_CODE_MARGIN = 0;
    protected const QR_CODE_ROUND_BLOCK_SIZE_MODE = 'margin';
    protected const QR_CODE_ERROR_CORRECTION_LEVEL = 'low';
    
    protected QrCode $qrCode;
    
    protected string $data;
    protected ?int $size;
    protected ?int $margin;
    protected Color $forgroundColor;
    protected Color $backgroundColor;
    protected ErrorCorrectionLevel $errorCorrectionLevel;
    protected RoundBlockSizeMode $roundBlockSizeMode;
    
    protected ?Logo $logo = null;
    protected ?Label $label = null;
    
    protected $result;
    
    protected ?string $nameLogo = null;
    
    public function __construct(
        string $data,
        ?int $size = null,
        ?int $margin = null,
        ?Color $forgroundColor = null,
        ?Color $backgroundColor = null,
        ?ErrorCorrectionLevel $errorCorrectionLevel = null,
        ?RoundBlockSizeMode $roundBlockSizeMode = null
        ) {
        
        $this->data = $data;
        $this->size = $size ?? self::QR_CODE_SIZE;
        $this->margin = $margin ?? self::QR_CODE_MARGIN;
        $this->forgroundColor = $forgroundColor ?? new Color(0, 0, 0);
        $this->backgroundColor = $backgroundColor ?? new Color(255, 255, 255, 127);
        
        try {
            $this->errorCorrectionLevel = $errorCorrectionLevel
                ?? ErrorCorrectionLevel::tryFrom(self::QR_CODE_ERROR_CORRECTION_LEVEL);
        } catch (\ValueError $e) {
            $this->errorCorrectionLevel = ErrorCorrectionLevel::Low;
        }
        
        $this->roundBlockSizeMode = $roundBlockSizeMode ?? RoundBlockSizeMode::from(self::QR_CODE_ROUND_BLOCK_SIZE_MODE) ?? RoundBlockSizeMode::Margin;
        $this->instance_qr();
    }
    
    
    protected function instance_qr() {
        $this->qrCode = new QrCode(
            $this->data,
            new Encoding(self::QR_CODE_ENCODING),
            $this->errorCorrectionLevel,
            $this->size,
            $this->margin,
            $this->roundBlockSizeMode,
            $this->forgroundColor,
            $this->backgroundColor
        );
    }
    
    public function getQrCode(): QrCode {
        return $this->qrCode;
    }
    
    public function withLogo (
        ?string $logo = null, 
        int $width = 50, 
        int $height = 50, 
        bool $punchoutBackground= false
        ): self {
        
        if ($this->logo) {
            return $this;
        }
        
        
        $this->nameLogo = 'logo.png';
        $path = Storage::disk('app_qr')->path('logo.png');
        
        if ($logo) {
            
            $image = Base64Helper::extract_image($logo, true);
            
            if ($image) {
                $exts = MimeTypes::getDefault()->getExtensions($image->mime);
                $ext = $exts[0] ?? 'png';
                $name = 'logo_' . Str::random(32) . '.' . $ext;
                Storage::disk('app_qr')->put($name, $image->content);
                
                $this->nameLogo = $name;
                $path = Storage::disk('app_qr')->path($name);
            }
        }
        
        
        $this->logo = new Logo(
            $path,
            $width,
            $height,
            $punchoutBackground
        );
        
        return $this;
    }
    
    public function withLabel (
        string $text,
        ?Font $font = null,
        ?LabelAlignment $alignment = null, 
        ?Margin $margin = null, 
        ?Color $color = null,
        ): self {
        
        if ($this->label) {
            return $this;
        }
        
        $alignment = $alignment ?? LabelAlignment::Center;
        $margin = $margin ?? new Margin(0, 0, 0, 0);
        $color = $color ?? new Color(0, 0, 0);
        $defaultFontPath = resource_path('fonts/poppins/Poppins-Regular.ttf');
        $font = $font ?? new Font($defaultFontPath, 16);
        
        $this->label = new Label(
            $text,
            $font,
            $alignment,
            $margin,
            $color
        );
        
        return $this;
    }
    
    public function write(?string $format = null) {
        
        $format = strtolower($format);
        
        $writerMap = [
            'png' => PngWriter::class,
            'pdf' => PdfWriter::class,
            'svg' => SvgWriter::class,
            'gif' => GifWriter::class,
            'eps' => EpsWriter::class,
            'bin' => BinaryWriter::class
        ];
        
        if (!isset($writerMap[$format])) {
            $format = 'png';
        }
        
        $writer = new $writerMap[$format]();
        $result = $writer->write($this->qrCode, $this->logo, $this->label);
        $this->deleteLogo();
        
        return $result;
    }
    
    
    protected function deleteLogo() {
        if ($this->nameLogo) {
            Storage::disk('app_qr')->delete($this->nameLogo);
        }
    }
    
}