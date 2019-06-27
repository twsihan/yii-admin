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
    private $upload = '@webroot/upload/';
    private $url = '@web/upload/';


    /**
     * @inheritdoc
     */
    public function getUpload()
    {
        return $this->upload;
    }

    /**
     * @inheritdoc
     */
    public function setUpload($upload)
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

    /**
     * Returns an uploaded file for the given model attribute.
     * The file should be uploaded using [[\yii\widgets\ActiveField::fileInput()]].
     *
     * @param \yii\base\Model $model the data model
     * @param string $attribute the attribute name. The attribute name may contain array indexes.
     * For example, '[1]file' for tabular file uploading; and 'file[1]' for an element in a file array.
     *
     * @return null|UploadedFile the instance of the uploaded file.
     * Null is returned if no file is uploaded for the specified model attribute.
     * @see getInstanceByName()
     */
    public static function getInstance($model, $attribute)
    {
        return parent::getInstance($model, $attribute);
    }

    /**
     * Saves the uploaded file.
     * Note that this method uses php's move_uploaded_file() method. If the target file `$file`
     * already exists, it will be overwritten.
     *
     * @param string $file the file path used to save the uploaded file
     * @param bool $deleteTempFile whether to delete the temporary file after saving.
     * If true, you will not be able to save the uploaded file again in the current request.
     *
     * @return bool true whether the file is saved successfully
     * @see error
     */
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
