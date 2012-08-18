#!/usr/bin/perl
##################################################################################
# Form2Email.net 30/October/2006
# © 1999-2005 Form2Email.net
##################################################################################
# Instructions:              http://www.Form2Email.net/instructions/
# FAQ:                       http://www.Form2Email.net/faq/
##################################################################################
my $script_name              = 'form2email.pl';
my $HTML_thankyou            = 'http://www.siskiyouvelo.org/followup.html';
my $to                       = 'roadhazards@siskiyouvelo.org';
my $from                     = 'roadhazards@siskiyouvelo.org';
my $mailprog                 = '/usr/sbin/sendmail';
my $subject                  = 'Siskiyou Velo Hazards Notification';
##################################################################################
# 
#          That's everything you need to get your script running!
#                You don't need to change anything else
#      unless you want to use any of the advanced features of the script
# 
##################################################################################
# Advanced Settings Log details
my $log                      = 0;
my $log_name                 = 'log.txt';
my $seperator                =  '##### log #####';
my $data_only                = 0;
##################################################################################
# Admin mode
my $admin_mode               = 'admin';
my $username                 = "username";
my $password                 = "password";
##################################################################################
# Advanced Settings
my $kill_image_buttons_value = 1;
my $kill_html_tags	     = 1;
my $subject_field            = "";
my $max_message_length	     = 9000;
my $max_message_error	     = "Your message is too big";
my $send_just_data           = 0;
##################################################################################
# Advanced Settings From Field Name
my $from_field_name          = 'Email';
my $from_field_name_error    = "Data Error in $from_field_name";
##################################################################################
# Advanced Settings Auto Responder
my $auto_responder           = 0;
my $auto_responder_from      = 'webmaster@siskiyouvelo.org';
my $auto_responder_message   = "responder.txt";
my $auto_responder_subject   = "Out of Office Notification From Siskiyou Velo";
##################################################################################
# Advanced Settings Environmental values
my $REMOTE_ADDR              = 1;
my $HTTP_USER_AGENT          = 1;
my $DATE                     = 1;
##################################################################################
# Advanced Settings SMTP email
# use Net::SMTP;
my $send_via_SMTP            = 0;
my $mailhost                 = 'smtp.com';
##################################################################################
# Advanced Settings required fields
my $required_fields_form     = 0;
my @required_fields          = ();
my @required_fields_numbers  = ();
my @required_fields_email    = ();
my $error_fields_require     = "Field is blank, it is required";
my $error_fields_numbers     = "Only numbers";
my $error_fields_email       = "Email address is not valid";
my $error_fields_forbidden   = "Forbidden field name";
my $error_title              = "<b>Sorry, we need you to check the following</b>";
my $fontColor                = "black";
my $fontSize                 = 3;
my $fontFace                 = "Verdana";
my $return_message           = "Please click 'back' on your browser and try again";
##################################################################################
# Advanced Settings extra thank you page
$HTML_thankyou_field_name = 'ThankYouPage';
$ThankYou{'thankyou1'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou2'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou3'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou4'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou5'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou6'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou7'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou8'}       = 'http://www.your-website-url.co.uk/';
$ThankYou{'thankyou9'}       = 'http://www.your-website-url.co.uk/';
##################################################################################
# Advanced Settings extra email address
$field_name_email = 'SendToAddress';
$SendTo{'email1'}            = 'email@your-website-url.co.uk';
$SendTo{'email2'}            = 'email@your-website-url.co.uk';
$SendTo{'email3'}            = 'email@your-website-url.co.uk';
$SendTo{'email4'}            = 'email@your-website-url.co.uk';
$SendTo{'email5'}            = 'email@your-website-url.co.uk';
$SendTo{'email6'}            = 'email@your-website-url.co.uk';
$SendTo{'email7'}            = 'email@your-website-url.co.uk';
$SendTo{'email8'}            = 'email@your-website-url.co.uk';
$SendTo{'email9'}            = 'email@your-website-url.co.uk';
##################################################################################
# Advanced Settings file upload
my @file_upload_fields       = ();
my $rename_file              = 0;
my $max_size                 = 100000;
my @required_file_types      = ("txt", "jpg", "jpeg", "tif", "gif");
my $error_max_size           = "Your file is too big!";
my $error_file_type          = "File type is not valid!";
my $file_dir                 = "/path/to/your/directory";
my $file_URL                 = "http://www.your-website-url.co.uk/files";
my $useKb                    = 0;
##################################################################################
# 
#          Stop! You don't need to edit anything below this line
# 
##################################################################################
# Form2Email.net © 1999 - 2005
# This script is available for private and commercial use
# You may not sell this script in any format to anybody
# The script may only be distributed by Form2Email.net
# Do not post or email any part of the this code in any format whatsoever
# The redistribution of modified versions of the scripts is strictly prohibited
# Form2Email.net accepts no responsibility or liability
# whatsoever for any damages however caused when using this script
# By downloading and using this script you agree to the terms and conditions
################################################################################## 

use CGI qw/:standard :cgi-lib/;

@date=localtime();
$date[4]++;
$date[5]+=1900;
$date="$date[5]-$date[4]-$date[3]";

%FORM = parse_cgi();

# check required fields param in form if necessary
if ($required_fields_form) {
   my $reqfields = $FORM{required_fields};
   $reqfields =~ s/\s//g;
   @required_fields = ();
   @required_fields = split(/,/,$reqfields);  
   $reqfields = $FORM{required_fields_numbers};
   $reqfields =~ s/\s//g;
   @required_fields_numbers = ();
   @required_fields_numbers = split(/,/,$reqfields);  
   $reqfields = $FORM{required_fields_email};
   $reqfields =~ s/\s//g;
   @required_fields_email = ();
   @required_fields_email = split(/,/,$reqfields);  
}

if($FORM{mode} eq $admin_mode){
	if($FORM{login}){
		if ($FORM{login} eq $username && $FORM{password} eq $password){$FORM{hash}=crypt($password,$username);}
		else{promt();}
	}
	elsif ($FORM{hash} ne crypt($password,$username)){promt()};
	#read log file
	open(F, "$log_name") or error("Can't open log-file");
	local $/;
	my $data = <F>;
	close F;
	$seperator.="\n";
	@data = split($seperator, $data);	
	#delete if act=delete
	if ($FORM{action} eq 'delete'){
		@items =split(", ", $FORM{Item});
		
		for(@items){$Items{$_}=1;}
		#error($Items{1});
		my $i=1;
		my $a=0;
		my @new_data;
		for(@data){
			unless($Items{($i)}){
				$new_data[$a++] = $_ ;
				
			}
			$i++;
		}
		@data=@new_data;
		open (LOG, ">$log_name") or error("Can't open log file");
		print LOG join("$seperator",@data);
		close LOG;
		print "Location: $script_name?mode=$admin_mode&hash=$FORM{hash}\n\n";
		exit;
	}

	my $total = @data;
	my $i=1;
	my $txt = qq~
	<script>
		function deleteItem(){
			myform.submit();
		}
	</script>
	<form name=myform><input type=hidden name=mode value=$admin_mode>
	<input type=hidden name=action value=delete>
	<input type=hidden name=hash value=$FORM{hash}>
	<table width=600><tr><th class=of align=center>Total Emails: $total</th></tr>
	<tr><td class=off align=right><a href=javascript:deleteItem()>delete marked messages</a>&nbsp;</td></tr>~;
	for(@data){
		$_=~s/\n/<br>/g;
		$txt.= "<tr><td class=of>&nbsp;Email #$i</td></tr>";
		$txt.= "<tr><td class=off>$_</td></tr>";
		$txt.= "<tr><td class=off align=right><a href=$script_name?mode=$admin_mode&action=delete&Item=$i&hash=$FORM{hash}>delete</a> <input type=checkbox name=Item value=$i>&nbsp;</td></tr>";
		$i++;
	}
	$txt.="</table></form>";
	
	html_text($txt);

	exit;
}

push @required_fields,$field_name_email unless $to;
push @required_fields_email,$from_field_name if $FORM{$from_field_name};
test_form();
my @to = ();
my $logText = "";
foreach(@field){
	if ($_ eq $from_field_name){
		$from =  $FORM{$from_field_name} if  $FORM{$from_field_name};
		error("$from_field_name_error")  if length($FORM{$from_field_name})>40;
		error("$from_field_name_error")  if $FORM{$from_field_name}=~m/:/s;
		error("$from_field_name_error")  if $FORM{$from_field_name}=~m/Content-type/is;
		error("$from_field_name_error")  if $FORM{$from_field_name}=~m/\n/is;
		$from =~ s/<([^>]|\n)*>//gs;
		$from =~ s/ //gs;
		$from =~ s/\n|\r//gs;
		$message.="$_: " unless $send_just_data;
		$message.="$FORM{$_}\n";
		$logText.="$_: " unless $data_only;
		$logText.="$FORM{$_}\n";
	}
	elsif ($_ eq $subject_field){$subject =  $FORM{$subject_field} if  $FORM{$subject_field};}
	elsif ($_ eq $HTML_thankyou_field_name){$HTML_thankyou =  $ThankYou{$FORM{$HTML_thankyou_field_name}} if  $ThankYou{$FORM{$HTML_thankyou_field_name}};}
	elsif ($_ eq $field_name_email){
		if  ($FORM{$field_name_email}){
			my @mails =  split(", ",$FORM{$field_name_email});
			foreach(@mails){push @to, $SendTo{$_} if $SendTo{$_};}
		}
	}
        elsif (($_ eq "required_fields") || ($_ eq "required_fields_numbers") || ($_ eq "required_fields_email")) { }
        elsif ((in_array($_ ,@file_upload_fields)) && ($FORM{$_} ne ""))
        {
	  my $fn = save_file($_,$file_dir);
          $message .= "$_ : " unless $send_just_data;
          $message .= "$file_URL/$fn\n"; 
          $logText .= "$_ : " unless $data_only;
          $logText .= "<a href=\"$file_URL/$fn\">$file_URL/$fn</a>\n";
        }
	else{
           if ( !($kill_image_buttons_value && $_=~/(\.|\A)(x|y)\Z/) ) {
		$message.="$_: " unless $send_just_data;
		$message.="$FORM{$_}\n";
		$logText.="$_: " unless $data_only;
		$logText.="$FORM{$_}\n";
           }
	}

} 
push @to, $to if $to && !@to;

my @checker = split(/[\r\n]/, $message);
my $line;
foreach $line(@checker)
{  if (($line =~ /^to:/i) || ($line =~ /^cc:/i) || ($line =~ /^bcc:/i) || ($line =~ /^from:/i) || ($line =~ /^reply-to:/i) || ($line =~ /Content-Type:/i)) {
      error("use of reserved words to:, cc:, bcc: or reply-to: at line \"$line\"<br>");
   }
}

if ($REMOTE_ADDR){
	$message.="REMOTE_ADDR: " unless $send_just_data;
	$message.="$ENV{REMOTE_ADDR}\n";
	$logText.="REMOTE_ADDR: " unless $data_only;
	$logText.="$ENV{REMOTE_ADDR}\n";
}
if ($HTTP_USER_AGENT){
	$message.="HTTP_USER_AGENT: " unless $send_just_data;
	$message.="$ENV{HTTP_USER_AGENT}\n";
	$logText.="HTTP_USER_AGENT: " unless $data_only;
	$logText.="$ENV{HTTP_USER_AGENT}\n";
}
if ($DATE){
	$message.="DATE: " unless $send_just_data;
	$message.="$date[2]:$date[1]:$date[0] $date\n";
	$logText.="DATE: " unless $data_only;
	$logText.="$date[2]:$date[1]:$date[0] $date\n";
}
error($sendToError) unless @to;
error($max_message_error) if length($message)>$max_message_length;

if($log){
	$logText="To: ".join(", ", @to)."\nSubject: $subject\n".$logText;
	$logText="$seperator\n".$logText if -f $log_name;
	open (LOG, ">>$log_name") or error("Can't open log file");
	print LOG $logText;
	close LOG;
}

foreach(@to){
        my $to = $_;
        $to =~ s/\r//g; $from =~ s/\r//g; $subject =~ s/\r//g;
        $to =~ s/\n//g; $from =~ s/\n//g; $subject =~ s/\n//g;
	male("$to", "$from", $subject, $message);
	}
if($auto_responder && $FORM{$from_field_name}){
	$FORM{$from_field_name} =~ s/<([^>]|\n)*>//g;
	$FORM{$from_field_name} =~ s/ //g;
	$FORM{$from_field_name} =~ s/ //gs;
	$FORM{$from_field_name} =~ s/\n|\r//gs;
	open(F, "$auto_responder_message") or error("Can't open message file");
	my @message=<F>;
	close F;
	male("$FORM{$from_field_name}","$auto_responder_from",$auto_responder_subject, join('',@message)); 
	
	}

print "Location: $HTML_thankyou\n\n";
exit;

sub in_array {
   (my $fieldname, my @uploadfields) = @_;

   my $field;
   foreach $field(@uploadfields)
   {
     if ($field eq $fieldname) {
        return 1;
     }
   }
   return 0;
}

sub error{
	print "Content-type: text/html\n\n";
	print "<html><head><title>Error</title></head><body><br><font color=$fontColor size=$fontSize face=$fontFace>$error_title<br><br>$_[0]<br>";
	print "$return_message" if $_[1];
	print "</font></body></html>";
	exit;

}

sub save_file {
	$file=param($_[0]);

	$file =~m/([^\\\/]*\.\w*\Z)/i;
	$filename=$1;
	$filename=~m/.*\.(\w*\Z)/i;
	$type = $1;
	my $found=0;
	foreach(@required_file_types){$found =1 if lc $_ eq lc $type}	
	error($error_file_type." for type $type") unless $found;
	my $tmp_size =0;
	if($rename_file){
		$filename = $_[0]."_".int(rand(10000)).".".$type;
		while(-e $filename){$filename = $_[0]."_".int(rand(10000)).".".$type;}
	}
	$filename =~ s/\s/_/g;
	open(FILE,">$_[1]/$filename") || error("Can't save file $_[1]/$filename");
	binmode FILE; 
	while ($bytesread=read($file,$buffer,1024)) {
		print FILE $buffer;
		$tmp_size+=1024;
		if($max_size && $max_size<$tmp_size){
		close FILE; unlink "$_[1]/$filename";
		error("$error_max_size");
		}
	} 
	close(FILE);
        return "$filename"; 
}

sub parse_cgi{

  my %FORM = Vars;
  @field = param;
  my $a++;
  foreach $key (keys %FORM) {
       	$FORM{$key} =~ s/%(..)/pack("c",hex($1))/ge;
       	$FORM{$key} =~ s/<!--(.|\n)*-->//g if $kill_html_tags;
       	$FORM{$key} =~ s/<([^>]|\n)*>//g if $kill_html_tags;
       	$FORM{$key} =~ s/\r//g;
  }
  return %FORM;
}



sub male{

	if($send_via_SMTP){
	    $smtp = Net::SMTP->new($mailhost);

	    $smtp->mail($_[1]);
	    $smtp->to($_[0]);

	    $smtp->data();
	    $smtp->datasend("To: $_[0]\n");
	    $smtp->datasend("From: $_[1]\n");
	    $smtp->datasend("Subject: $_[2]\n\n");
	    $smtp->datasend("\n");
	    $smtp->datasend("$_[3]\n");
	    $smtp->dataend();

	    $smtp->quit;

	}
	else{
		open(MAIL,"|$mailprog -t");
		print MAIL "To: $_[0]\n";
		print MAIL "From: $_[1]\n";
		print MAIL "Subject: $_[2]\n\n";
		print MAIL "$_[3]\n";
		close(MAIL);
		#print "To: $_[0]<br>";

	}
}

sub test_form{
	my $errors='';
	foreach(@required_fields){
		$errors.="ERROR FIELD &lt; $_ &gt;: $error_fields_require!<br>" if $FORM{$_} eq "";
	}
	foreach(@required_fields_numbers){
		$errors.="ERROR FIELD &lt; $_ &gt;: $error_fields_numbers!<br>" if $FORM{$_}=~m/\D/ or $FORM{$_} eq '';
	}
	foreach(@required_fields_email){
		$errors.="ERROR FIELD &lt; $_ &gt;: $error_fields_email!<br>" if $FORM{$_} !~m/\A\S+?\@\S+?\.\S+?\Z/;
	}                                                                                                                        
        foreach(keys(%FORM)){
           if ( (uc($_) eq uc('to')) || (uc($_) eq uc('cc')) || (uc($_) eq uc('bcc'))|| (uc($_) eq uc('reply-to'))|| (uc($_) eq uc('from'))|| (uc($_) eq uc('Content-Type'))) {
                $errors.="ERROR FIELD &lt; $_ &gt;: $error_fields_forbidden!<br>";
           }
        }       
        foreach (keys(%FORM)){
           if (($FORM{$_} =~ /bcc:/i)||($FORM{$_} =~ /cc:/i)||($FORM{$_} =~ /from:/i)||($FORM{$_} =~ /reply-to:/i)||($FORM{$_} =~ /Content-Type:/i) ||($FORM{$_} =~ /to:/i)) {
                $errors.="ERROR FIELD &lt; $_ &gt;: use of reserved words to:, cc:, bcc: or reply-to:!<br>";
           }
        }
        my $http_user_agent = $ENV{HTTP_USER_AGENT};
        $http_user_agent =~ s/[\s\n]//g;
        if ($http_user_agent eq "") { $errors .= "ERROR : browser problem<br>";}
        if ($http_user_agent =~ /Missigua Locator/i) { $errors .= "ERROR : bot<br>";}

	error("$errors",1) if $errors;
	
	
return;
}

sub html_text{
	print "Content-type: text/html\n\n" unless $type;
	print qq|<html>
		<head>
		<title>:: Admin Mode</title>
		<style>
			tr,td,body {font-family: Tahoma, Arial; font-size:10pt;}  
			.of {border-style:solid; border-width: 1; border-color : #999999; background-color:#dddddd}
			.on {border-style:none;background-color:#ddeedd}
			.off {border-style:none;background-color:#f3f3f3}
		</style>
		</head>
		<body>
		$_[0]
		</body></html>|;
	exit;

} 
sub promt{
	    html_text(qq|
	      <br><br><br><br><center><form method=post action=$script_name>
	      <input type=hidden name=mode value=$admin_mode>
	      <table border=0 width=280>
	        <tr><th colspan=2 class=of>:: Admin mode</th></tr>
		<tr class=on><td width=43% align=right>Login: </td>
		    <td width=57%><input type=text name=login size=13></td></tr>
		<tr class=on><td align=right>Password: </td>
		    <td><input type=password name=password size=13></td></tr>
		<tr class=on><td colspan=2 align=center><input type=submit></td></tr>
	      </table></form></center>|);
	exit;
}
##################################################################################
# Form2Email.net © 1999 - 2005
# This script is available for private and commercial use
# You may not sell this script in any format to anybody
# The script may only be distributed by Form2Email.net
# Do not post or email any part of the this code in any format whatsoever
# The redistribution of modified versions of the scripts is strictly prohibited
# Form2Email.net accepts no responsibility or liability
# whatsoever for any damages however caused when using this script
# By downloading and using this script you agree to the terms and conditions
################################################################################## 
