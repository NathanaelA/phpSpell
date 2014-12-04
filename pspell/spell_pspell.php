<?php
  // --------------------------------------------------------------------
  // phpSpell 1.06 (beta) PSpell Specific Code
  //
  // This is (c)Copyright 2003-2005 Team phpSpell.
  // --------------------------------------------------------------------
  // Warning: do not change anything in this file
  // --------------------------------------------------------------------

  if (!defined("PHPSPELL_CONFIG")) exit;
  define('INADMIN', false);

  $dbms = "None";
  $Spell_Config["DB_MODULE"] = "pspell";
  $table_prefix = "";

  if (!function_exists("pspell_new")) {
    die ("PSPELL is not installed in PHP; Use a different Module!");
  }

  $pspell_link = pspell_new ($Spell_Config["PSPELL_LANGUAGE"]);


  function DB_Drop_Table() {return;}
  function DB_Create_Table() {return;}
  function DB_Add_Word() {return;}
  function DB_Get_Word_Count() {return ("N/A"); }
  function DB_Get_OBO_Suggestions() {return (array());}

  function DB_Get_Suggestions($Word_Sound, $Word_To_Check) {
    global $pspell_link;
    return (pspell_suggest($pspell_link, $Word_To_Check));
  }

  function DB_Check_Word($Word_To_Check)
  {
    global $pspell_link;
    return (pspell_check ($pspell_link, $Word_To_Check));
  }






?>