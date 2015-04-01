<?php

namespace Www;

class TestA
{
    public function __construct()
    {
        echo "Hell no!\n";
    }
}

class TestB
{
    public function __construct(TestA $a)
    {
        echo "Hell yes!\n";
    }
}
