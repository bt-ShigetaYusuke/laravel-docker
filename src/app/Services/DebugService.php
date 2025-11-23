<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * 呼び出し
 * 
 * use App\Services\DebugService;
 * DebugService::log('ラベル', $memos);
 */
class DebugService
{
  public static function log($label, $var)
  {
    Log::debug($label . ' ' . print_r([
      'value' => $var,
      'type'  => gettype($var),
      'class' => is_object($var) ? get_class($var) : null,
    ], true));
  }
}
