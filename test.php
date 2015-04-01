<?php

namespace Www;

class TestA
{
    public function __construct(\DateTime $a)
    {

    }
}

class TestB
{
    public function __construct(TestA $a)
    {

    }
}
