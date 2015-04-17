<?php

namespace DummyServices;

class ClassD
{
    public function __construct(ClassC $d)
    {
        echo 'I\'m D!';
    }
}
