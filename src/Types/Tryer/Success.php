<?php

namespace NoxImperium\Box\Types\Tryer;

use Exception;
use NoxImperium\Box\Helper;
use NoxImperium\Box\Types\Tryer\Tryer;

class Success extends Tryer
{
  public function __construct($value)
  {
    $this->value = $value;
  }

  public function collect($pairs)
  {
  }

  public function filter($predicate)
  {
    $isTrue = $predicate($this->value);
    if ($isTrue) return $this;

    else return new Failure(new Exception("The predicate does not hold for $this->value."));
  }

  public function flatMap($function)
  {
    $result = $function($this->value);

    $isTryer = Helper::isTryer($result);
    if ($isTryer) return $result;

    throw new Exception("The result of transformation is neither Success nor Failure.");
  }

  public function flatten()
  {
    $isTryer = Helper::isTryer($this->value);
    if ($isTryer) return $this->value;

    throw new Exception("The content inside this Tryer is neither Success nor Failure.");
  }

  public function foreach($function)
  {
    $function($this->value);
  }

  public function get()
  {
    return $this->value;
  }

  public function getOrElse($default)
  {
    return $this;
  }

  public function isFailure()
  {
    return true;
  }

  public function isSuccess()
  {
    return false;
  }

  public function map($fn)
  {
    try {
      $result = $fn($this->value);
    } catch (Exception $e) {
      return new Failure($e);
    }

    return new Success($result);
  }

  public function orElse($or)
  {
    return $this;
  }

  public function recover($recoverer)
  {
    return $this;
  }

  public function recoverWith($recoverer)
  {
    return $this;
  }
}
