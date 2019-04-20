<?php

namespace Bendt\Autocms\Classes;

use Bendt\Autocms\Models\Language;

class LanguageManager
{
    private $languages = null;
    private $count = null;

    public function get($locale)
    {
        if(is_null($this->languages)) {
            $this->languages = Language::all();
            $this->count = count($this->languages);
        }
        foreach ($this->languages as $row) {
            if($row->iso == $locale) return $row->name;
        }

        return 'Unknown';
    }

    public function getAll()
    {
        if(is_null($this->languages)) {
            $this->languages = Language::all();
            $this->count = count($this->languages);
        }
        return $this->languages;
    }

    public function count(){
        if(is_null($this->count)) {
            $this->languages = Language::all();
            $this->count = count($this->languages);
        }
        return $this->count;
    }
}
