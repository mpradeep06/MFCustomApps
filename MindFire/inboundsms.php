<?php


$filename = 'inboundsms.csv';
$Message="";
$OriginatorAddress="";
$AcceptedTime="";
$CustomerNickname="";

$check=$_GET['check'];
if($check=='true')
{
echo "Success";
}


if ($_POST) {    

$Message=htmlspecialchars($_POST["Message"]);
$OriginatorAddress=htmlspecialchars($_POST["OriginatorAddress"]);
$OriginatorAddress=substr($OriginatorAddress,1);
$AcceptedTime=htmlspecialchars($_POST["AcceptedTime"]);
$CustomerNickname=htmlspecialchars($_POST["CustomerNickname"]);
$Key=strtolower($CustomerNickname);
$splitstrings = explode(" ", $Message);
$subkeyword=$splitstrings[1];
$subKey=strtolower($subkeyword);
echo $subKey;

if (strlen($subKey) == 0)
{

    $f = fopen($filename, "r");
    $result = false;
    while ($row = fgetcsv($f)) {
        if ((strtolower($row[1]) == $Key)&&(strlen($row[2]) == 0)) {


            $Identifier = $row[3];
            $accountId = $row[4];
	    $contactfieldvalue = $row[6];
            $eventid = $row[7];
            break;
        }
    }
    fclose($f);
$var="https://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=". $Identifier."&accountId=". $accountId."&outboundId=0";
echo $var." Step 1";
studioAPI($var, $contactfieldvalue,$eventid ,$OriginatorAddress,$subKey);

}

else
{

    $f = fopen($filename, "r");
    $result = false;
    while ($row = fgetcsv($f)) {
        if ((strtolower($row[1]) == $Key)&&(strtolower($row[2]) == $subKey)) {


            $Identifier = $row[3];
            $accountId = $row[4];
	    $contactfieldvalue = $row[6];
            $eventid = $row[7];
            $var="https://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=". $Identifier."&accountId=". $accountId."&outboundId=0";
            echo $var." Step 2";
            studioAPI($var, $contactfieldvalue,$eventid ,$OriginatorAddress,$subKey);
            break;

        }
  
 else if ((strtolower($row[1]) == $Key)&&(strtolower($row[2]) != $subKey)&&(strtolower($row[2]) != "")) {

            $Identifier = $row[3];
            $accountId = $row[4];
	    $contactfieldvalue = $row[6];
            $eventid = $row[7];
            $var="https://studio.afw.mdl.io/api/OutboundApp/AppCallback?serviceTypeId=2019&identifier=". $Identifier."&accountId=". $accountId."&outboundId=0";
            echo $var." Step 3";
            studioAPI($var, $contactfieldvalue,$eventid ,$OriginatorAddress,$subKey);
            break;

        
  }
else
{

}

 } 
    fclose($f);

}


}

function studioAPI($var, $contactfieldvalue,$eventid ,$OriginatorAddress,$subKey)
{

if($subKey=="thous")
{
$eventid="201912";
}

if($subKey=="wshous")
{
$eventid="201913";
}

if($subKey=="tshous")
{
$eventid="201914";
}

if($subKey=="fhous")
{
$eventid="201915";
}

date_default_timezone_set('America/Los_Angeles');
$date = date("M d Y H:i:s");
// Setup request to send json via POST.
$payload = json_encode(  
  array("request"=>array(  
      "optin"=>$contactfieldvalue,
      "mobile"=>$OriginatorAddress,
      "uniqueid"=>$subKey,
      "eventid"=> $eventid,
      "date"=>$date,
      "eventoption"=>$OriginatorAddress."-".$Message,
      "dedup"=>"mobile::".$OriginatorAddress
     )));

echo $payload;
 //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

// create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $var); 
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   
 

}
 



?>
