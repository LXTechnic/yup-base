<?php
namespace Terminal;

/**
 * Description
 *
 * @author andares
 */
class Conf {
    protected $file;

    public $comment = ['#', ';'];

    public $parsed = [];

    public $index = [];

    public $cursor;

    public function __construct($file) {
        $this->file = $file;
    }

    public function parse($file = null) {
        !$file && $file = $this->file;

        $this->cursor = 0;
        $handle = fopen($file, 'r');
        while (!feof($handle)) {
            $line = fgets($handle, 4096);
            $line = $this->parseLine($line);
            $this->parsed[$this->cursor] = $line;

            // 判断是否建立索引
            if (is_array($this->parsed[$this->cursor])) {
                // 增加一个防重复功能
                // 规则是如果遇到的是未注释的覆盖注释的，未注释之间后者覆盖前者
                if (isset($this->index[$line[0]])) {
                    $old = $this->parsed[$this->index[$line[0]]];
                    if ($line[2]) {
                        if ($old[2]) {
                            $this->index[$line[0]] = $this->cursor;
                        }
                    } else {
                        $this->index[$line[0]] = $this->cursor;
                    }
                } else {
                    $this->index[$line[0]] = $this->cursor;
                }
            }

            $this->cursor++;
        }
    }

    protected function parseLine($line) {
        $line = trim($line);
        if (!$line) {
            // 只是空回车
            return $line;
        }

        // 判断是否是变量行
        if (!strpos($line, '=')) {
            // 继续作为注释
            return $line;
        }

        // 作为变量处理
        $data   = explode('=', $line);
        if (count($data) > 2) {
            // 暂不支持多个=的参数调整
            return $line;
        }
        $name   = trim($data[0]);
        $value  = trim($data[1]);

        // 判断是否被注释掉了
        $comment = '';
        if (in_array($name[0], $this->comment)) {
            $comment = $name[0];

            // 去除掉多余的注释符
            $cursor = 0;
            while (in_array($name[$cursor], $this->comment)) {
                $cursor++;
            }
            $name = trim(substr($name, $cursor));
            // 如果处理下来是空串，则同样作为注释处理
            if (!$name) {
                return $line;
            }
        }
        return [$name, $value, $comment];
    }

    public function set($name, $value) {
        if (!isset($this->index[$name])) {
            throw new Error(601, [$name]);
        }

        $cursor = $this->index[$name];
        $this->parsed[$cursor][1] = $value;
    }

    public function enable($name) {
        if (!isset($this->index[$name])) {
            throw new Error(601, [$name]);
        }

        $cursor = $this->index[$name];
        $this->parsed[$cursor][2] = '';
    }

    public function disable($name ,$tag) {
        if (!isset($this->index[$name])) {
            throw new Error(601, [$name]);
        }

        $cursor = $this->index[$name];
        $this->parsed[$cursor][2] = $tag;
    }

    public function append($name, $value, $comment) {
        if (isset($this->index[$name])) {
            throw new Error(602, [$name]);
        }

        $this->parsed[$this->cursor] = '';
        $this->cursor++;
        $this->parsed[$this->cursor] = "$comment Conf appended at " . date('Y-m-d H:i:s', NOW);
        $this->cursor++;
        $this->parsed[$this->cursor] = [$name, $value, ''];
        $this->index[$name] = $this->cursor;
        $this->cursor++;
    }

    public function backup($force = false) {
        $backup = $this->file . '.' . date('Y-m-d', NOW) . '.backup';
        if (!$force && file_exists($backup)) {
            return $backup;
        }
        if (!copy($this->file, $backup)) {
            throw new Error(603);
        }
        return $backup;
    }

    public function save() {
        // 先备份
        $this->backup();

        // 再写入
        $handle = fopen($this->file, 'w');
        foreach ($this->parsed as $line) {
            if (is_array($line)) {
                $line = ($line[2] ? "{$line[2]} " : '') . "{$line[0]} = {$line[1]}";
            }
            fputs($handle, $line . "\n");
        }
        fclose($handle);
    }
}

