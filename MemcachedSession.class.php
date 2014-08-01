<?php

    // namespace
    namespace Plugin;

    // dependency check
    if (class_exists('\\Plugin\\Config') === false) {
        throw new \Exception(
            '*Config* class required. Please see ' .
            'https://github.com/onassar/TurtlePHP-ConfigPlugin'
        );
    }

    // dependency check
    if (class_exists('\\SMSession') === false) {
        throw new \Exception(
            '*SMSession* class required. Please see ' .
            'https://github.com/onassar/PHP-SecureSessions'
        );
    }

    /**
     * MemcachedSession
     * 
     * Memcached session plugin for TurtlePHP
     * 
     * @author   Oliver Nassar <onassar@gmail.com>
     * @abstract
     */
    abstract class MemcachedSession
    {
        /**
         * _configPath
         *
         * @var    string
         * @access protected
         * @static
         */
        protected static $_configPath = 'config.inc.php';

        /**
         * _reference
         *
         * @var    SMSession
         * @access public
         * @static
         */
        protected static $_reference;

        /**
         * open
         * 
         * @access public
         * @static
         * @return void
         */
        public static function open()
        {
            if (is_null(self::$_reference) === true) {
                require_once self::$_configPath;
                $config = \Plugin\Config::retrieve();
                $config = $config['TurtlePHP-MemcachedSessionPlugin'];
                self::$_reference = (new \SMSession());
                if (HTTPS === true) {
                    self::$_reference->setSecured();
                }
                self::$_reference->setName($config['name']);
                self::$_reference->setHost($config['host']);
                self::$_reference->addServers($config['servers']);
                self::$_reference->open();
            }
        }

        /**
         * setConfigPath
         * 
         * @access public
         * @param  string $path
         * @return void
         */
        public static function setConfigPath($path)
        {
            self::$_configPath = $path;
        }
    }