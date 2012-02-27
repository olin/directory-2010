<?php
//header("Content-Type: application/opensearchdescription+xml");
$isSSL = @$_SERVER['HTTPS']=="on";
$base = str_replace('opensearch.php','',$_SERVER['REDIRECT_URL']);
$absoluteBase = ($isSSL ? 'https' : 'http').'://'.$_SERVER['SERVER_NAME'].$base;
?>
<?xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
  <ShortName>OlinDirectory</ShortName>
  <Description>A Directory of Olin Students</Description>
  <Url type="text/html" method="get" template="<?php print $absoluteBase;?>?q={searchTerms}"/>
  <Image width="16" height="16"><?php print $absoluteBase;?>img/icon.png</Image>
  <Developer>Jeffrey Stanton</Developer>
  <InputEncoding>UTF-8</InputEncoding>
  <moz:SearchForm><?php print $absoluteBase;?></moz:SearchForm>
</OpenSearchDescription>
