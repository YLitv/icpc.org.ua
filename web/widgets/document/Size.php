<?php

namespace web\widgets\document;

/**
 * Renders document size
 */
class Size extends \web\ext\Widget
{

    /**
     * Document
     * @var \common\models\document
     */
    public $document;

    /**
     * Run widget
     */
    public function run()
    {
        if ($this->document->file !== null) {
            $size = $this->document->file->getSize();
        } else {
            $size = 0;
        }

        if ($size < BYTES_IN_KB) {
            $sizeLabel = $size . ' b';
        } elseif ($size < BYTES_IN_MB) {
            $sizeLabel = number_format($size / BYTES_IN_KB, 2) . ' kb';
        } elseif ($size < BYTES_IN_GB) {
            $sizeLabel = number_format($size / BYTES_IN_MB, 2) . ' mb';
        } else {
            $sizeLabel = number_format($size / BYTES_IN_GB, 2) . ' gb';
        }
        
        echo $sizeLabel;
    }

}