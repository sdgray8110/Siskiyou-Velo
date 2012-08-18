#!/usr/local/bin/perl

$|=1;

use CGI::Carp qw(fatalsToBrowser);
use CGI qw/:standard/;
# $CGI::POST_MAX=4048 * 100;

$file_directory = "/home/gray8110/public_html/uploads";




##########################################################################
##########################################################################


$filename = param('file_name');
$submit = param('submit');

$new = "$filename";
@new_split = split(/\\/, $new);
$new_name = pop(@new_split);
$new_name =~ s/ /_/g;

$fullfile = "$file_directory/$filename";

print "Content-type: text/html\n\n";
print qq~
<HTML>
<HEAD>
<TITLE>Upload File</TITLE>
<META NAME="robots" CONTENT="noindex">
<script Language="JavaScript"><!--
 function rightclick() {
   if (event.button==2) {
     alert('Please use your left mouse button to open this link.');
     return (false);
   }
 }
 function open_window(url,width,height,name,scroll) {
   if (document.SubmitFile.file_name.value != "") {
      var winl = (screen.width - width) / 2;
      var wint = (screen.height - height) / 2;
      mywin = window.open(url,name,'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars='+scroll+',resizable=1,width='+width+',height='+height+',left='+winl+',top='+wint+',dependent=1')
   }
 }
 function CloseMyWin() {
   if (document.SubmitFile.file_name.value != "") {
     if (mywin && !mywin.closed) {
        mywin.close()
     }
   }
 }
 function processing(width,height) {
  if (document.SubmitFile.file_name.value != "") {
    var winl = (screen.width - width) / 2;
    var wint = (screen.height - height) / 2;
    mywin = window.open('','Processing','width='+width+',height='+height+',left='+winl+',top='+wint+',toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0');
    var tmp = mywin.document;
    tmp.write('<html><head><title>Processing Image</title>');
    tmp.write('<style><!-- body {font: 12pt Arial, Helvetica; font-weight: bold;} --></style>');
    tmp.write('</head><body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>');
    tmp.write('<center><br><i>Uploading File<br>Please Wait</i><br><br>');
    tmp.write('<img width=128 height=60 border=0 src="../images/processing.gif" alt="Processing...."></center>');
    tmp.write('</body></html>');
    tmp.close();
  }
 }
//--></SCRIPT>

<style><!--
body,td,a,p,.h {font: 12pt Arial, Helvetica;}
.headline {font: 16pt Arial, Helvetica; font-weight: bold;}
--></style>

</HEAD>

<BODY leftMargin=10 topMargin=10 marginwidth=10 marginheight=10 BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#006600" VLINK="#006600" ALINK="#006600" onUnload="CloseMyWin()">

~;

&form if ($submit ne "" && $filename eq "");

if ($filename ne "") {
   &Print_Results;
} else {
   &form;
}

#########################################################
### THE MAIN FORM #######################################

sub form {

if ($submit ne "" && $filename eq "") {
  $msg = qq~\n<center><span class="headline"><font color="#990000">Please choose a file to upload!</font></span></center>\n<br>~;
}

opendir (FILES, "$file_directory") || print "Cannot open file directory!";
@files = readdir(FILES);
closedir (FILES);
foreach $file (@files) {
  $arr .= "'$files'," if ($file ne "." && $file ne "..");
}
$arr =~ s/,$//;

print qq~
<script Language="JavaScript"><!--
  function checkFiles() {
    var filename = document.SubmitFile.file_name.value;
    var files = new Array($arr);
    var filect = files.length;
    var space_arr = filename.split("\\\\");
    var new_name = space_arr.pop();
    var found = 0;
    if (filect > 1) {
      for(i = 0; i < filect; i++) {
        if (new_name == files[i]) {
           found++;
        }
      }
    }
    if (found > 0) {
      var agree=confirm("The file '" + new_name + "' already exists. Overwrite this file?");
        if (agree) {
           return(true);
        } else {
           return(false);
        }
    }
  }
//--></SCRIPT>

<center>
$msg
<FORM NAME="SubmitFile" METHOD="post" ACTION="upload.cgi" ENCTYPE="multipart/form-data" onSubmit="processing('180','180')">
<table border=0 cellpadding=0 cellspacing=0>
 <tr>
  <td>
   Choose the file from your hard drive.
   <br>
  </td>
 </tr>
 <tr>
  <td>
   <hr size=1 color="#CCCCCC">
  </td>
 </tr>
 <tr>
  <td>
   <INPUT TYPE="file" NAME="file_name" SIZE="28" style="width:400px; height:20px; font-family:arial; font-size:11; color:#000000; background-color:#FFFFFF; border: solid 1px #000000;">
  </td>
 </tr>
 <tr>
  <td>
   <hr size=1 color="#CCCCCC">
  </td>
 </tr>
 <tr>
  <td align=center>
   <br>
   <INPUT TYPE="submit" NAME="submit" VALUE="Click Once to Upload" onClick="return checkFiles();">
  </td>
 </tr>
</table>
</form>
</center>
</BODY>
</HTML>
~;

exit;

}

#########################################################
### Upload and Return Successful Message ################

sub Print_Results {

     $file_size = 0;
     open (MYFILE, ">$fullfile") || &error("Can't create $fullfile!");
           binmode (MYFILE); 
           while($line=read($filename,$data,1024))   { 
              print MYFILE $data; 
              $file_size += $line; 
           } 
     close(MYFILE);


     rename("$fullfile", "$file_directory/$new_name");
     $file_size = ($file_size / 1000);
     $file_size = sprintf("%.1f", $file_size);

     $msg = qq~<center><span class="headline">Thank you, your file named "$new_name" ($file_size KB) has been uploaded to the server! <br><br></span></center>~;

&continue;

}


#########################################################
### Continue ############################################

sub continue {

print qq~
<br>
$msg
<p>
<center>
<a href="javascript:history.go(-1)" onMousedown="rightclick()"><u><< Go Back</u></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="javascript:self.close()" onMousedown="rightclick()"><u>Close Window</u></a>
</center>
</body>
</html>
~;

exit;

}


#######################################################################
### FILE OPEN ERROR ###################################################

sub error {

$error = $_[0];

print qq~<font color="#990000">$error</font>~;

}

