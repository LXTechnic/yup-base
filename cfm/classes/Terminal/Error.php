<?php
namespace Terminal;

/**
 * Description of Error
 *
 * @author andares
 */
class Error extends \Error\Error {
    protected static $prefix    = 5000;
    protected static $messages  = [
        0   => 'Unkown error',
        1   => 'Args error',

        // Conf类用
        601 => 'Config name not exists',
        602 => 'Config name already exists',
        603 => 'Config backup fail',
    ];
}

