<?php

error_reporting(E_ALL);

// Load all classes from namespace-like directories
function __autoload($className) {
    if (is_file(str_replace('\\', '/', $className).".php")) {
        require_once str_replace('\\', '/', $className).".php";
        return true;
    } else {
        return false;
    }
}

use warnemuende\model\mysql\Model;

mysql_connect("localhost", "modeltester", "test");
mysql_select_db("modeltester");
mysql_query("SET NAMES 'utf8';");
mysql_query("SET CHARACTER NAMES 'utf8';");

class Page extends Model {

    public function configure() {
        $this->addInteger("id", 22, true, false, true, array("myType" => "apfel"));
        $this->addText("slug", 30);
        $this->addText("content", -1);

        $this->addIndex(array("slug"));
    }
}

class PageTag extends Model {

    public function configure() {
        $this->addInteger("id", 22, true, true, true);
        $this->addText("tag", 30);
        $this->addInteger("level", 10);
    }
}

$p = new Page();
$p->createTables();

$t = new PageTag();
$t->createTables();

$p->setField("slug", "home");
$p->setField("content", "Hallo und herzlich willkomennßäüö");
$p->save();

$p->dropTable();
$t->dropTable();
die("Läuft");
/* 
 * This is a demo page for testing models
 */

class Page extends Model {

    public static $id = array(
        "type" => "integer",
        "unsigned" => true,
        "autoIncrement" => true,
        "primaryKey" => true,
        "semantic" => "identifier"
    );

    public static $slug = array(
        "type" => "text",
        "maximumLength" => 30,
        "index" => true,
        "semantic" => "page slug"
    );

    public static $title = array(
        "type" => "text",
        "semantic" => "page title"
    );

    public static $content = array(
        "type" => "longtext",
        "semantic" => "page content"
    );

    public static $tags = array(
        "type" => "association",
        "class" => "Page",
        "cardinality" => "n"
    );

    public static $Indices = array(
        array("slug")
    );


}

class PageTag extends Model {

    public static $tag = array(
        "type" => "text",
        "maximumLength" => 30,
        "primaryKey" => true
    );

    public static $page = array(
        "type" => "association",
        "class" => "Page",
        "cardinality" => 1,
        "primaryKey" => true
    );


}

echo "getFields(): ";
print_r(Page::getFields());
echo "\n";

echo "getFieldOption('id', 'type'): ";
echo Page::getFieldOption("id", "type");
echo "\n\n";

mysql_connect("localhost", "modeltester", "test");
mysql_select_db("modeltester");
$result = mysql_query("show tables;");
if (mysql_num_rows($result) != 0) {
    die("Testing can lead to different problems - please give me an empty database.");
}

echo "Page::initDatabase()... ";
echo Page::initDatabase();
echo "\n\n";

echo "PageTag::initDatabase()... ";
echo PageTag::initDatabase();
echo "\n";


echo "\n\nDeleting created databases...";
@mysql_query("drop table ".Page::getTableName());
@mysql_query("drop table ".PageTag::getTableName());
echo "\n Reached end";
?>
