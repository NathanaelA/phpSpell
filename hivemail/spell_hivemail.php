<?php
  // --------------------------------------------------------------------
  // phpSpell 1.06 (beta) Hivemail Specific Code
  //
  // This is (c)Copyright 2003, Team phpSpell.
  // --------------------------------------------------------------------
  // Warning: do not change anything in this file
  // --------------------------------------------------------------------

  if (!defined("PHPSPELL_CONFIG")) exit;
  define('INADMIN', false);

  $dbms = "Mysql";
  $Spell_Config["DB_MODULE"] = "Hivemail";
  $table_prefix = "";

  include_once('../includes/config.php');
  include_once('../includes/db_mysql.php');

  $db = new DB_MySQL($config);


    // --------------------------------------------
  // Checks for a Good Word in the Database
  // --------------------------------------------
  function DB_Check_Word($Word_To_Check)
  {
     global $db, $dbms, $DB_TableName;

     $word_to_seek = addslashes($Word_To_Check);
     $Query = "select * from ".$DB_TableName." where word='".$word_to_seek."'";
     if( !($Query_Result = $db->query($Query)) ) {
       message_die(CRITICAL_ERROR,"Unable to perform spell check at this time.<br>");
     }
     if ($db->num_rows($Query_Result) > 0) return (true);
     return (false);
  }


  // --------------------------------------------
  // Get the Suggestions from the Database
  // --------------------------------------------
  function DB_Get_Suggestions($Word_Sound, $Word_To_Check)
  {
    global $db, $DB_TableName, $dbms;
    $Suggestions = array();
    $Query = "select word from ". $DB_TableName." where sound='".$Word_Sound."'";
    if( !($Query_Result = $db->query($Query)) ) {
       message_die(CRITICAL_ERROR,"Unable to perform spell check at this time.<br>");
    }
    $Count = $db->num_rows($Query_Result);
    for ($i=0;$i<$Count;$i++) {
      $Fetched_Array = $db->fetch_array($Query_Result);
      $Suggestions[$i] = stripslashes(trim(strtolower($Fetched_Array["word"])));
    }
    return ($Suggestions);
  }

  // --------------------------------------------
  // Get the OFF BY ONE suggestions (if active)
  // --------------------------------------------
  function DB_Get_OBO_Suggestions($Word_To_Check)
  {
    global $db, $dbms, $DB_Table_Name;
    $Suggestions = array();

    $Query_Words_Length = strlen($Word_To_Check);
    $Query_Words = "\"_".substr($Word_To_Check, 1)."\"";
    for ($i=1;$i<$Query_Words_Length;$i++) {
      $Query_Words .= " or word like \"".substr($Word_To_Check, 0, $i)."_".substr($Word_To_Check, $i+1)."\"";
    }

    $Query_Words = str_replace("'", "\\\'", $Query_Words);

    $Query = "select word from ". $DB_TableName." where word like ".$Query_Words;
    if( !($Query_Result = $db->query($Query)) ) {
       message_die(CRITICAL_ERROR,"Unable to perform spell check at this time.<br>");
    }
    $Count = $db->num_rows($Query_Result);
    for ($i=0;$i<$Count;$i++) {
      $Fetched_Array = $db->fetch_array($Query_Result);
      $Suggestions[$i] = stripslashes(trim(strtolower($Fetched_Array["word"])));
    }
    return ($Suggestions);
  }


  // -----------------------------------------------
  // This function is used by the Diagnostic Routine
  // Returns the number of words in the Database
  // -----------------------------------------------
  function DB_Get_Word_Count()
  {
    global $DB_TableName, $db;
    $Query = "select count(word) from ".$DB_TableName;
    if( !($Query_Result = $db->query($Query)) ) {
       return(DB_Error_Message($Query_Result));
    }

    if ($db->num_rows($Query_Result) == 0) return ("0");

    $code = $db->fetch_array($Query_Result);
    return ($code[0]);
  }


  // -----------------------------------------------
  // This function is used by the Diagnostic Routine
  // And by the Admin Module
  // Returns a detailed error message
  // -----------------------------------------------
  function DB_Error_Message($Query_Result, $Ignore_Table_Create_Error=false)
  {
    global $db, $dbms;

    if (mysql_errno($db->link_id) == 1050 && $Ignore_Table_Create_Error) return (-1);
    $Info = "Code: ".mysql_errno($db->link_id)."<br>Message: ".mysql_error($db->link_id);
    $Info .= "<br>Result: ".$Query_Result."  Link: ".$db->link_id."<br>";
    $Info .= "DB: ".$db->config["database"]." User: ".$db->config["username"]." Server: ".$db->config["server"];
    return ($Info);
  }


  // -----------------------------------------------
  // This function is used by the Admin Module
  // Adds a word to the table
  // -----------------------------------------------
  function DB_Add_Word($Word_To_Add, $Word_Sound)
  {
     global $dbms, $db, $DB_TableName;
     $Word_To_Add = addslashes($Word_To_Add);
    $Query = "insert into ".$DB_TableName."(word, sound) values ('$Word_To_Add', '$Word_Sound')";
    $db->query($Query);
  }

  // -----------------------------------------------
  // This function is used by the Admin Module
  // Create the Table
  // -----------------------------------------------
  function DB_Create_Table()
  {
    global $db, $DB_TableName;
    $Query = "CREATE TABLE ".$DB_TableName." (id MEDIUMINT AUTO_INCREMENT NOT NULL, word VARCHAR (30) BINARY NOT NULL, sound VARCHAR(10) not NULL, PRIMARY KEY(id), INDEX(sound), UNIQUE(word))";
    $result = $db->query($Query);
    if (!$result) {
      $Info = DB_Error_Message($result);
      if ($Info != "1050") echo "Unable to create dictionary database table: ".$Info;
    }
  }

  // -----------------------------------------------
  // This function is used by the Admin Module
  // Deletes the Table
  // -----------------------------------------------
  function DB_Drop_Table()
  {
    global $db, $DB_TableName;
    $Query = "drop table ".$DB_TableName;
    $db->query($Query);
  }


?>