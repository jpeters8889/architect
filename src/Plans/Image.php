<?php

namespace JPeters\Architect\Plans;

use Illuminate\Database\Eloquent\Model;
use JPeters\Architect\Contracts\ImageUploaderContract;

class Image extends InternalPlan
{
    /** @var ImageUploaderContract */
    private $imageUploader;
    private $imageDirectory = '';
    private $uploadSubdirectoryColumn;

    public $deferUpdate = true;

    public function __construct($column, $label = null)
    {
        parent::__construct($column, $label);

        $this->imageUploader = resolve(ImageUploaderContract::class);
    }

    public function vuePrefix()
    {
        return 'image';
    }

    public function setImageDirectory($directory)
    {
        $this->imageDirectory = $directory;

        return $this;
    }

    public function setUploadSubdirectoryColumn($column)
    {
        $this->uploadSubdirectoryColumn = $column;

        return $this;
    }

    public function getCurrentValue(Model $model)
    {
        $currentValue = parent::getCurrentValue($model);

        return $this->imageUploader->retrieve($currentValue);
    }

    public function handleUpdate(Model $model, $column, $value)
    {
        if (empty($value)) {
            return;
        }
        
        $image = $this->imageUploader->upload($value, $this->getUploadDirectory($model));

        parent::handleUpdate($model, $column, $image);
    }

    private function getUploadDirectory(Model $model)
    {
        if (!$this->uploadSubdirectoryColumn) {
            return $this->imageDirectory;
        }

        $subdirectory = $model->{$this->uploadSubdirectoryColumn};

        return implode('/', [$this->imageDirectory, $subdirectory]);
    }

    public function requestMethod()
    {
        return 'file';
    }
}
