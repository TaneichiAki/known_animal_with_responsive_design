<?php

class Logger
{
  private string $path;

  public function __construct(string $path)
  {
    $this->path = $path;
  }

  public function setPath(string $path):void
  {
    $this->path = $path;
  }

  public function loggerInfo(string $message):void
  {
    $this->logger($message,"INFO");
  }

  private function logger(string $message,string $level):void
  {
    if(! file_exists($this->path)) $this->mkLog($this->path);

    $d = "[".date('Y-m-d H:i:s')."]";
    $l = "[".$level."]";
    $msg = $d.$l." ".$message."\n";
    error_log($msg,3,$this->path);
  }

  private function mkLog(string $file)
  {
    touch($file);
  }
}
