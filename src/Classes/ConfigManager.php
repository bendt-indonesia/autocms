<?php

namespace Bendt\Autocms\Classes;

use Illuminate\Support\Facades\DB;
use Bendt\Autocms\Exceptions\NoConfigFoundException;
use Bendt\Autocms\Models\Config as Model;

class ConfigManager
{
    private $_configs = null;

    private function _getConfigs()
    {
        if(is_null($this->_configs))
        {
            $this->_fetchConfigsFromDatabase();
        }

        return $this->_configs;
    }

    public function getAll($withModel = false)
    {
        if($withModel) {
            return $this->_getConfigs();
        }
        else {
            $key_value = [];
            foreach ($this->_getConfigs() as $key => $config)
            {
                $key_value[$key] = $config->value;
            }
            return $key_value;
        }
    }

    public function get($key, $default = null)
    {
        return $this->isExist($key) ? nl2br($this->_configs[$key]->value) : $default;
    }

    public function getOrFail($key)
    {
        if($this->isExist($key)) {
            return $this->_configs[$key]->value;
        }
        else {
            throw new NoConfigFoundException($key);
        }
    }

    public function getModel($key)
    {
        return $this->_configs[$key];
    }

    public function hasValue($key)
    {
        $value = $this->get($key);
        return !is_null($value) && $value != '';
    }

    public function isExist($key)
    {
        $configs = $this->_getConfigs();
        return array_key_exists(strtolower($key), $configs);
    }

    public function update($input)
    {
        // Populate config
        if(is_null($this->_configs))
        {
            $this->_fetchConfigsFromDatabase();
        }

        return DB::transaction(function() use($input)
        {
            foreach ($input as $key => $value)
            {
                if($this->isExist($key)) {
                    $config = $this->getModel($key);
                    if($config->value != $value) {
                        $config->value = trim($value) == '' ? null : $value;
                        $config->save();
                    }
                }
            }

            $this->_fetchConfigsFromDatabase();
        });
    }


    public function text($key, $label = null, $extra_info = null)
    {
        return $this->_render($key, $label, 'text', $extra_info);
    }

    public function textarea($key, $label = null, $extra_info = null)
    {
        return $this->_render($key, $label, 'textarea', $extra_info);
    }

    private function _render($key, $label, $view, $extra_info)
    {
        $data = ['key' => $key, 'label' => $label, 'value' => $this->getOrFail($key)];
        if($extra_info) {
            $data = array_merge($data, $extra_info);
        }

        return view("autocms::config.form-group-{$view}", $data);
    }

    private function _fetchConfigsFromDatabase()
    {
        $list = Model::all();
        $this->_configs = [];
        foreach($list as $item)
        {
            $this->_configs[strtolower($item->name)] = $item;
        }
    }
}
