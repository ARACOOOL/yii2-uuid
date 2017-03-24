# aracoool/yii2-uuid

[![Packagist](https://img.shields.io/packagist/dt/aracoool/yii2-uuid.svg?style=flat-square)]()

Yii 2 UUID Extension. A PHP 7.0+ library for generating RFC 4122 version 3, 4, and 5 universally unique identifiers (UUID).


## About

From [Wikipedia](http://en.wikipedia.org/wiki/Universally_unique_identifier):

> The intent of UUIDs is to enable distributed systems to uniquely identify information without significant central coordination. In this context the word unique should be taken to mean "practically unique" rather than "guaranteed unique". Since the identifiers have a finite size, it is possible for two differing items to share the same identifier. The identifier size and generation process need to be selected so as to make this sufficiently improbable in practice. Anyone can create a UUID and use it to identify something with reasonable confidence that the same identifier will never be unintentionally created by anyone to identify something else. Information labeled with UUIDs can therefore be later combined into a single database without needing to resolve identifier (ID) conflicts.

Much inspiration for this library came from the [Java][javauuid] and [Python][pyuuid] UUID libraries.

## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run the following command to install the package and add it as a requirement to your project's `composer.json`:

```bash
composer require aracoool/yii2-uuid
```

## Usage

### Behavior usage for V4

```php
/**
 * @return array
 */
public function behaviors()
{
    return [
        [
            'class' => UuidBehavior::class,
            'version' => Uuid::V4
        ],
        ...
    ];
}
```

### Behavior usage for V3 and V5

```php
/**
 * @return array
 */
public function behaviors()
{
    return [
        [
            'class' => UuidBehavior::class,
            'version' => Uuid::V3, // Uuid::V5
            'namespace' => Uuid::NAMESPACE_NIL,
            'nameAttribute' => 'title' // Value of this attribute SHOULD be unique in your database
        ],
        ...
    ];
}
```

```php
Uuid::v3(Uuid::NAMESPACE_URL, 'http://example.com/');
Uuid::v5(Uuid::NAMESPACE_DSN, 'www.google.com');
Uuid::v3(Uuid::NAMESPACE_OID, '1.3.6.1');
Uuid::v5(Uuid::NAMESPACE_X500, 'c=ca');
Uuid::v4();
```

### Validator usage

```php
/**
 * @return array
 */
 public function rules()
 {
     return [
         ...
         ['attribute_id', '\aracoool\yii2-uuid\UuidValidator'],
         ...
     ];
 }
```
