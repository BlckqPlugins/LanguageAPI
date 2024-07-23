<?php

namespace LanguageAPI\LanguageAPI\utils;

class Language {

    protected string $_name;
    protected string $_locale;
    protected string $_prefix;
    protected mixed $_values;

    public function __construct(string $name, string $locale, string $prefix, $values)
    {
        $this->_name = $name;
        $this->_locale = $locale;
        $this->_prefix = $prefix;
        $this->_values = $values;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->_locale;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->_prefix;
    }

    /**
     * @return mixed
     */
    public function getValues(): mixed
    {
        return $this->_values;
    }
}