<?php 
require 'Library/common.php';
error_reporting(E_ALL ^E_NOTICE);
$id=$_GET['id'];
$re=$M->table('rules')->where(array('id'=>$id))->delete();
header("Location:manage.php");