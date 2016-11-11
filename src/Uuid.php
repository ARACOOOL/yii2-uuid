<?php

namespace aracoool\uuid;

/**
 * Class Uuid
 * @package aracoool\uuid
 */
class Uuid
{
    /**
     * @param int $length
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function randomBytes(int $length)
    {
        if (!is_int($length) || $length < 1) {
            throw new \InvalidArgumentException('Invalid first parameter ($length)');
        }

        return random_bytes($length);
    }

    /**
     * Generate v3 UUID
     *
     * Version 3 UUIDs are named based. They require a namespace (another
     * valid UUID) and a value (the name). Given the same namespace and
     * name, the output is always the same.
     *
     * @param string $namespace
     * @param string $name
     *
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function v3(string $namespace, string $name)
    {
        if (!self::is_valid($namespace)) {
            throw new \InvalidArgumentException($namespace . ' is invalid');
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);
        $nstr = '';
        $nhexLen = strlen($nhex);
        for ($i = 0; $i < $nhexLen; $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        // Calculate hash value
        $hash = md5($nstr . $name, true);
        return self::fromBinary($hash);
    }

    /**
     * Generates a random UUID using the secure RNG.
     *
     * Returns Version 4 UUID format: xxxxxxxx-xxxx-4xxx-Yxxx-xxxxxxxxxxxx where x is
     * any random hex digit and Y is a random choice from 8, 9, a, or b.
     *
     * @throws \InvalidArgumentException
     * @return string the UUID
     */
    public static function v4() : string
    {
        $bytes = self::randomBytes(16);
        return self::fromBinary($bytes);
    }

    /**
     * Generate v5 UUID
     *
     * Version 5 UUIDs are named based. They require a namespace (another
     * valid UUID) and a value (the name). Given the same namespace and
     * name, the output is always the same.
     *
     * @param string $namespace
     * @param string $name
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function v5(string $namespace, string $name)
    {
        if (!self::is_valid($namespace)) {
            throw new \InvalidArgumentException($namespace . ' is invalid');
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(array('-', '{', '}'), '', $namespace);
        $nstr = '';
        $nhexLen = strlen($nhex);
        for ($i = 0; $i < $nhexLen; $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        // Calculate hash value
        $hash = substr(sha1($nstr . $name, true), 0, 16);
        return self::fromBinary($hash);
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public static function hex2Bin(string $uuid)
    {
        return pack('h*', str_replace('-', '', $uuid));
    }

    /**
     * @param $binString
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function fromBinary($binString)
    {
        if (strlen($binString) !== 16) {
            throw  new \InvalidArgumentException('Wrong binary string');
        }

        $string = unpack('h*', $binString);
        return preg_replace('/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/', "$1-$2-$3-$4-$5",
            $string[1]);
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public static function is_valid(string $uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
            '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }
}