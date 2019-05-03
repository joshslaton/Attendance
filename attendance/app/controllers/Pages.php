<?php
namespace Core;

class Pages {

  function __construct(){

  }

  function __destruct(){

  }

  function renderPage(){
    echo Registry::$registry['page_html'];
  }
}
?>
