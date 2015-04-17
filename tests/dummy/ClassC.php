<?php

namespace DummyServices;

class ClassC
{
    public function __construct(ClassD $d)
    {
        echo 'I\'m C!';
    }
}
