<?php
namespace Terminal;

/**
 * 命令行参数解析器
 *
 *
 * @author andares
 */
class Arguments {
    public $master = [];
    public $option = [];

    public $pipe;
    public $repeat;

    protected $current  = '';
    protected $cursor   = [];

    public function __construct($argv = [], $pipe = [], $repeat = []) {
        $this->pipe     = $pipe;
        $this->repeat   = $repeat;

        $argv && $this->parse($argv);
    }

    public function parse($argv) {
        foreach ($argv as $arg) {
            $arg = trim($arg);
            if (!$arg) {
                continue;
            }

            if ($arg[0] == '-') {
                // 重置current
                $this->setCurrent();

                if ($arg[1] == '-') {
                    $arg = explode('=', substr($arg, 2));
                    $name   = trim($arg[0]);
                    $value  = isset($arg[1]) ? trim($arg[1]) : true;
                    if (!$name || $value === '') {
                        throw new Error(1);
                    }
                    $this->option[$name] = $value;
                } else {
                    $this->setCurrent(substr($arg, 1));
                }
            } else {

                if ($this->current) {
                    $pipe   = in_array($this->current, $this->pipe);

                    if (in_array($this->current, $this->repeat)) {
                        if (!isset($this->cursor[$this->current])) {
                            $this->cursor[$this->current] = 0;
                        }
                        if ($pipe) {
                            $this->option[$this->current][$this->cursor[$this->current]][] = $arg;
                        } else {
                            $this->option[$this->current][$this->cursor[$this->current]] = $arg;
                        }
                    } else {
                        if ($pipe) {
                            $this->option[$this->current][] = $arg;
                        } else {
                            $this->option[$this->current] = $arg;
                        }
                    }
                    if (!$pipe) {
                        $this->setCurrent();
                    }
                } else {
                    $this->master[] = $arg;
                }
            }
        }
    }

    protected function setCurrent($name = '') {
        if ($this->current != $name) {
            if (in_array($this->current, $this->repeat)) {
                $this->cursor[$this->current]++;
            }
        }
        $this->current = $name;
    }
}

