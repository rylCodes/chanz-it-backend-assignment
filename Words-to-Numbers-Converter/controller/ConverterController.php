<?php

require_once 'model/ConverterModel.php';

class ConverterController {
    private $model;

    public function __construct() {
        $this->model = new ConverterModel();
    }

    public function wordsToNumber($words) {
        $words = strtolower($words);    
        $words = preg_replace('/\s+/', ' ', $words);
        $words = preg_replace('/[^a-z]+/', ' ', $words);
        $words = preg_replace('/(^\s+|\s+$)/', '', $words);

        return $this->model->wordsToNumber($words);
    }

    public function numberToWords($number) {
        return $this->model->numberToWords($number);
    }

    public function convertToUSD($amount) {
        return $this->model->convertToUSD($amount);
    }
}