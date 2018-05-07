<?php

namespace framework\aggregateConfig;

class aggregateConfig implements aggregateConfigInterface {

    private $config = Array();

    public function setConfig(array $config, array $namespace = array(), bool $overwrite = false) {

        $data = &$this->config;

        foreach ($namespace AS $item) {
            if (!isset($data[$item]))
                $data[$item] = Array();
            $data = &$data[$item];
        }


        if (!$overwrite)
            $data = $this->mergeConfig($config, $data);
        else
            $data = array_replace_recursive($data, $config);
    }


    private function mergeConfig(array $data, array $config)
    {

        $arr = array_diff_key($data, $config);
        foreach ($config AS $key => $item) {
            if (!isset($data[$key]) || !is_array($data[$key]) || !is_array($item))
                $arr[$key] = $item;
            else
                $arr[$key] = $this->mergeConfig($data[$key], $item);
        }
        return $arr;
    }


    public function getConfig()
    {
        $arr = $this->config;
        foreach (func_get_args() AS $key) {
            if (isset($arr[$key]))
                $arr = $arr[$key];
            else
                return false;
        }
        return $arr;
    }
}