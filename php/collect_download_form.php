<?php
  session_start(); 
  require_once 'conn_db.php';
  function isLoggedIn(){
    if(isset($_SESSION['username'])){
      return ture;
    }
    else{
      return false;
    }
  }
  

  class FlxZipArchive extends ZipArchive {
    /** Add a Dir with Files and Subdirs to the archive;;;;; @param string $location Real Location;;;;  @param string $name Name in Archive;;; @author Nicolas Heimann;;;; @access private  **/
    public function addDir($location, $name) {
      $this->addEmptyDir($name);
      $this->addDirDo($location, $name);
   } // EO addDir;

    /**  Add Files & Dirs to archive;;;; @param string $location Real Location;  @param string $name Name in Archive;;;;;; @author Nicolas Heimann * @access private   **/
    private function addDirDo($location, $name) {
      $name .= '/';         
      $location .= '/';
      // Read all Files in Dir
      $dir = opendir ($location);
      while ($file = readdir($dir))    {
          if ($file == '.' || $file == '..') continue;
          // Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
          $do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
          $this->$do($location . $file, $name . $file);
      }
    } 
  }

  //If the user is logged in, go and download the files
  //Else the pages will turn to the index.html
  if(isLoggedIn()){
    $download=$_POST["download_list"];
    $download_list=explode(",", $download);
    $files_num=count($download_list);
    
    for($i=0;$i<$files_num;$i++){
      if(file_exists($download_list[$i])){
        $the_folder=$download_list[$i];
        $zip_file_name = '../download/archived_name.zip';
        $za = new FlxZipArchive;
        $res = $za->open($zip_file_name, ZipArchive::CREATE);
        if($res === TRUE){
          $flag=1;
          $za->addDir($the_folder, basename($the_folder));
          $za->close();
        }
        else  { $flag=0;}
      }
    }

    $yourfile = "../download/archived_name.zip";

    $file_name = basename($yourfile);

    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=$file_name");
    header("Content-Length: " . filesize($yourfile));

    readfile($yourfile);
    unlink("../download/archived_name.zip");
    exit;
  }
  else {
    $url="../pages/index.html";
    echo "<script type='text/javascript'>";
    echo "alert('Please login first.');";
    echo "window.location.href='$url'";
    echo "</script>";
  }
?>