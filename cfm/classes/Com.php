<?php
/**
 *
 * @author ananda
 */

define('NOW',   time());
const OK    = 2;
const DONE  = 1;
const FAIL  = 0;

class Com {
    /**
     * DEBUG状态
     * @var boolean
     */
    public static $debug;

    /**
     * 关闭执行函数
     * @var array
     */
    public static $shutdown_functions = [];

    /**
     *  是否系统为崩溃状态
     * @var bool
     */
    public static $crashed = false;

    /**
     * 系统级使用的全局变量，不建议逻辑层使用
     * @var array
     */
    public static $glob = [];

    /**
     * 初始化应用程序
     * @param type $config
     * @param function $autoload
     * @param function $shutdown_handler
     * @param function $error_handler
     * @param function $exception_handler
     * @throws \Error\Php
     */
    public static function init($config, $autoload = null, $shutdown_handler = null, $error_handler = null, $exception_handler = null)
    {
        // 定义自动载入
        if (!$autoload) {
            $autoload = function($classname) {
                $classname  = \str_replace("\\", DIRECTORY_SEPARATOR, $classname);
                include "$classname.php";
                return \class_exists($classname, false) || \interface_exists($classname, false);
            };
        }
        spl_autoload_register($autoload);
        self::import(APP_ROOT . DIRECTORY_SEPARATOR . 'classes');

        // 自动关闭函数
        if (!$shutdown_handler) {
            $shutdown_handler = function() {
                // 单元测试环境下不启动
                // 该功能暂时关闭
//                if (defined('IN_UNITTEST')) {
//                    return true;
//                }

                ksort(\Com::$shutdown_functions);
                foreach (\Com::$shutdown_functions as $function) {
                    $function();
                }
            };
        }
        register_shutdown_function($shutdown_handler);

        // 注册错误及违例处理
        if (!$error_handler) {
            $error_handler = function($errno, $errstr, $errfile, $errline, $errcontext = []) {
                if (intval(ini_get('log_errors'))) {
                    error_log("PHP Error[$errno]: $errstr in $errfile on line $errline", 0);
                }
                throw new \Error\Php($errstr, 0, $errno, $errfile, $errline, null);
            };
        }
        set_error_handler($error_handler, error_reporting());
        if (!$exception_handler) {
            $exception_handler = function(\Exception $exception) {
                echo "Uncaught exception: " , $exception->getMessage(), "\n";
                if (\Com::$debug) {
                    echo $exception->getTraceAsString();
                }
                // 需要标记系统为出错状态，不再写入数据
                // 这个仅针对使用了shutdown function来写入数据的处理方式，目前无效
                \Com::$crashed = true;
            };
        }
        set_exception_handler($exception_handler);

        // 初始化一些glob数据
        self::$glob['__confirm'] = 0;

        // 载入配置
        $config = include $config;

        // debug设置
        self::$debug = $config['debug'];

        // PHP设置
        foreach ($config['php_ini'] as $key => $value) {
            ini_set($key, $value);
        }
        error_reporting($config['error_reporting']);

        // 全局设置
        $glob_class = $config['glob_class'];
        foreach ($config['glob_vars'] as $name => $value) {
            $glob_class::$$name = $value;
        }
    }

    /**
     * 导入目录
     * @staticvar array $imported
     * @param string $path
     */
    public static function import($path)
    {
        static $imported    = [];

        if (!isset($imported[$path])) {
            $imported[$path]    = 1;
            set_include_path($path.PATH_SEPARATOR.get_include_path());
        }
    }

    public static function sing($name, $object = null) {
        if ($object) {
            self::$glob['__sing'][$name] = $object;
        }
        return isset(self::$glob['__sing'][$name]) ? self::$glob['__sing'][$name] : null;
    }

    /**
     * 读取全局外部配置信息
     * @staticvar array $conf
     * @param string $category
     * @param mixed $id
     * @return mixed
     */
    public static function conf($space, $name, $id = null)
    {
        static $conf = [];

        // 载入
        $category = $space . DIRECTORY_SEPARATOR . $name;
        if (!isset($conf[$category])) {
            $conf[$category] = include APP_ROOT . DIRECTORY_SEPARATOR . 'config' .
                DIRECTORY_SEPARATOR . "$category.php";
        }

        // 是否取子键值
        if ($id !== null) {
            if (!isset($conf[$category][$id])) {
                throw new RuntimeException("Config not exists|$category|$id", 1);
            }
            return $conf[$category][$id];
        }

        return $conf[$category];
    }

    public static function write($vars) {
        if (!is_array($vars)) {
            $vars = [$vars];
        }

        foreach ($vars as $var) {
            echo $var, "\n";
        }
    }

    /**
     * 调试输出函数
     * @staticvar integer $counter
     * @param mixed $var
     * @param string $title
     */
    public static function du($var, $title = null)
    {
        static $counter = 0;
        $counter++;
        if (self::$debug < 2) {
            return true;
        }

        !$title && $title = $counter;
        if (isset($_SERVER['REQUEST_URI'])) {
            echo "<p><strong>Dump #$title</strong></p>";
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        } else {
            echo "\n=== Dump #$title ===\n";
            var_dump($var);
            echo "\n";
        }
    }

    public static function log($var)
    {
        error_log(var_export($var, true));
    }

}

