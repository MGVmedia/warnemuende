<?php
namespace warnemuende\model;
/**
 * Prepares MySQL databases for a specific Model
 *
 * @author Sebastian Gaul <sebastian@mgvmedia.com>
 */
class MySqlInitialization implements Initialization {
    
    /**
     * Array of name => option[]
     *
     * @var mixed[]
     */
    private $fields;

    /**
     * Relation's name
     *
     * @var string
     */
    private $tableName;
    
    public function  __construct() {
        $this->fields = array();
    }

    public function init() {
        // TODO
        echo $this->getCreateTableStatement();
    }

    public function setField($name, $options) {
        $this->fields[$name] = $options;
    }

    public function setTableName($name) {
        $this->tableName = $name;
    }

    public function getCreateTableStatement() {
        $q  = "CREATE TABLE `".$this->tableName."` (\n";
        foreach ($this->fields as $name => $prop) {
            if (!isset($prop["type"])) {
                trigger_error("No type given for <em>".
                              $name."</em> in Model ".get_called_class(),
                              \E_USER_ERROR);
                exit;
            }
            switch ($prop["type"]) {
                case "integer":
                    isset($prop["maximumLength"]) ? $l = $prop["maximumLength"] : $l = 11;
                    $q .= "`".$name."` INTEGER(".$l.")".(isset($prop["unsigned"]) ? " UNSIGNED" : "");
                    break;
                case "text":
                    if (isset($prop["maximumLength"])) {
                        $q .= "`".$name."` VARCHAR(".$prop["maximumLength"].")";
                    } else {
                        $q .= "`".$name."` TINYTEXT";
                    }
                    break;
                case "longtext":
                    $q .= "`".$name."` LONGTEXT";
                    break;
                case "association":
                    if (isset($prop["cardinality"])
                        && $prop["cardinality"] == "1") {
                        // TODO Enable more then INT-linking
                        // Object can have more than one field as primary key
                        // with all datatypes
                        $q .= "`".$name."` INTEGER UNSIGNED";
                    // If relation is m:n the m will add the connection table:
                    } elseif(isset($prop["cardinality"])
                             && $prop["cardinality"] == "m") {
                        // TODO
                    } else {
                        continue(2);
                    }
                    break;
                default:
                    trigger_error("Unknown type <em>".$prop["type"].
                                  "</em> given for <em>".$name."</em> in Model ".
                                  get_called_class(), \E_USER_ERROR);
                    exit;
            }
            $q .= ",\n";
        }
        $q = substr($q, 0, -2);
        $q .= "\n);";
        return $q;
    }
}
?>