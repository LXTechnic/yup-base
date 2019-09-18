<?php
namespace Error;

/**
 * Description of Error
 *
 * @author andares
 */
class Error extends \Exception {
    protected static $prefix    = 0;
    protected static $messages  = [0 => 'Unkown error',];
    protected $extra;

    public function __construct ($code = 0, $extra = [], $previous = null) {
        $called = get_called_class();
        !isset($called::$messages[$code]) && $code = 0;
        $message = $called::$messages[$code];
        $code   += $called::$prefix;

        $this->extra = $extra;
        // 转义message方便调试
        \Com::$debug && $this->extra && $message .= '|' . (is_array($this->extra) ? implode('|', $this->extra) : $this->extra);

        parent::__construct($message, $code, $previous);
    }

    public function getExtra() {
        return $this->extra;
    }
}

