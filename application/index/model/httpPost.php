<?php
namespace app\index\model;
use think\Model;

class httpPost extends Model{
 /**
 * POST 请求 抓取数据

 * @param string $url

 * @param array $param

 * @return string content

 */
	public function httpPost($url, $param) {

	   $oCurl = curl_init ();

	   if (stripos ( $url, "https://" ) !== FALSE) {

	      curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );

	      curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );

	   }

	   if (is_string ( $param )) {

	      $strPOST = $param;

	   } else {

	      $aPOST = array ();

	      foreach ( $param as $key => $val ) {

	         $aPOST [] = $key . "=" . urlencode ( $val );

	      }

	      $strPOST = join ( "&", $aPOST );

	   }

	   curl_setopt ( $oCurl, CURLOPT_URL, $url );

	   curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );

	   curl_setopt ( $oCurl, CURLOPT_POST, true );

	   curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );

	   //curl_setopt($oCurl,CURLOPT_ENCODING,'gzip');//解决乱码
	   
	   //$UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
		// curl_setopt($oCurl, CURLOPT_USERAGENT, $UserAgent); // 使用代理
		// //
		// curl_setopt($oCurl, CURLOPT_HEADER, false);

		// curl_setopt($oCurl, CURLOPT_HEADER, 0);
     	
  		// curl_setopt($oCurl, CURLOPT_COOKIEFILE, $cookie_file);

	   $sContent = curl_exec ( $oCurl );

	   $aStatus = curl_getinfo ( $oCurl );

	   curl_close ( $oCurl );

	   if (intval ( $aStatus ["http_code"] ) == 200) {

	      return $sContent;

	   } else {

	      return false;

	   }

	}


	
}