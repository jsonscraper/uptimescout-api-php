<?php
declare(strict_types=1);
namespace UptimeScout;
final class Client {
 public function __construct(private string $licenseKey,private string $baseUrl='https://api.uptimescout.com',private int $timeout=30){if($licenseKey===''||preg_match('/[\r\n]/',$licenseKey))throw new \InvalidArgumentException('A license key is required.');}
 public function request(string $method,array $parameters=[]):array {if(!in_array($method,['services','status','statuses','summary','history','stats','incidents'],true))throw new \InvalidArgumentException('Unknown method.');foreach($parameters as &$value)if(is_array($value))$value=implode(',',$value);unset($value);$c=curl_init(rtrim($this->baseUrl,'/').'/v1/'.$method.'?'.http_build_query($parameters));curl_setopt_array($c,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>$this->timeout,CURLOPT_HTTPHEADER=>['Accept: application/json','Authorization: Bearer '.$this->licenseKey]]);try{$raw=curl_exec($c);$status=(int)curl_getinfo($c,CURLINFO_RESPONSE_CODE);if($raw===false)throw new \RuntimeException('UptimeScout connection failed.');$body=json_decode($raw,true,128,JSON_THROW_ON_ERROR);if($status!==200||($body['status']??'')==='fail')throw new \RuntimeException('UptimeScout request failed (HTTP '.$status.').');return $body;}finally{curl_close($c);}}
 public function services(array $p=[]):array{return $this->request('services',$p);}
 public function status(string $service,array $p=[]):array{return $this->request('status',array_merge($p,['service'=>$service]));}
 public function statuses(array $services,array $p=[]):array{return $this->request('statuses',array_merge($p,['services'=>$services]));}
 public function summary(array $p=[]):array{return $this->request('summary',$p);}
 public function history(string $service,array $p=[]):array{return $this->request('history',array_merge($p,['service'=>$service]));}
 public function stats(string $service,array $p=[]):array{return $this->request('stats',array_merge($p,['service'=>$service]));}
 public function incidents(array $p=[]):array{return $this->request('incidents',$p);}
}
