<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace lib\internal\Mageplaza\Hybrid\Exception;

/**
 * BadMethodCallException
 *
 * Exception thrown if a callback refers to an undefined method or if some arguments are missing.
 */
class BadMethodCallException extends RuntimeException implements ExceptionInterface
{
}
