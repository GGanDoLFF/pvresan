<?php

define('API_KEY','TOKEN');
//----######------
function makereq($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
//##############=--API_REQ
function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = "https://api.telegram.org/bot".API_KEY."/".$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
//----######------
//---------
$update = json_decode(file_get_contents('php://input'));
var_dump($update);
//=========
$chat_id = $update->message->chat->id;
$boolean = file_get_contents('booleans.txt');
  $booleans= explode("\n",$boolean);

$message_id = $update->message->message_id;
$from_id = $update->message->from->id;
$name = $update->message->from->first_name;
$username = $update->message->from->username;
$textmessage = isset($update->message->text)?$update->message->text:'';
$rpto = $update->message->reply_to_message->forward_from->id;
$stickerid = $update->message->reply_to_message->sticker->file_id;
$photo = $update->message->photo;
$video = $update->message->video;
$sticker = $update->message->sticker;
$file = $update->message->document;
$music = $update->message->audio;
$voice = $update->message->voice;
$forward = $update->message->forward_from;
$admin = 216996658;
//-------
function SendMessage($ChatId, $TextMsg)
{
 makereq('sendMessage',[
'chat_id'=>$ChatId,
'text'=>$TextMsg,
'parse_mode'=>"MarkDown"
]);
}
function SendSticker($ChatId, $sticker_ID)
{
 makereq('sendSticker',[
'chat_id'=>$ChatId,
'sticker'=>$sticker_ID
]);
}
function Forward($KojaShe,$AzKoja,$KodomMSG)
{
makereq('ForwardMessage',[
'chat_id'=>$KojaShe,
'from_chat_id'=>$AzKoja,
'message_id'=>$KodomMSG
]);
}
function save($filename,$TXTdata)
	{
	$myfile = fopen($filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}

//------------

if($textmessage == '/start')
 if ($from_id == $admin) {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"*Hi Admin*\n`Wlecome To Your Bot`",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'keyboard'=>[
              [
                ['text'=>"Count Member"],['text'=>"Block List"]
              ],
	      [
                ['text'=>"Send To All"],['text'=>"Clean Blcok List"]
              ],
	      [
	        ['text'=>"Help"]
	      ]
            ]
        ])
    ]));
 }
 else{
 
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"Hi `#$name` \n\nPls send *PM*",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'keyboard'=>[
              [
                ['text'=>"Send Contact",'request_contact' => true],['text'=>"Send Location",'request_location' => true]
              ],
	      [
                ['text'=>"Contact Admin"],['text'=>"Profile"], ['text'=>"Help"]
              ]
            ]
        ])
    ]));	
    $txxt = file_get_contents('member.txt');
$pmembersid= explode("\n",$txxt);
	if (!in_array($chat_id,$pmembersid)) {
		$aaddd = file_get_contents('member.txt');
		$aaddd .= $chat_id."
";
    	file_put_contents('member.txt',$aaddd);
} 
 }

	elseif(strpos($textmessage , '/setprofile')!== false && $chat_id == $admin)
	{
		$javab = str_replace('/setprofile',"",$textmessage);
		if ($javab != "")
	{
	save("profile.txt","$javab");
	SendMessage($chat_id,"*Changed.*");
	}
	}
	
elseif($textmessage == 'Profile')
	{
	$profile = file_get_contents("profile.txt");
	Sendmessage($chat_id," $profile ");
	}
	
	elseif($textmessage == 'Contact Admin')
{
	$phone = '+989160730657';
	$namea = 'Abbas :D';
makereq('sendContact',[
	'chat_id'=>$chat_id,
	'phone_number'=>$phone,
	'first_name'=>$namea
	]);
}

