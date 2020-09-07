<?php
function executeRESTCall($method, $url, $data = false)
{
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
  if ($data)
  {
      $head = ['Content-Type: application/json',
               'Accept: application/json',
               'Content-Length: '. strlen($data)];
      curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  }
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  
  return $response;
}
?>