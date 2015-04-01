<?php

include_once 'Container.php';
include_once 'test.php';

$c = Syringe\Container::getInstance();

$c->get('www-test-b');
