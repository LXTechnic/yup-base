<?php
// 定义常量
const APP_ROOT = __DIR__;

// 载入基础类
require APP_ROOT . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'Com.php';

// 初始化
\Com::init(APP_ROOT . DIRECTORY_SEPARATOR . 'config.php');


