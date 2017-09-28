<?php

class Validate
{

    private $passed = false;
    private $errors = [];
    private $db = null;

    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    //$item - 1st array level value, $rules - nested values array,
    // $rule - key nested array and value is $ruleValue
    //$value is our post||get parameters
    public function check($source, $items = []) {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $ruleValue) {
                $value = $source[$item];

                if ($rule === 'required' && empty($value)) {
                    $this->addErrors("{$item} is required");
                } else if (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $ruleValue) {
                                $this->addErrors("{$item} must be minimum {$ruleValue}
                                characters");
                            }
                        break;
                        case 'max':
                            if (strlen($value) > $ruleValue) {
                                $this->addErrors("{$item} must be maximum {$ruleValue}
                                characters");
                            }
                        break;
                        case 'unique':
                            $check = $this->db->get($ruleValue, [$item, '=', $value]);
                            if ($check->count()) {
                                $this->addErrors("{$item} already exist");
                            }
                        break;
                        case 'matches':
                            if ($value != $source[$ruleValue]) {
                                $this->addErrors("{$ruleValue} must be match {$item}");
                            }
                        break;
                    }
                }
            }
        }
        if (empty($this->errors)) {
            $this->passed = true;
        }

        return $this;
    }
//adding errors if they exist
    private function addErrors($error)
    {
        $this->errors[] = $error;
    }
// get errors array
    public function errors()
    {
       return $this->errors;
    }
//if errors not exist, we are continue to perform code | passed must be equal true
    public function passed()
    {
        return $this->passed;
    }
}