<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function hestiacp_MetaData()
{
    return array(
        'DisplayName' => 'Hestia CP',
        'APIVersion' => '1.1', // Use API Version 1.1
        'RequiresServer' => true, // Set true if module requires a server to work
    );
}

function hestiacp_ConfigOptions()
{
    $configarray = array(
        "Package Name" => array( "Type" => "text", "Default" => "default"),
        "SSH Access" => array( "Type" => "yesno", "Description" => "Tick to grant access", ),
        "IP Address (optional)" => array( "Type" => "text" ),
       );
       return $configarray;
}

function hestiacp_CreateAccount(array $params)
{
    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-add-user',
          'arg1' => $params["username"],
          'arg2' => $params["password"],
          'arg3' => $params["clientsdetails"]["email"],
          'arg4' => $params["configoption1"],
          'arg5' => $params["clientsdetails"]["firstname"],
          'arg6' => $params["clientsdetails"]["lastname"],
        );
        $postdata = http_build_query($postvars);

        // Create user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);

		logModuleCall('hestiacp','CreateAccount_UserAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

        // Enable ssh access
        if(($answer == 'OK') && ($params["configoption2"] == 'on')) {
            $postvars = array(
              'user' => $params["serverusername"],
              'password' => $params["serverpassword"],
              'hash' => $params["serveraccesshash"],
              'cmd' => 'v-change-user-shell',
              'arg1' => $params["username"],
              'arg2' => 'bash'
            );
            $postdata = http_build_query($postvars);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            $answer = curl_exec($curl);

            logModuleCall('hestiacp','CreateAccount_EnableSSH','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);
        }

        // Add domain
        if(($answer == 'OK') && (!empty($params["domain"]))) {
            $postvars = array(
              'user' => $params["serverusername"],
              'password' => $params["serverpassword"],
              'hash' => $params["serveraccesshash"],
              'cmd' => 'v-add-domain',
              'arg1' => $params["username"],
              'arg2' => $params["domain"],
              'arg3' => $params["configoption3"],
            );
            $postdata = http_build_query($postvars);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
            $answer = curl_exec($curl);

            logModuleCall('hestiacp','CreateAccount_AddDomain','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);
        }
    }

    if($answer == 'OK') {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestiacp_SuspendAccount(array $params)
{
    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-suspend-user',
          'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Susupend user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

	logModuleCall('hestiacp','SuspendAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestiacp_UnsuspendAccount(array $params)
{
    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-unsuspend-user',
          'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Unsusupend user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

    logModuleCall('hestiacp','UnsuspendAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestiacp_TerminateAccount(array $params)
{
    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-delete-user',
          'arg1' => $params["username"]
        );
        $postdata = http_build_query($postvars);

        // Delete user account
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

	logModuleCall('hestiacp','TerminateAccount','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestiacp_ChangePassword(array $params)
{
    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-change-user-password',
          'arg1' => $params["username"],
          'arg2' => $params["password"]
        );
        $postdata = http_build_query($postvars);

        // Change user package
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

	logModuleCall('hestiacp','ChangePassword','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "success";
    } else {
        $result = $answer;
    }
    
    return $result;
}

function hestiacp_ChangePackage(array $params)
{
    // Execute only if there is assigned server
    if ($params["server"] == 1) {

        // Prepare variables
        $postvars = array(
          'user' => $params["serverusername"],
          'password' => $params["serverpassword"],
          'hash' => $params["serveraccesshash"],
          'cmd' => 'v-change-user-package',
          'arg1' => $params["username"],
          'arg2' => $params["configoption1"]
        );
        $postdata = http_build_query($postvars);

        // Change user package
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $answer = curl_exec($curl);
    }

	logModuleCall('hestiacp','ChangePackage','https://'.$params["serverhostname"].':8083/api/'.$postdata,$answer);

    if($answer == 'OK') {
        $result = "success";
    } else {
        $result = $answer;
    }

    return $result;
}

function hestiacp_UsageUpdate(array $params) {

    // Prepare variables
    $postvars = array(
      'user' => $params["serverusername"],
      'password' => $params["serverpassword"],
      'hash' => $params["serveraccesshash"],
      'cmd' => 'v-list-users',
      'arg1' => 'json'
    );
    $postdata = http_build_query($postvars);

    // Get user stats
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://' . $params["serverhostname"] . ':8083/api/');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
    $answer = curl_exec($curl);

    // Decode json data
    $results = json_decode($answer, true);

    // Loop through results and update DB
    foreach ($results AS $user=>$values) {
        update_query("tblhosting",array(
          "diskusage"=>$values['U_DISK'],
          "disklimit"=>$values['DISK_QUOTA'],
          "bwusage"=>$values['U_BANDWIDTH'],
          "bwlimit"=>$values['BANDWIDTH'],
          "lastupdate"=>"now()",
        ),array("server"=>$params['serverid'], "username"=>$user));
    }
}

function hestiacp_ClientArea(array $params)
{
    // Determine the requested action and set service call parameters based on
    // the action.
    $requestedAction = isset($_REQUEST['customAction']) ? $_REQUEST['customAction'] : '';

    if ($requestedAction == 'manage') {
        $serviceAction = 'get_usage';
        $templateFile = 'templates/manage.tpl';
    } else {
        $serviceAction = 'get_stats';
        $templateFile = 'templates/overview.tpl';
    }

    try {
        // Call the service's function based on the request action, using the
        // values provided by WHMCS in `$params`.
        $response = array();

        return array(
            'tabOverviewReplacementTemplate' => $templateFile,
        );
    } catch (Exception $e) {
        // Record the error in WHMCS's module log.
        logModuleCall(
            'hestiacp',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        // In an error condition, display an error page.
        return array(
            'tabOverviewReplacementTemplate' => 'error.tpl',
            'templateVariables' => array(
                'usefulErrorHelper' => $e->getMessage(),
            ),
        );
    }
}
