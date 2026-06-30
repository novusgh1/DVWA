<?php

function dvwaPendoSnippet() {
    $html = '<script>
(function(apiKey){
    (function(p,e,n,d,o){var v,w,x,y,z;o=p[d]=p[d]||{};o._q=o._q||[];
    v=[\'initialize\',\'identify\',\'updateOptions\',\'pageLoad\',\'track\', \'trackAgent\'];for(w=0,x=v.length;w<x;++w)(function(m){
    o[m]=o[m]||function(){o._q[m===v[0]?\'unshift\':\'push\']([m].concat([].slice.call(arguments,0)));};})(v[w]);
    y=e.createElement(n);y.async=!0;y.src=\'https://cdn.pendo.io/agent/static/\'+apiKey+\'/pendo.js\';
    z=e.getElementsByTagName(n)[0];z.parentNode.insertBefore(y,z);})(window,document,\'script\',\'pendo\');
})(\'8b69e142-8c9a-4615-b63d-56065dfe1c30\');
</script>';

    if (function_exists('dvwaIsLoggedIn') && dvwaIsLoggedIn()) {
        $username = dvwaCurrentUser();
        $visitorData = dvwaPendoGetVisitorData($username);
        $html .= "\n" . '<script>' . "\n" . 'pendo.initialize(' . json_encode(array('visitor' => $visitorData)) . ');' . "\n" . '</script>';
    } else {
        $html .= "\n" . '<script>' . "\n" . 'pendo.initialize({ visitor: { id: \'\' } });' . "\n" . '</script>';
    }

    return $html;
}

function dvwaPendoGetVisitorData($username) {
    global $_DVWA;

    $visitorData = array(
        'id' => $username,
        'full_name' => $username,
        'userId' => 0,
        'firstName' => '',
        'lastName' => '',
        'user' => $username,
        'avatar' => '',
        'lastLogin' => '',
        'failedLogin' => 0,
        'role' => '',
        'accountEnabled' => true
    );

    $conn = null;
    $ownConnection = false;

    if (isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) {
        $conn = $GLOBALS["___mysqli_ston"];
    } elseif (isset($_DVWA['db_server'])) {
        $conn = @mysqli_connect(
            $_DVWA['db_server'],
            $_DVWA['db_user'],
            $_DVWA['db_password'],
            $_DVWA['db_database'],
            $_DVWA['db_port']
        );
        if ($conn) {
            $ownConnection = true;
        }
    }

    if ($conn) {
        $escapedUser = mysqli_real_escape_string($conn, $username);
        $result = @mysqli_query($conn, "SELECT * FROM users WHERE user = '{$escapedUser}' LIMIT 1");
        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            $visitorData['id'] = isset($row['user_id']) ? (string)$row['user_id'] : $username;

            $firstName = isset($row['first_name']) ? $row['first_name'] : '';
            $lastName = isset($row['last_name']) ? $row['last_name'] : '';
            $visitorData['full_name'] = trim($firstName . ' ' . $lastName);

            if (isset($row['user_id'])) $visitorData['userId'] = (int)$row['user_id'];
            if (isset($row['first_name'])) $visitorData['firstName'] = $row['first_name'];
            if (isset($row['last_name'])) $visitorData['lastName'] = $row['last_name'];
            if (isset($row['user'])) $visitorData['user'] = $row['user'];
            if (isset($row['avatar'])) $visitorData['avatar'] = $row['avatar'];
            if (isset($row['last_login']) && $row['last_login']) {
                $ts = strtotime($row['last_login']);
                $visitorData['lastLogin'] = $ts !== false ? date('c', $ts) : $row['last_login'];
            }
            if (isset($row['failed_login'])) $visitorData['failedLogin'] = (int)$row['failed_login'];
            if (isset($row['role'])) $visitorData['role'] = $row['role'];
            if (isset($row['account_enabled'])) $visitorData['accountEnabled'] = (bool)$row['account_enabled'];
        }

        if ($ownConnection) {
            @mysqli_close($conn);
        }
    }

    return $visitorData;
}

?>
