<?php

/**
 * A PHP class to access mysqli database with convenient methods
 * in an object-oriented way, and with a powerful debug system.
 * Licence:  LGPL
 * Web site: http://slaout.linux62.org/
 * @version  1.0
 * author   Sébastien Laoût (slaout@linux62.org)
 */
class DB
{
  /** Put this variable to true if you want ALL queries to be debugged by default: */
  public $defaultDebug = false;

  /** INTERNAL: The start time, in milliseconds. */
  private $mtStart;

  /** INTERNAL: The number of executed queries. */
  private $nbQueries;

  /** INTERNAL: The last result resource of a query(). */
  private $lastResult;

  /** Database connection */
  private $db;

  public function __construct()
  {
    include './config.php';

    if (!isset($config) || !is_array($config)) {
      die("Configuration file is missing or invalid.");
    }

    $this->db = $this->db_conn(
      $config['database'] ?? '',
      $config['hostname'] ?? '',
      $config['username'] ?? '',
      $config['password'] ?? ''
    );
  }

  /** Connect to a mysqli database to be able to use the methods below. */
  public function db_conn($base, $server, $user, $pass)
  {
    $this->mtStart = $this->getMicroTime();
    $this->nbQueries = 0;
    $this->lastResult = NULL;
    $connection = mysqli_connect($server, $user, $pass, $base);

    if ($connection === false) {
      $data = 'Database Connection is Not valid. Please Enter The valid database connection';
      header("location:install.php?msg=$data");
      exit;
    }
    return $connection;
  }

  /** Query the database. */
  public function query($query, $debug = -1)
  {
    $this->nbQueries++;
    $this->lastResult = mysqli_query($this->db, $query) or $this->debugAndDie($query);

    $this->debug($debug, $query, $this->lastResult);

    return $this->lastResult;
  }

  /** Do the same as query() but do not return nor store result. */
  public function execute($query, $debug = -1)
  {
    $this->nbQueries++;
    mysqli_query($this->db, $query) or $this->debugAndDie($query);

    $this->debug($debug, $query);
  }

  /** Convenient method for mysqli_fetch_object(). */
  public function fetchNextObject($result = NULL)
  {
    if ($result == NULL)
      $result = $this->lastResult;

    if ($result == NULL || mysqli_num_rows($result) < 1)
      return NULL;
    else
      return mysqli_fetch_object($result);
  }

  /** Get the number of rows of a query. */
  public function numRows($result = NULL)
  {
    if ($result == NULL)
      return mysqli_num_rows($this->lastResult);
    else
      return mysqli_num_rows($result);
  }

  /** Get the result of the query as an object. The query should return a unique row. */
  public function queryUniqueObject($query, $debug = -1)
  {
    $query = "$query LIMIT 1";

    $this->nbQueries++;
    $result = mysqli_query($this->db, $query) or $this->debugAndDie($query);

    $this->debug($debug, $query, $result);

    return mysqli_fetch_object($result);
  }

  /**
   * Get the result of the query as value. The query should return a unique cell.
   */
  public function queryUniqueValue($query, $debug = -1)
  {
    $query = "$query LIMIT 1";

    $this->nbQueries++;
    $result = mysqli_query($this->db, $query) or $this->debugAndDie($query);

    // Fetch the row as an array
    $line = mysqli_fetch_row($result);

    // If there is no result, return null
    if ($line === NULL) {
      return NULL;
    }

    $this->debug($debug, $query, $result);

    return $line[0];
  }


  /** Get the maximum value of a column in a table, with a condition. */
  public function maxOf($column, $table, $where)
  {
    return $this->queryUniqueValue("SELECT MAX(`$column`) FROM `$table` WHERE $where");
  }

  /** Get the maximum value of a column in a table. */
  public function maxOfAll($column, $table)
  {
    return $this->queryUniqueValue("SELECT MAX(`$column`) FROM `$table`");
  }

  /** Get the count of rows in a table, with a condition. */
  public function countOf($table, $where)
  {
    return $this->queryUniqueValue("SELECT COUNT(*) FROM `$table` WHERE $where");
  }

