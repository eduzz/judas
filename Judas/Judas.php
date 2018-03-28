<?php

namespace Judas;

use Adbar\Dot;

class Judas
{
    private $context;
    private $hosts;
    private $app;
    private $category;
    private $subcategory;
    private $configPath;
    private $config;
    private $actual_context;
    private $data;
    private $type;
    private $index;

    public function __construct($connection = null)
    {
        $this->hosts = ($connection == null) ? ['host' => 'localhost', 'port' => '9200', 'scheme' => 'http', 'user' => '', 'pass' => ''] : $connection;

        $this->configPath = __DIR__ . '/judas-config.json';
        $this->config = $this->getConfig();
        $this->index  = $this->config->document;
        $this->type = 'default';
        $this->data = [];
    }

    public function debug($var = '')
    {
        if (defined('DEBUG_JUDAS') && DEBUG_JUDAS == 1) {
            print_r('|'.$var.'--');
        }
    }

    public function getConfig()
    {
        $file = file_get_contents($this->configPath);
        return json_decode($file);
    }

    public function contextValidate($context_str, $data)
    {
        $arrContext = explode('.', $context_str);
        $foundContext = false;

        if (sizeof($arrContext) != 3) {
            return false;
        }

        foreach ($this->config->contexts as $context) {
            if ($context->name == $context_str) {
                $foundContext = true;
                $this->actual_context = $context;
            }
        }

        if (!$foundContext) {
            foreach ($this->config->contexts as $context) {
                if ($context->name == $this->app . '.' . $this->category . '*') {
                    $foundContext = true;
                    $this->actual_context = $context;
                }
            }
        }

        if (!$foundContext) {
            $this->debug('Context not found');
            return false;
        }

        list($this->app, $this->category, $this->subcategory) = explode(".", $context_str);

        $this->context = $context;

        return true;
    }

    public function parseSchema($data)
    {

        $dot = new Dot();

        $dot->set('event.app', $this->app);
        $dot->set('event.category', $this->category);
        $dot->set('event.subcategory', $this->subcategory);
        $dot->set('event.date', date('Y-m-d\TH:i:s\Z'));

        foreach ($this->actual_context->schema as $key => $val) {
            if (isset($data[$val])) {
                $dot->set($val, $data[$val]);
            } else {
                $this->debug($val . ' not found in schema');
            }
        }

        return $dot->all();
    }

    public function log($context = '', $data = [])
    {
        if (!$this->contextValidate($context, [])) {
            $this->debug('--Unknown Context--');
            return;
        }

        $this->data = $this->parseSchema($data);

        $this->save();
    }

    public function save()
    {
        $elastic = new JudasElastic($this->config->host);
        $elastic->put($this->type, $this->index, $this->data);
    }
}
