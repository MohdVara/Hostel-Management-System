<?php
require_once'core/init.php';

$user = new User();
$user->logout();

Session::flash('error','You have successfully logout');

Redirect::to('login.php');

?>