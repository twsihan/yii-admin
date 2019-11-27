<?php

namespace twsihan\admin\components\web;

use twsihan\yii\helpers\FileHelper;
use Yii;

/**
 * Class UploadedFile
 *
 * @package twsihan\admin\components\web
 * @author twsihan <twsihan@gmail.com>
 */
class UploadedFile extends \yii\web\UploadedFile
{
    /**
     * @var string Upload file path
     */
    private $upload = '@webroot/upload';
    private $url = '@web/upload';


    /**
     * @inheritdoc
     */
    public function getUpload(): string
    {
        return $this->upload;
    }

    /**
     * @inheritdoc
     */
    public function setUpload($upload): void
    {
        $this->upload = $upload;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function saveAs($file, $deleteTempFile = true)
    {
        if ($this->error == UPLOAD_ERR_OK) {
            $file = Yii::getAlias($this->upload) . $file;
            if (FileHelper::createDirectory(dirname($file))) {
                if ($deleteTempFile) {
                    return move_uploaded_file($this->tempName, $file);
                } elseif (is_uploaded_file($this->tempName)) {
                    return copy($this->tempName, $file);
                }
            }
        }
        return false;
    }
}
