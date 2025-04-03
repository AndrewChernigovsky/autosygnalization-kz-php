<?php

namespace DATA;

class SelectData
{
    public function getData()
    {

        $data = [
          [
            "name" => 'Название',
            "value" => 'name',
            "class" => 'select-item default',
          ],
          [
            "name" => 'Цена',
            "value" => 'price',
            "class" => 'select-item',
          ],
        ];

        return $data;

    }
}
