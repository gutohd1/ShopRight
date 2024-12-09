<?php

namespace App\Models\Base;

use App\Models\Logs;
use ReflectionClass;
use stdClass;
use \Exception;

class Model
{
    protected array|stdClass $content = [];

    protected Logs $logs;

    public function __construct()
    {
        //$this->logs = new Logs();
    }

    public function read(): Model
    {
        $function = new ReflectionClass($this);
        try {
            $file = BASE_PATH . '/storage/json/' . strtolower($function->getShortName()) . '.json';
            if (! file_exists($file)) {
                throw new Exception('json file not found');
            }
            $fileContent = @file_get_contents(
                $file
            );
            $fileContent = json_decode($fileContent);
            if ($fileContent) {
                $this->content = $fileContent;
            } else {
                throw new Exception('Json file not properly formatted');
            }
        } catch (\Exception $e){
            error_log(PHP_EOL . json_encode([
                'function' => $function->getShortName(),
                'type' => 'read',
                'error' => $e->getMessage()
            ]), 3, BASE_PATH . '/storage/logs/error.log');
        }
        return $this;
    }

    protected function write($content): bool
    {
        $function = new ReflectionClass($this);
        try {
            $file = BASE_PATH . '/storage/json/' . strtolower($function->getShortName()) . '.json';
            if (! file_exists($file)) {
                throw new Exception('json file not found');
            }
            $file = file_put_contents(
                $file,
                json_encode($content)
            );
            if ($file) {
                return true;
            } else {
                throw new Exception('Incorrect json format');
            }

        } catch (\Exception $e) {
            error_log(PHP_EOL . json_encode([
                'function' => $function->getShortName(),
                'type' => 'write',
                'error' => $e->getMessage()
            ]), 3, BASE_PATH . '/storage/logs/error.log');
        }
        return false;
    }

    public function get(): array|stdClass|null
    {
        if (count((array)$this->content) > 0) {
            return $this->content;
        }
        return null;
    }

    public function where(string $column, string $value): Object
    {
        if (count($this->content) > 0) {
            $id = array_search($value, array_column($this->content, $column));
            if ($id !== false) {
                $this->content = (object)$this->content[$id];
            }
        }
        return $this;
    }

    public function create(array $order): int|bool
    {
        $this->read();
        $this->content[] = $order;
        if ($this->write($this->content)) {
            return array_key_last($this->content);
        }
        return false;
    }

    public function getAll(): array
    {
        $all = $this->read()->get();
        if (is_object($all)) {
            return [$all];
        }
        return $all;
    }
}
