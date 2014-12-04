<?php
  // --------------------------------------------------------------------
  // phpSpell 1.06 (beta) PHPBB & Nuke Specific code
  //
  // This is (c)Copyright 2008, Team phpSpell.
  // --------------------------------------------------------------------

  if (!defined('PHPSPELL_CONFIG')) exit;
  define('IN_PHPBB', true);
  $phpbb_root_path = $Spell_Config['PHPBB_ROOT_PATH'];

  $Spell_Config['DB_MODULE'] = 'PHPBB v3.0';
  $phpEx = substr(strrchr(__FILE__, '.'), 1);

  $PathPrefix = '';


  // General Configuration Include File
  include ($phpbb_root_path.$PathPrefix.'config.'.$phpEx);
  // ---------------------------------

  require($phpbb_root_path . 'includes/db/' . $dbms . '.' . $phpEx);
  $db = new $sql_db();
  $db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, defined('PHPBB_DB_NEW_LINK') ? PHPBB_DB_NEW_LINK : false);

    define('CRITICAL_ERROR', 1);
    define('BEGIN_TRANSACTION', 1);
    define('END_TRANSACTION', 2);
    function message_die($Error, $Message) {
     die($Message);
    }

  if ($Spell_Config['PHPBB_Load_Smilies']) Create_PHPBB_Smiles();


  function Create_PHPBB_Smiles()
  {
    global $Spell_Config;
    global $db, $table_prefix, $dbms;

    if ($dbms == 'mssql' || $dbms == 'mssql-odbc') {
      $Query = 'SELECT code FROM'.$table_prefix.'smilies ORDER BY {fn LENGTH(code)} desc';
    } else {
      $Query = 'SELECT code FROM '.$table_prefix.'smilies ORDER BY length(code) desc';
    }
    if( !($Query_Result = $db->sql_query($Query)) ) {
       if (defined('IN_SPELL_DIAGS') || defined('IN_SPELL_ADMIN')) {
         echo '<br><Br><center><b>Error opening smilies table.</b></center><br>';
       } else {
         message_die(CRITICAL_ERROR,'Unable to perform spell check at this time.<br>');
         exit;
       }
    }

    while ($row = $db->sql_fetchrow($Query_Result)) {
      $Spell_Config['Symbol_Tags'][] = $row['code'];
    }
  }

  // --------------------------------------------
  // Checks for a Good Word in the Database
  // --------------------------------------------
  function DB_Check_Word($Word_To_Check)
  {
     global $db, $dbms, $DB_TableName;

     if ($dbms == 'mysql' || $dbms == 'mysql4') {
       $word_to_seek = addslashes($Word_To_Check);
     } else {
       $word_to_seek = str_replace('\'', '\'\'', $Word_To_Check);
     }
     $Query = 'SELECT count(*) as cnt FROM '.$DB_TableName.' WHERE word=\''.$word_to_seek.'\'';
     $Query_Result = $db->sql_query($Query, $db->db_connect_id);
     if( !$Query_Result ) {
       message_die(CRITICAL_ERROR,'Unable to perform spell check at this time.<br>');
     }

     $Count = $db->sql_fetchrow($Query_Result);
     $db->sql_freeresult($Query_Result);

     if ($Count['cnt'] > 0) return (true);
     return (false);
  }


  // --------------------------------------------
  // Get the Suggestions from the Database
  // --------------------------------------------
  function DB_Get_Suggestions($Word_Sound, $Word_To_Check)
  {
    global $db, $DB_TableName, $dbms;
    $Suggestions = array();
    $Query = 'SELECT word FROM '. $DB_TableName.' WHERE sound=\''.$Word_Sound.'\'';
    if( !($Query_Result = $db->sql_query($Query)) ) {
       message_die(CRITICAL_ERROR,'Unable to perform spell check at this time.<br>');
    }

    while ($Fetched_Array = $db->sql_fetchrow($Query_Result)) {
      $Fetched_Array = $db->sql_fetchrow($Query_Result);
      if ($dbms == 'mysql' || $dbms == 'mysql4') {
        $Suggestions[$i] = stripslashes(trim(strtolower($Fetched_Array['word'])));
      } else {
        $Suggestions[$i] = str_replace('\'\'', '\'', trim(strtolower($Fetched_Array['word'])));
      }
    }
    return ($Suggestions);
  }

  // --------------------------------------------
  // Get the OFF BY ONE suggestions (if active)
  // --------------------------------------------
  function DB_Get_OBO_Suggestions($Word_To_Check)
  {
    global $db, $dbms, $DB_TableName;
    $Suggestions = array();

    $Query_Words_Length = strlen($Word_To_Check);
    $Query_Words = '"_'.substr($Word_To_Check, 1).'"';
    for ($i=1;$i<$Query_Words_Length;$i++) {
      $Query_Words .= ' OR word LIKE "'.substr($Word_To_Check, 0, $i).'_'.substr($Word_To_Check, $i+1).'"';
    }

    if ($dbms == 'mysql' || $dbms == 'mysql4') {
      $Query_Words = str_replace('\'', "\\'", $Query_Words);
    } else {
      $Query_Words = str_replace('\'', '\'\'', $Query_Words);
    }

    $Query = 'SELECT word FROM '. $DB_TableName.' WHERE word LIKE '.$Query_Words;
    if( !($Query_Result = $db->sql_query($Query)) ) {
       message_die(CRITICAL_ERROR,'Unable to perform spell check at this time.<br>');
    }

    while ($Fetched_Array = $db->sql_fetchrow($Query_Result)) {
      if ($dbms == 'mysql' || $dbms == 'mysql4') {
        $Suggestions[$i] = stripslashes(trim(strtolower($Fetched_Array['word'])));
      } else {
        $Suggestions[$i] = str_replace('\'\'', '\'', trim(strtolower($Fetched_Array['word'])));
      }
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
    $Query = 'SELECT COUNT(*) as cnt FROM '.$DB_TableName;

    $Query_Result = $db->sql_query($Query, $db->db_connect_id);
    if(!$Query_Result) {
       return(DB_Error_Message($Query_Result));
    }

    $code = $db->sql_fetchrow($Query_Result);
    return ($code['cnt']);
  }


  // -----------------------------------------------
  // This function is used by the Diagnostic Routine
  // And by the Admin Module
  // Returns a detailed error message
  // -----------------------------------------------
  function DB_Error_Message($Query_Result, $Ignore_Table_Create_Error=false)
  {
    global $db, $dbms;
    $result = $db->_sql_error();
    if (!isset($result['code'])) $result['code'] = 'N/A';

    if ($result['code'] == '1050' && $Ignore_Table_Create_Error) return (-1);
    $Info = 'Code: '.$result['code'].'<br>Message: '.$result['message'];
    $Info .= '<br>Result: '.$Query_Result.'  Link: '.$db->db_connect_id.'<br>';
    $Info .= 'DB: '.$db->dbname.' User: '.$db->user.' Server: '.$db->server;
    return ($Info);
  }


  // -----------------------------------------------
  // This function is used by the Admin Module
  // Adds a word to the table
  // -----------------------------------------------
  function DB_Add_Word($Word_To_Add, $Word_Sound)
  {
    global $dbms, $db, $DB_TableName;
    if ($dbms == 'mysql' || $dbms == 'mysql4') {
      $Word_To_Add = addslashes($Word_To_Add);
    } else {
      $Word_To_Add = str_replace('\'', '\'\'', $Word_To_Add);
    }
    $Query = 'INSERT INTO '.$DB_TableName.'(word, sound) VALUES (\''.$Word_To_Add.'\', \''.$Word_Sound.'\')';
    $QResult = $db->sql_query($Query);
//    echo DB_Error_Message($QResult);
  }

  // -----------------------------------------------
  // This function is used by the Admin Module
  // Create the Table
  // -----------------------------------------------
  function DB_Create_Table()
  {
    global $dbms, $db, $DB_TableName;
    switch ($dbms)
    {
      case 'mysql':
      case 'mysql4':
        $Query[0] = 'CREATE TABLE '.$DB_TableName.' (id MEDIUMINT AUTO_INCREMENT NOT NULL, word VARCHAR (30) BINARY NOT NULL, sound VARCHAR(10) not NULL, PRIMARY KEY(id), INDEX(sound), UNIQUE(word))';
        break;
      case 'mssql-odbc':
      case 'mssql':
        $Query[0] = 'CREATE TABLE ['.$DB_TableName.'] ([id] [int] primary key identity (1,1) NOT NULL , [word] [varchar] (30) NOT NULL, [sound] [varchar] (10) NOT NULL) ON [PRIMARY]';
//        $Query[0] = 'CREATE TABLE ['.$DB_TableName.'] ([id] [int] NOT NULL , [word] [varchar] (30) NOT NULL, [sound] [varchar] (10) NOT NULL) ON [PRIMARY]';
        $Query[1] = 'CREATE INDEX [IX_'.$DB_TableName.'_WORD] on ['.$DB_TableName.'](word) ON [PRIMARY]';
        $Query[2] = 'CREATE INDEX [IX_'.$DB_TableName.'_SOUND] on ['.$DB_TableName.'](sound) ON [PRIMARY]';
        break;
      default:
        message_die(CRITICAL_ERROR,'No valid SQL to create your database type.');
    }
    for ($i=0;$i<count($Query);$i++) {
      $result = $db->sql_query($Query[$i]);
      if (!$result) {
        $Info = DB_Error_Message($result);
        if ($Info != '1050') echo 'Unable to create dictionary database table: '.$Info;
      }
    }
  }

  // -----------------------------------------------
  // This function is used by the Admin Module
  // Deletes the Table
  // -----------------------------------------------
  function DB_Drop_Table()
  {
    global $db, $DB_TableName;
    $Query = 'DROP TABLE '.$DB_TableName;
    $db->sql_query($Query);
  }


?>