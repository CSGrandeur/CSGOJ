<?php
$DB_HOSTNAME    = getenv('DB_HOSTNAME');
$DB_DATABASE    = getenv('DB_DATABASE');
$DB_USERNAME    = getenv('DB_USERNAME');
$DB_PASSWORD    = getenv('DB_PASSWORD');
$DB_HOSTPORT    = getenv('DB_HOSTPORT');
$PASS_ADMIN     = getenv('PASS_ADMIN');
$PASS_JUDGER    = getenv('PASS_JUDGER');

$maxTries = 10;
$tries = 0;
$mysqli = null;
while ($tries < $maxTries) {
    try{
        $mysqli = new mysqli($DB_HOSTNAME, $DB_USERNAME, $DB_PASSWORD, "", $DB_HOSTPORT);

        if ($mysqli->connect_error) {
            throw new Exception($mysqli->connect_error, $mysqli->connect_errno);
        }
        echo "连接数据库成功\n";
        break;
    } catch (Exception $e) {
        if ($tries === $maxTries - 1) {
            die("连接数据库失败: " . $e->getMessage() . "\n");
        } else {
            echo "等待数据库启动\n";
            sleep(10); // Wait for 10 seconds before retrying
            $tries++;
        }
    }
}
$result = $mysqli->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$DB_DATABASE'");
if ($result->num_rows == 0) {
    if(!$mysqli->query("CREATE DATABASE IF NOT EXISTS `$DB_DATABASE` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;")) {
        die("建立数据库`$DB_DATABASE`失败\n");
    }
    $mysqli->select_db($DB_DATABASE);
    $dir = '/SQL';
    $files = scandir($dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) == 'sql') {
            $sql = file_get_contents($dir . '/' . $file);
            if(!$mysqli->multi_query($sql)) {
                die("导入" . $file . "失败\n");
            } else {
                echo "导入" . $file . "\n";
                while ($mysqli->more_results() && $mysqli->next_result()) {
                    // Waiting Finish
                }
            }
        }
    }
} else {
    $mysqli->select_db($DB_DATABASE);
}
if ($mysqli->errno) {
    die("数据库操作失败：" . $mysqli->errno);
}
$result = $mysqli->query("SELECT * FROM `users` WHERE `user_id` = 'admin'");
if ($result->num_rows == 0) {
    $password = password_hash($PASS_ADMIN, PASSWORD_DEFAULT);
    $mysqli->query("INSERT INTO `users` (`user_id`, `email`, `submit`, `solved`, `defunct`, `ip`, `accesstime`, `volume`, `language`, `password`, `reg_time`, `nick`, `school`) VALUES ('admin', 'admin@admin.admin', 0, 0, 'N', '127.0.0.1', NOW(), 1, 1, '$password', NOW(), 'XCPC', 'CSGOJ');");
}

$result = $mysqli->query("SELECT * FROM `privilege` WHERE `user_id` = 'admin'");
if ($result->num_rows == 0) {
    $mysqli->query("INSERT INTO `privilege` (`user_id`, `rightstr`, `defunct`) VALUES ('admin', 'super_admin', 'N');");
}

$result = $mysqli->query("SELECT * FROM `users` WHERE `user_id` = 'judger'");
if ($result->num_rows == 0) {
    $password = password_hash($PASS_JUDGER, PASSWORD_DEFAULT);
    $mysqli->query("INSERT INTO `users` (`user_id`, `email`, `submit`, `solved`, `defunct`, `ip`, `accesstime`, `volume`, `language`, `password`, `reg_time`, `nick`, `school`) VALUES ('judger', '@', 0, 0, 'N', '', NULL, 1, 1, '$password', NOW(), 'judger', 'CSGOJ');");
}

$result = $mysqli->query("SELECT * FROM `privilege` WHERE `user_id` = 'judger'");
if ($result->num_rows == 0) {
    $mysqli->query("INSERT INTO `privilege` (`user_id`, `rightstr`, `defunct`) VALUES ('judger', 'judger', 'N');");
}

$mysqli->close();
echo "数据库初始化完毕\n";
?>
