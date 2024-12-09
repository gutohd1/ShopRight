<?php

namespace App\Models;

use App\Models\Base\Model;
use Predis\Client;

class Products extends Model
{
    const TABLE_NAME = 'products';

    protected Client $redis;

    public function __construct()
    {
        global $redis;
        $this->redis = $redis;
    }

    public function getProductById($id): Object|null
    {
        return $this->read()->where('id', $id)->get();
    }

    public function update(array $args): bool
    {
        $allStock = $this->getAll();
        if (count($allStock) > 0) {
            foreach ($args as $arg) {
                $rowIndex = array_search($arg['id'], array_column($allStock, 'id'));

                $row = (array)$allStock[$rowIndex];
                $row['stock'] = $arg['stock'];
                $allStock[$rowIndex] = $row;
            }
            if ($this->write($allStock)) {
                return true;
            }
        }
        return false;
    }

    public function read(): Products
    {
        if ($this->redis->exists(self::TABLE_NAME) && ! empty($this->redis->get(self::TABLE_NAME))) {
            $this->content = json_decode($this->redis->get(self::TABLE_NAME), true);
        } elseif (isset($_SESSION[self::TABLE_NAME]) && !empty($_SESSION[self::TABLE_NAME])) {
            $this->content = $_SESSION[self::TABLE_NAME];
        } else {
            parent::read();
            $this->redis->set(self::TABLE_NAME, json_encode($this->content));
            $_SESSION[self::TABLE_NAME] = $this->content;
        }
        return $this;
    }

    protected function write($content): bool
    {
        $_SESSION[self::TABLE_NAME] = $content;
        $this->redis->set(self::TABLE_NAME, json_encode($content));
        return parent::write($content);
    }

}
