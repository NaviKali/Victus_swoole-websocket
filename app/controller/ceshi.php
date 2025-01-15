<?php

namespace app\controller;

class ceshi
{
    public function demo(array $data)
    {
        var_dump($data[0]);


        return json_encode($data[0]);

    }
}