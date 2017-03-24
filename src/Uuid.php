<?php

namespace aracoool\uuid;

/**
 * Class Uuid
 * @package aracoool\uuid
 */
class Uuid
{
    /**
     * @var string DNS namespace from RFC 4122 appendix C.
     */
    const NAMESPACE_DNS = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
    /**
     * @var string URL namespace from RFC 4122 appendix C.
     */
    const NAMESPACE_URL = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
    /**
     * @var string ISO OID namespace from RFC 4122 appendix C.
     */
    const NAMESPACE_OID = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';
    /**
     * @var string X.500 namespace from RFC 4122 appendix C.
     */
    const NAMESPACE_X500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';
    /**
     * @var string NULL UUID string from RFC 4122.
     */
    const NAMESPACE_NIL = '00000000-0000-0000-0000-000000000000';

    const V3 = 'v3';
    const V4 = 'v4';
    const V5 = 'v5';

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
        if (!self::isValid($namespace)) {
            throw new \InvalidArgumentException('Namespace ' . $namespace . ' is invalid');
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        $nhexLen = strlen($nhex);
        for ($i = 0; $i < $nhexLen; $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        return self::fromBinary(md5($nstr . $name, true));
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
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0x0fff) | 0x4000, random_int(0, 0x3fff) | 0x8000, random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff));
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
        if (!self::isValid($namespace)) {
            throw new \InvalidArgumentException('Namespace ' . $namespace . ' is invalid');
        }

        // Get hexadecimal components of namespace
        $nhex = str_replace(['-', '{', '}'], '', $namespace);
        $nstr = '';
        $nhexLen = strlen($nhex);
        for ($i = 0; $i < $nhexLen; $i += 2) {
            $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
        }

        return self::fromBinary(substr(sha1($nstr . $name, true), 0, 16));
    }

    /**
     * @param string $uuid
     * @return string
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
    public static function isValid(string $uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?' .
            '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }
}
