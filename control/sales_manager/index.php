<?php
require_once('../settings.php');
require_once(NMG_SERVER_CONTROL."lib/lead.php");
$lead_manager = new lead();
$lead_manager->show();