  /** Get the count of rows in a table. */
  public function countOfAll($table)
  {
    return $this->queryUniqueValue("SELECT COUNT(*) FROM `$table`");
  }

  /** Internal function to debug when mysqli encountered an error. */
  private function debugAndDie($query)
  {
    $this->debugQuery($query, "Error");
    die("<p style=\"margin: 2px;\">" . mysqli_error($this->db) . "</p></div>");
  }

  /** Internal function to debug a mysqli query. */
  private function debug($debug, $query, $result = NULL)
  {
    if ($debug === -1 && $this->defaultDebug === false)
      return;
    if ($debug === false)
      return;

    $reason = ($debug === -1 ? "Default Debug" : "Debug");
    $this->debugQuery($query, $reason);
    if ($result == NULL)
      echo "<p style=\"margin: 2px;\">Number of affected rows: " . mysqli_affected_rows($this->db) . "</p></div>";
    else
      $this->debugResult($result);
  }

  /** Internal function to output a query for debug purpose. */
  private function debugQuery($query, $reason = "Debug")
  {
    $color = ($reason == "Error" ? "red" : "orange");
    echo "<div style=\"border: solid $color 1px; margin: 2px;\">" .
      "<p style=\"margin: 0 0 2px 0; padding: 0; background-color: #DDF;\">" .
      "<strong style=\"padding: 0 3px; background-color: $color; color: white;\">$reason:</strong> " .
      "<span style=\"font-family: monospace;\">" . htmlentities($query) . "</span></p>";
  }

  /** Internal function to output a table representing the result of a query, for debug purpose. */
  private function debugResult($result)
  {
    echo "<table border=\"1\" style=\"margin: 2px;\">" .
      "<thead style=\"font-size: 80%\">";
    $numFields = mysqli_num_fields($result);
    // BEGIN HEADER
    $tables    = array();
    $nbTables  = -1;
    $lastTable = "";
    $fields    = array();
    $nbFields  = -1;
    while ($column = mysqli_fetch_field($result)) {
      if ($column->table != $lastTable) {
        $nbTables++;
        $tables[$nbTables] = array("name" => $column->table, "count" => 1);
      } else {
        $tables[$nbTables]["count"]++;
      }
      $lastTable = $column->table;
      $nbFields++;
      $fields[$nbFields] = $column->name;
    }
    for ($i = 0; $i <= $nbTables; $i++)
      echo "<th colspan=" . $tables[$i]["count"] . ">" . $tables[$i]["name"] . "</th>";
    echo "</thead>";
    echo "<thead style=\"font-size: 80%\">";
    for ($i = 0; $i <= $nbFields; $i++)
      echo "<th>" . $fields[$i] . "</th>";
    echo "</thead>";
    // END HEADER
    while ($row = mysqli_fetch_array($result)) {
      echo "<tr>";
      for ($i = 0; $i < $numFields; $i++)
        echo "<td>" . htmlentities($row[$i]) . "</td>";
      echo "</tr>";
    }
    echo "</table></div>";
    $this->resetFetch($result);
  }

  /** Get how many time the script took from the begin of this object. */
  public function getExecTime()
  {
    return round(($this->getMicroTime() - $this->mtStart) * 1000) / 1000;
  }

  /** Get the number of queries executed from the begin of this object. */
  public function getQueriesCount()
  {
    return $this->nbQueries;
  }

  /** Go back to the first element of the result line. */
  public function resetFetch($result)
  {
    if (mysqli_num_rows($result) > 0)
      mysqli_data_seek($result, 0);
  }

  /** Get the id of the very last inserted row. */
  public function lastInsertedId()
  {
    return mysqli_insert_id($this->db);
  }

  /** Close the connection with the database server. */
  public function close()
  {
    mysqli_close($this->db);
  }

  /** Internal method to get the current time. */
  private function getMicroTime()
  {
    list($msec, $sec) = explode(' ', microtime());
    return floor($sec / 1000) + $msec;
  }
} // class DB
