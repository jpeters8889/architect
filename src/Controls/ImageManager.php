<?php

namespace JPeters\Architect\Controls;

class ImageManager extends Control
{
    private $socialImage = true;
    private $mainImage = true;
    private $squareImage = true;
    private $insertImage = false;
    private $insertIntoField;

    public function vuePrefix()
    {
        return 'image-manager';
    }

    public function disableSquareImageOption()
    {
        $this->squareImage = false;

        return $this;
    }

    public function enableImageInserts($intoField)
    {
        $this->insertImage = true;
        $this->insertIntoField = $intoField;

        return $this;
    }

    public function getMetas()
    {
        return array_merge(parent::getMetas() ?? [], [
            'buttons' => [
                'social' => $this->socialImage,
                'main' => $this->mainImage,
                'square' => $this->squareImage,
                'insert' => $this->insertImage,
            ],
            'insertImageIntoField' => $this->insertIntoField,
        ]);
    }
}