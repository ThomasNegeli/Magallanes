<?php
namespace Mage;

/*
 * (c) 2011-2015 Andrés Montañez <andres@andresmontanez.com>
 * (c) 2016 by Cyberhouse GmbH <office@cyberhouse.at>
 *
 * This is free software; you can redistribute it and/or
 * modify it under the terms of the MIT License (MIT)
 *
 * For the full copyright and license information see
 * <https://opensource.org/licenses/MIT>
 */

use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Wrapper around Yaml component and yaml PECL extension
 *
 * @author Georg Großberger <georg.grossberger@cyberhouse.at>
 * @copyright (c) 2016 by Cyberhouse GmbH <www.cyberhouse.at>
 */
class Yaml
{
    /**
     * Parse given YAML resouces
     * Must be a string containing a readable file path
     * or the actual YAML source
     *
     * @param string $input
     * @return array|mixed
     */
    public static function parse($input)
    {
        if (function_exists('yaml_parse_file') && function_exists('yaml_parse')) {
            if (strpos($input, "\n") === false && is_file($input)) {
                $result = yaml_parse_file($input);
            } else {
                $result = yaml_parse($input);
            }

            if ($result === false) {
                throw new ParseException('Cannot parse YAML');
            }

            return $result;
        } else {
            return \Symfony\Component\Yaml\Yaml::parse($input);
        }
    }

    public static function dump(array $data, $file)
    {
        if (function_exists('yaml_emit_file')) {
            $success = yaml_emit_file($file, $data, YAML_UTF8_ENCODING, YAML_LN_BREAK);
        } else {
            $success = false;
            $data    = \Symfony\Component\Yaml\Yaml::dump($data, 4, 2);

            if (is_string($data)) {
                $success = file_put_contents($file, $data, LOCK_EX);
            }
        }

        return $success;
    }
}
