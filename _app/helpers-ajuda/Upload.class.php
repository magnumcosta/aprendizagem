<?php

/**
 *  Upload.class [Heleper-Classe_Aux]
 *  Responsável por executar upload de imgs, arquivos e outras mídias
 * @copyright (c) 2018, Magnum ParseString ltda
 */
class Upload {

    private $File;
    private $Name;
    private $Send;

    /** IMAGE UPLOAD */
    private $Width;
    private $Image;

    /** RESULT SET  */ //Serão os resultados ou retornso da nossa classe
    private $Result;
    private $Error;

    /**  DIRETÓRIOS */
    private $Folder; //a palavra folder também descreve uma pasta onde é possível guardar documentos.
    private static $BaseDir;

    function __construct($BaseDir = Null) {
        self::$BaseDir = ((string) $BaseDir ? $BaseDir : "../uploads/");
        if (!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)):
            mkdir(self::$BaseDir, 777);
        endif;
    }

    /*
     * ***************************************
     * **********  PUBLIC METHODS  *********
     * ***************************************
     */

    function getResult() {
        return $this->Result;
    }

    function getError() {
        return $this->Error;
    }

    public function Image(array $Image, $Name = Null, $width = Null, $Folder = Null) {

        $this->File = $Image;
        $this->Name = ((string) $Name ? $Name : substr($Image['name'], 0, strrpos($Image['name'], '.')));
        $this->Width = ((int) $width ? $width : 1024);
        $this->Folder = ((string) $Folder ? $Folder : 'imagens');
        $this->CheckFolder($this->Folder);
        $this->setFileName();
        $this->UploadImagem();
    }

    /*
     * ***************************************
     * ********  PRIVATE METHODS **********
     * ***************************************
     */

    private function CheckFolder($Folder) {

        list($y, $m) = explode('/', date('Y/m'));

        $this->CreateFolder("{$Folder}");
        $this->CreateFolder("{$Folder}/{$y}");
        $this->CreateFolder("{$Folder}/{$y}/{$m}/");
        $this->Send = "{$Folder}/{$y}/{$m}/";
    }

    private function CreateFolder($Folder) {

        if (!file_exists(self::$BaseDir . $Folder) && !is_dir(self::$BaseDir . $Folder)):
            mkdir(self::$BaseDir . $Folder, 0777);
        endif;
    }   

    //Verifica e monta o nome dos arquivos tratando a string!
    private function setFileName() {

        $fileName = Check::Name($this->Name) . strrchr($this->File['name'], '.');
        if (file_exists(self::$BaseDir . $this->Send . $fileName)):
            $fileName = Check::Name($this->Name) . '-' . time() . strrchr($this->File['name'], '.');
        endif;
        $this->Name = $fileName;
    }

    private function UploadImagem() {
        
        switch ($this->File['type']):
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->Image = imagecreatefromjpeg($this->File['tmp_name']);
                break;
            case 'image/png':
            case 'image/x-png':
                $this->Image = imagecreatefrompng($this->File['tmp_name']);
                break;
        endswitch;

        if (!$this->Image):
            $this->Result = False;
            $this->Error = 'Arquivo invalido, envie arquivos provenientes das extensões JPG ou PNG. ';
        else:
            $x = imagesx($this->Image);
            $y = imagesy($this->Image);
            $ImageX = ($this->Width < $x ? $this->Width : $x);
            $ImageH = ($ImageX * $y) / $x;
            $NewImage = imagecreatetruecolor($ImageX, $ImageH);
            imagealphablending($NewImage, false);
            imagesavealpha($NewImage, true);
            imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $ImageX, $ImageH, $x, $y);
             switch ($this->File['type']):
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    imagejpeg($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
                case 'image/png':
                case 'image/x-png':
                    imagepng($NewImage, self::$BaseDir . $this->Send . $this->Name);
                    break;
            endswitch;  
            if (!$NewImage):
                $this->Result = False;
                $this->Error = 'Arquivo invalido, envie arquivos dos tipos JPG ou PNG. ';
            Else:
                $this->Result = $this->Send . $this->Name;
                $this->Error = Null;
            endif;
            imagedestroy($this->Image);
            imagedestroy($NewImage);
        endif;
    }
  

}