elseif($textmessage == 'Help')
if($chat_id == $admin){
	{
		Sendmessage($chat_id,"-*/ban[reply]*
		مسدود کردن فرد
		
		-*/unban[reply]*
		ازاد کردن فرد
		
		-*/setprofile* [text]
		نتظیم پروفایل شما");
	}
}
else
	{
		Sendmessage($chat_id,"-*Send Contact*
		ارسال شماره خود به ادمین
		
		-*Send Location*
		ارسال مکان شما به ادمین
		
		-*Contact Admin*
		ارسال شماره ادمین به شما
		
		-*Profile*
		نمایش اطلاعات ادمین");
	}


elseif ($chat_id != $admin) {

    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);
$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"Pm You send to Pv Admin ✅");
}else{
  
Sendmessage($chat_id,"❌*You Are Banned*❌");

    }
    }
      elseif (isset($message['contact'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"Shomare mord nazar ersal shod ✅");	
}else{
  
Sendmessage($chat_id,"❌*You Are Banned*❌");	

}
    }
      }

	   elseif (isset($message['sticker'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"Shomare mord nazar ersal shod ✅");	
}else{
  
Sendmessage($chat_id,"❌*You Are Banned*❌");	

}
    }
      }


   elseif (isset($message['photo'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"Shomare mord nazar ersal shod ✅");	
}else{
  
Sendmessage($chat_id,"❌*You Are Banned*❌");	

}
    }
      }
      
         elseif (isset($message['voice'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"Shomare mord nazar ersal shod ✅");	
}else{
  
Sendmessage($chat_id,"❌*You Are Banned*❌");	

}
    }
      }
               elseif (isset($message['video'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"Shomare mord nazar ersal shod ✅");	
}else{
  
Sendmessage($chat_id,"❌*You Are Banned*❌");	

}
    }
      }
   


	elseif($textmessage == 'Count Member' && $chat_id == $admin)
	{
		$txtt = file_get_contents('member.txt');
		$membersidd= explode("\n",$txtt);
		$mmemcount = count($membersidd) -1;
{
sendmessage($chat_id,"*Member List* : $mmemcount");
}
}

	elseif($textmessage == 'Block List' && $chat_id == $admin){
		$txtt = file_get_contents('banlist.txt');
		$membersidd= explode("\n",$txtt);
		$mmemcount = count($membersidd) -1;
{
sendmessage($chat_id,"*Block List* : $mmemcount");
}
}




                  elseif (isset($message['location'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"location mord nazar ersal shod ✅");	
}else{
  
Sendmessage($chat_id,"❌*You Are Banned*❌");	

}
    }
      }
            elseif($rpto != "" && $chat_id == $admin){
    	if($textmessage != "/ban" && $textmessage != "/unban")
    	{
sendmessage($rpto,"$textmessage");
sendmessage($chat_id,"پیغام شما ارسال شد");
    	}
    	else
    	{
    		if($textmessage == "/ban"){
    	$txtt = file_get_contents('banlist.txt');
		$banid= explode("\n",$txtt);
	if (!in_array($rpto,$banid)) {
		$addd = file_get_contents('banlist.txt');
		$addd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $addd);
		$addd .= $rpto."
";

    	file_put_contents('banlist.txt',$addd);
    	{
sendmessage($rpto,"*You Are Added to block list*");
sendmessage($chat_id,"به لیست بلاک شده ها افزوده شد");
        }
    		}
}
    	if($textmessage == "/unban"){
    	$txttt = file_get_contents('banlist.txt');
		$banidd= explode("\n",$txttt);
	if (in_array($rpto,$banidd)) {
		$adddd = file_get_contents('banlist.txt');
		$adddd = str_replace($rpto,"",$adddd);
		$adddd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $adddd);
    $adddd .="
";


		$banid= explode("\n",$adddd);
    if($banid[1]=="")
      $adddd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $adddd);

    	file_put_contents('banlist.txt',$adddd);
}
sendmessage($rpto,"*You Are UnBanned*");
sendmessage($chat_id,"از لیست بلاک شده ها پاک شد");
    		}
    	}
	}
      

        elseif ($textmessage =="Send To All"  && $chat_id == $admin | $booleans[0]=="false") {
	{
          sendmessage($chat_id,"`Pls Send Msg`");
	}
      $boolean = file_get_contents('booleans.txt');
		  $booleans= explode("\n",$boolean);
	  	$addd = file_get_contents('banlist.txt');
	  	$addd = "true";
    	file_put_contents('booleans.txt',$addd);
    	
    }
      elseif($chat_id == $admin && $booleans[0] == "true") {
    $texttoall = $textmessage;
		$ttxtt = file_get_contents('member.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			sendmessage($membersidd[$y],"$texttoall");
			
		}
		$memcout = count($membersidd)-1;
	 	{
	 	Sendmessage($chat_id,"پیغام شما به $memcout مخاطب ارسال شد.");
	 	}
         $addd = "false";
    	file_put_contents('booleans.txt',$addd);
    	}
 elseif($textmessage == 'Clean Blcok List')
 if($chat_id == $admin){
 {
 file_put_contents('banlist.txt',$chat_id);
 Sendmessage($chat_id,"test");
 }
}
?>
