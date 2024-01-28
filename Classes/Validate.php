<?php

class Validate
{
    private bool $passed = false;
    private array $errors = [];
    private DataBase $db;

    public function __construct()
    {
        $this->db = DataBase::getConnect();
    }

    /**
     * @param array $source
     * @param array $items
     * @return object
     */
    public function check(array $source, array $items = []): object
    {

        foreach ($items as $item=>$rules) {
            foreach ($rules as $rule=>$rule_value) {
                $value = $source[$item];
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required!");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value} charsets!");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value} charsets!");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match of {$item}!");
                            }
                            break;
                        case 'unique':
                            $check = $this->db->get($rule_value, [$item => $value]);
                            if ($check) {
                                $this->addError("{$item} all ready exists!");
                            }
                            break;
                        case 'email':
                            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("invalid {$item}!");
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

    /**
     * @param string $name
     * @param int $size
     * @return bool|null
     */
    public function checkFile(string $name, int $size): bool|null
    {
        if ($name === 'png' || $name === 'jpg' && $size < 9000000) {
            return true;
        } else {
            $this->addError('File is not valid!');
        }
        return null;
    }

    /**
     * @param string $error
     * @return void
     */
    public function addError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
     public function passed(): bool
     {
         return $this->passed;
     }

    /**
     * @param string $key
     * @param string $value
     * @param int $id
     * @return bool
     */
     public function checkUser(string $key, string $value, int $id): bool
     {
         $result = $this->db->get('users', [$key => $value]);
         if (!$result) {
             return true;
         }
         if ($result) {
             if ($result[0]->id === $id) {
                 return true;
             } else {
                 $this->addError("{$key} all ready exists!");
             }
         }
         return false;
     }
}