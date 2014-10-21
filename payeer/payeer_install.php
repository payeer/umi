<?php 
$ini_array = parse_ini_file("config.ini", true);

$host = $ini_array['connections']['core.host'];
$user = $ini_array['connections']['core.login'];
$pass = $ini_array['connections']['core.password'];
$dbase = $ini_array['connections']['core.dbname'];

$link = mysql_connect($host,$user,$pass);

mysql_select_db($dbase);
mysql_set_charset('utf8');

mysql_query("SELECT @parent_id:=id FROM `cms3_object_types` WHERE `guid`='emarket-payment'");
mysql_query("SELECT @hierarchy_type_id:=id FROM `cms3_hierarchy_types` WHERE `name`='emarket' AND `ext`='payment'");
mysql_query("SELECT @type_id:=id FROM `cms3_object_types` WHERE `guid`='emarket-paymenttype'");
mysql_query("SELECT @payment_type_id:=id FROM `cms3_object_fields` WHERE `name`='payment_type_id'");

mysql_query("INSERT INTO `cms3_object_types` VALUES(NULL, 'emarket-payment-payeer', 'payeer', 1, @parent_id, 0, 0, @hierarchy_type_id, 0)");
mysql_query("SET @obj_type = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_import_types` VALUES (1, 'payeer', @obj_type)");
mysql_query("INSERT INTO `cms3_objects` VALUES(NULL, 'emarket-paymenttype-payeer', 'payeer', 0, @type_id, 9, NULL)");
mysql_query("SET @obj = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_import_objects`  VALUES(1, 'payeer', @obj)");

mysql_query("SELECT @field_id:=new_id FROM `cms3_import_fields` WHERE `source_id`='1' AND `field_name`='class_name' AND `type_id`=@type_id");
mysql_query("INSERT INTO `cms3_object_content` VALUES(@obj, @field_id, NULL, 'payeer', NULL, NULL, NULL, NULL)");
mysql_query("SELECT @field_id:=new_id FROM `cms3_import_fields` WHERE `source_id`='1' AND `field_name`='payment_type_id' AND `type_id`=@type_id");
mysql_query("INSERT INTO `cms3_object_content` VALUES(@obj, @field_id, @obj_type, NULL, NULL, NULL, NULL, NULL)");
mysql_query("SELECT @field_id:=new_id FROM `cms3_import_fields` WHERE `source_id`='1' AND `field_name`='payment_type_guid' AND `type_id`=@type_id");
mysql_query("INSERT INTO `cms3_object_content` VALUES(@obj, @field_id, NULL, 'emarket-payment-payeer', NULL, NULL, NULL, NULL)");

mysql_query("INSERT INTO `cms3_object_field_groups` VALUES(NULL, 'payment_props', 'Properties of the method of payment', @obj_type, 1, 1, 5, 0)");
mysql_query("SET @field_group = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_fields_controller` VALUES(5, @payment_type_id, @field_group)");

mysql_query("INSERT INTO `cms3_object_field_groups` VALUES(NULL, 'settings', 'Settings', @obj_type, 1, 1, 10, 0)");
mysql_query("SET @field_group = LAST_INSERT_ID()");

mysql_query("INSERT INTO `cms3_object_fields` VALUES(NULL, 'payeer_shop', 'ID store', 0, 13, 0, 1, NULL, 0, 0, 'The store identifier registered in the system PAYEER. It can be found in Payeer account: Account -> My store -> Edit.', 1, NULL, 0, 0)");
mysql_query("SET @field = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_fields_controller` VALUES (15, @field, @field_group)");

mysql_query("INSERT INTO `cms3_object_fields` VALUES(NULL, 'payeer_key', 'Secret key', 0, 13, 0, 1, NULL, 0, 0, 'The secret key notification of payment that is used to verify the integrity of the received information and the unique identification of the sender. Must be the same secret key specified in the Payeer account: Account -> My store -> Edit.', 1, NULL, 0, 0)");
mysql_query("SET @field = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_fields_controller` VALUES (20, @field, @field_group)");

mysql_query("INSERT INTO `cms3_object_fields` VALUES(NULL, 'payeer_log', 'Logging', 0, 1, 0, 1, NULL, 0, 0, 'The query log from Payeer is stored in the file: /payeer.log', 1, NULL, 0, 0)");
mysql_query("SET @field = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_fields_controller` VALUES (25, @field, @field_group)");

mysql_query("INSERT INTO `cms3_object_fields` VALUES(NULL, 'payeer_ipfilter', 'IP filter', 0, 13, 0, 1, NULL, 0, 0, 'The list of trusted ip addresses, you can specify the mask', 1, NULL, 0, 0)");
mysql_query("SET @field = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_fields_controller` VALUES (30, @field, @field_group)");

mysql_query("INSERT INTO `cms3_object_fields` VALUES(NULL, 'payeer_emailerr', 'Email for errors', 0, 13, 0, 1, NULL, 0, 0, 'E-mail for error reporting payment', 1, NULL, 0, 0)");
mysql_query("SET @field = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_fields_controller` VALUES (35, @field, @field_group)");

mysql_query("INSERT INTO `cms3_object_fields` VALUES(NULL, 'payeer_url', 'The URL of the merchant', 0, 13, 0, 1, NULL, 0, 0, 'url for payment in the system Payeer (specify //payeer.com/merchant/)', 1, NULL, 0, 0)");
mysql_query("SET @field = LAST_INSERT_ID()");
mysql_query("INSERT INTO `cms3_fields_controller` VALUES (40, @field, @field_group)");

echo "<center><b>Install the payment module Payeer for UMI CMS is ready!</b></center>";

echo '
<script type="text/javascript">
 
    setTimeout(function () {
        location.href = "http://' . $_SERVER['HTTP_HOST'] . '";
    }, 3000);
 
</script>';
?>