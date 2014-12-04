PHPSpell
Version 1.06r
-------------

	I am making a simple "request" of all of you that are using this software.  
    I have a couple web sites/programs that I am attempting to make some money.   I work as a full
time consultant, so donations/free advertising is a good thing for me.  If any of your sites runs banner ads, I would be very grateful if you would add any of the banners ads to your site that is listed in the banner directory.   And/Or if you could just add any of the following links to your link section.

http://www.master-technology.com  - Computer Consulting (Software & Web Programming)
http://www.congocart.com  - Congo Software
http://www.featurecreature.net    - A EASY to use Automated Web Assistant, Downloads RSS Feeds, News, Comics, Puzzles, Etc...

Thanks!

-------------
Credits:
  Ifroggy - Original php Alpha/1.02 Version and for the php based spell concept (Team PHPSpell)
  Nathan - Designed & wrote the 1.03/1.04/1.05/1.06 phpSpell versions (Team PHPSpell)
  MJ - Spell_Admin / Admin_Spellcheck.php - Conversion of spell_dictionary_installer into a PHPBB Administration module
  Michael - Help debugging Russian Language support (I don't speak or read Russian)
  Dove - Yabsse Instructions
  Pablo - polish Language Support


This script now supports more than just phpbb, however phpbb will probably always be it primary target platform -- it now supports:

  PHPBB v3.0x
  PHPBB v2.0x
  HiveMail 1.2
  Invision 1.1x
  phpNuke 6.5
  pSpell (php module)
  phpMail 1.x
  VBulletian
  Native MySQL interface


  So depending on what you are using see the specific install instructions file for notes regarding how to install it for your program.  If your program is not supported YET feel free to email me at phpspell@master-technology.com and inquire about support for your program / version.

  If you are using PHPBB and you want to use the pspell module; you follow the install instructions for the PHPBB and then you follow the instructions for the PSPELL.   You need the support files from BOTH directories to use BOTH modules.   You will need to make sure that the $SPELL_CONFIG["MODULE"] = "pspell" since this is the actual database you will be using for the word searches.



Notes:
------
  The dictionary installer's table creation SQL IS PROBABLY NOT compatable with anything except mysql & mssql.  If you need help installing this on a non-mysql site email me at phpspell@master-technology.com   
  The phpbb_alpha - is an alpha of the new template code.   USE AT YOUR OWN RISK!!!

   If your host has a MAX_QUERIES set on your mysql; you are likely to exceed them when you install.  Recommend you split the word file into groups and install each word file daily; or talk to your host about temporarily lifting the limit so you can install it.





Diagnostics:
------------
  If you are having a problem with the spell checker, upload the spell_diags.php file to the server and run it.  It might point you (or me if I request it) in the right direction. 



Language Translation:
---------------------
   If you are using English or Russian you do not need to worry about this section.  At this point we do not have any other languages translated.  Preliminary support in spell_langtemplate.php is so that you can create a translation if you would like the spell checker to support your language.  We ask that if you do create a  translation, that you please consider sending us the translation file so that we can include it in the official version.   The file should be named:  "spell_Language.php" where language is your language.  So for Spanish it should be "spell_Spanish.php".   Note the "Spanish" is capitalized.


