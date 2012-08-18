#!/usr/bin/perl
use vars qw(%config %category %form);
use strict;
#-###########################################################################
# 
# In accordance with the GPL, this copyright notice MUST remain intact:
#
# EveryAuction Release Version 1.53 (2/17/02)
# Copyright (C) 2000-2002 EverySoft
# Registered with the United States Copyright Office, TX5-186-526
# http://www.everysoft.com/
#
#-###########################################################################
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
#
#-###########################################################################
#
# Modification Log (please add new entries to bottom):
#
# * 02/2000
#   Matt Hahnfeld (matth@everysoft.com) - Original Concept and Design
#   Version available from http://www.everysoft.com/
#
# * MM/YYYY
#   Name (email) - Modification
#   Availability
#
#-###########################################################################

#-#############################################
# Configuration Section
# Edit these variables!
local %config;

# The Base Directory.  We need an
# absolute path for the base directory.
# Include the trailing slash.  THIS SHOULD
# NOT BE WEB-ACCESSIBLE!

$config{'basepath'} = '../Auction/';

# Closed Auction Directory
# This is where closed auction items are stored.
# Leave this blank if you don't want to store
# closed auctions.  It can potentially take
# up quite a bit of disk space.

$config{'closedir'} = 'closed';

# User Registration Directory
# This is where user registrations are stored.
# Leave this blank if you don't want to
# require registration.  It can potentially
# take up quite a bit of disk space.

$config{'regdir'} = 'reg';

# List each directory and its associated
# category name.  These directories should
# be subdirectories of the base directory.

%category = (
	MLC => '2007 Mountain Lakes Challenge Silent Auction',
);

# This is the password for deleting auction
# items.

$config{'adminpass'} = 'greensprings';

# You need to assign either a mail program or
# a mail host so confirmation e-mails can
# be sent out.
# Leave one commented and one uncommented.
#
# YOU NEED EITHER A MAIL PROGRAM
# $config{'mailprog'} = '/usr/lib/sendmail -t';
#
# OR YOU NEED A MAIL HOST (SMTP)

$config{'mailhost'} = 'smtp.siskiyouvelo.org';

# This line should be your e-mail address

$config{'admin_address'} = 'webmaster@siskiyouvelo.org';

# This line should point to the URL of
# your server.  It will be used for sending
# "you have been outbid" e-mail.  The script
# name and auction will be appended to the
# end automatically, so DO NOT use a trailing
# slash.  If you do not want to send outbid
# e-mail, leave this blank.

$config{'scripturl'} = 'www.mountainlakeschallenge.com';

# This will let you define colors for the
# tables that are generated and the
# other page colors.  The default colors
# create a nice "professional" look.  Must
# be in hex format.

$config{'colortablehead'} = '#ECECE8';
$config{'colortablebody'} = '#F7F7F1';

# Site Name (will appear at the top of each page)

$config{'sitename'} = 'Mountain Lakes Challenge';

# You can configure your own header which will
# be appended to the top of each page.

$config{'header'} =<<"EOF";
<HTML>
<HEAD>
<TITLE>$config{'sitename'} - Powered By EveryAuction</TITLE>
<link href="../Auction/auction.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="body">
	<TABLE WIDTH=800 BORDER=0><TR><TD VALIGN=TOP WIDTH=450>
		<h1>$config{'sitename'}</h1>
		<h2>Online Auction</h2>
	</TD><TD VALIGN=TOP ALIGN=LEFT>
		<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>
		<INPUT class=input TYPE=TEXT NAME=searchstring>
		<INPUT TYPE=SUBMIT VALUE="Search">
		<INPUT TYPE=HIDDEN NAME=action VALUE="search">
		<INPUT TYPE=RADIO NAME=searchtype VALUE="keyword" CHECKED><span class="formspan">keyword</span> <INPUT TYPE=RADIO NAME=searchtype VALUE="username"><span class="formspan">username</span>
		</FORM>
	</TD></TR></TABLE>
	<P>
EOF

# You can configure your own footer which will
# be appended to the bottom of each page.
# Although not required, a link back to
# everysoft.com will help to support future
# development.

$config{'footer'} =<<"EOF";
<P>
<CENTER><p>Return to <a href="http://www.mountainlakeschallenge.com">Mountain Lakes Challenge Homepage</a><br/><strong>Siskiyou Velo &copy; 2002 - 2007</strong></p></CENTER>
</BODY>
</HTML>
EOF

# Sniper Protection...  How many minutes
# past last bid to hold auction.  If auctions
# should close at exactly closing time, set
# to zero.

$config{'aftermin'} = 5;

# File locking enabled?  Should be 1 (yes)
# for most systems, but set to 0 (no) if you
# are getting flock errors or the script
# crashes.

$config{'flock'} = 1;

# User Posting Enabled- 1=yes 0=no

$config{'newokay'} = 1;

#-#############################################
# Main Program
# You do not need to edit anything below this
# line.

#-#############################################
# Print The Page Header
#
print "Content-type: text/html\n\n";
print $config{'header'};
#
#-#############################################

local %form = &get_form_data;
if ($form{'action'} eq 'new') { &new; }
elsif ($form{'action'} eq 'repost') { &new; }
elsif ($form{'action'} eq 'procnew') { &procnew; }
elsif ($form{'action'} eq 'procbid') { &procbid; }
elsif ($form{'action'} eq 'reg') { &reg; }
elsif ($form{'action'} eq 'procreg') { &procreg; }
elsif ($form{'action'} eq 'creg') { &creg; }
elsif ($form{'action'} eq 'proccreg') { &proccreg; }
elsif ($form{'action'} eq 'closed') { &viewclosed1; }
elsif ($form{'action'} eq 'closed2') { &viewclosed2; }
elsif ($form{'action'} eq 'closed3') { &viewclosed3; }
elsif ($form{'action'} eq 'admin') { &admin; }
elsif ($form{'action'} eq 'procadmin') { &procadmin; }
elsif ($form{'action'} eq 'search') { &procsearch; }
elsif ($form{'item'} eq int($form{'item'}) and $category{$form{'category'}}) { &dispitem; }
elsif ($category{$form{'category'}}) { &displist; }
else { &dispcat; }

#-#############################################
# Print The Page Footer
#
print "<P><P ALIGN=CENTER><FONT SIZE=-1><A HREF=$ENV{'SCRIPT_NAME'}>[Category List]</A>";
print " <A HREF=$ENV{'SCRIPT_NAME'}?action=new>[Post New Item]</A>" if ($config{'newokay'});
print " <A HREF=$ENV{'SCRIPT_NAME'}?action=reg>[New Registration]</A> <A HREF=$ENV{'SCRIPT_NAME'}?action=creg>[Change Registration]</A>" if ($config{'regdir'});
print " <A HREF=$ENV{'SCRIPT_NAME'}?action=closed>[Closed Auctions]</A>" if ($config{'regdir'}) and ($config{'closedir'});
print " </FONT></P>\n";
print $config{'footer'};
#
#-#############################################

#-#############################################
# Sub: Display List Of Categories
# This creates a "nice" list of categories.

sub dispcat {
	print "<H2>Auction Categories</H2><TABLE WIDTH=800 class=maintab>\n";
	print "<TR><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Category</B></TD><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Items</B></TD></TR>";
	my $key;
	foreach $key (sort keys %category) {
		umask(000);  # UNIX file permission junk
		mkdir("$config{'basepath'}$key", 0777) unless (-d "$config{'basepath'}$key");
		opendir DIR, "$config{'basepath'}$key" or &oops("Category directory $key could not be opened.");
		my $numfiles = scalar(grep -T, map "$config{'basepath'}$key/$_", readdir DIR);
		closedir DIR;
		print "<TR><TD BGCOLOR=$config{'colortablebody'}><A HREF=$ENV{'SCRIPT_NAME'}\?category=$key>$category{$key}</A></TD><TD BGCOLOR=$config{'colortablebody'}>$numfiles</TD></TR>";
	}
	print "</TABLE>\n";
}

#-#############################################
# Sub: Display List Of Items
# This creates a "nice" list of items in a
# category.

sub displist {
	print "<H2>$category{$form{'category'}}</H2>\n";
	print "<TABLE class=maintab WIDTH=800>\n";
	print "<TR><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Item</B></TD><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Closes</B></TD><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Num Bids</B></TD><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>High Bid</B></TD></TR>\n";
	opendir THEDIR, "$config{'basepath'}$form{'category'}" or &oops("Category directory $form{'category'} could not be opened.");
	my @allfiles = grep -T, map "$config{'basepath'}$form{'category'}/$_", sort { int($a) <=> int($b) } (readdir THEDIR);
	closedir THEDIR;
	my $file;
	foreach $file (@allfiles) {
		$file =~ s/^$config{'basepath'}$form{'category'}\///;
		$file =~ s/\.dat$//;
		my ($title, $reserve, $inc, $desc, $image, @bids) = &read_item_file($form{'category'},$file);
		if ($title ne '') {
			my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$#bids]);
			my @closetime = localtime($file);
			$closetime[4]++;
			print "<TR><TD BGCOLOR=$config{'colortablebody'}><A HREF=$ENV{'SCRIPT_NAME'}\?category=$form{'category'}\&item=$file>$title</A>";
			print " <FONT COLOR=#3333FF SIZE=-1>[PIC]</FONT>" if ($image);
			print "</TD><TD BGCOLOR=$config{'colortablebody'}>$closetime[4]/$closetime[3]</TD><TD BGCOLOR=$config{'colortablebody'}>$#bids</TD><TD BGCOLOR=$config{'colortablebody'}>\$$bid</TD></TR>\n";
		}
	}
	print "</TABLE>\n";
}

#-#############################################
# Sub: Display Item
# This displays a particular item, its
# description, and its associated bids.

sub dispitem {
	my ($title, $reserve, $inc, $desc, $image, @bids) = &read_item_file($form{'category'},$form{'item'});
	&oops("Item $form{'item'} could not be opened.  If this item is closed, you can view statistics and bid history using our <A HREF=$ENV{'SCRIPT_NAME'}\?action=closed>closed item viewer</A>.") if $title eq '';
	my $nowtime = localtime(time);
	my $closetime = localtime($form{'item'});
	my $html_description = &strip_scripts(&enable_html($desc));
	print "<H2>$title</H2><HR><H1>Information</H1><HR>\n";
	print "<TABLE WIDTH=800><TR>";
	print "<TD BGCOLOR=$config{'colortablebody'}><IMG SRC=$image></TD>" if ($image);
	print "<TD><TABLE class=maintab><TR><TD BGCOLOR=$config{'colortablehead'}><B>$title</B></TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Category:</B> <A HREF=$ENV{'SCRIPT_NAME'}\?category=$form{'category'}>$category{$form{'category'}}</A></TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}>";
	my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[0]); # read first bid
	print "<B>Offered By:</B> <A HREF=mailto:$email>$alias</A></TR></TD><TR><TD BGCOLOR=$config{'colortablebody'}><B>Current Time:</B> $nowtime</TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Closes:</B> $closetime<BR><FONT SIZE=-2>Or $config{'aftermin'} minutes after last bid...</FONT></TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Number of Bids:</B> $#bids</TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}>";
	my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$#bids]); # read last bid
	print "<B>Last Bid:</B> \$$bid ";
	print "<FONT SIZE=-1>(reserve price not yet met)</FONT>" if ($bid < $reserve);
	print "<FONT SIZE=-1>(reserve price met)</FONT>" if (($bid >= $reserve) and ($reserve > 0));
	print "</TD></TR></TABLE></TD></TR></TABLE>\n";
	print "<HR><H1>Description<H1><HR>$html_description</FONT></FONT></B></I></U></H1></H2></H3></H4></H5>";
	print "<HR><H1>Bid History</H1><HR>\n";
	my $lowest_new_bid;
	if ($#bids) {
		for (my $i=1; $i<scalar(@bids); $i++) {
			my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$i]);
			my $bidtime = localtime($time);
			print "<FONT SIZE=-1>$alias \($bidtime\) - \$$bid</FONT><BR>";
		}
		$lowest_new_bid = &parsebid($bid+$inc);
	}
	else {
		print "<FONT SIZE=-1>No bids yet...</FONT><BR>";
		$lowest_new_bid = (&read_bid($bids[0]))[2];
	}
	# either the item is closed or we will display a bid form
	my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$#bids]); # read the last bid
	if ((time > int($form{'item'})) && (time > (60 * $config{'aftermin'} + $time))) {
		print "<FONT SIZE=-1 COLOR=#FF0000><B>BIDDING IS NOW CLOSED</B></FONT><BR>";
		&closeit($form{'category'},$form{'item'});
	}
	else {
		print <<"EOF";
<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>
<HR><H1>Place A Bid</H1><HR>
<INPUT TYPE=HIDDEN NAME=action VALUE=procbid>
<INPUT TYPE=HIDDEN NAME=ITEM VALUE="$form{'item'}">
<INPUT TYPE=HIDDEN NAME=CATEGORY VALUE="$form{'category'}">
<B>The High Bid Is:</B> \$$bid<BR>
<B>The Lowest You May Bid Is:</B> \$$lowest_new_bid
<P>Please note that by placing a bid you are making a contract between you and the seller.
Once you place a bid, you may not retract it.  In some states, it is illegal to win
an auction and not purchase the item.  In other words, if you don't want to pay for it,
don't bid!
EOF

		if ($config{'regdir'}) {
			print <<"EOF";
<br /><p><strong><A HREF=$ENV{'SCRIPT_NAME'}?action=reg>Registration</A> is required to post or bid!</strong></p>
<P><span class="formtext">Your Handle/Alias:</span> <INPUT class="bodyinput" NAME=ALIAS TYPE=TEXT SIZE=30 MAXLENGTH=30> (used to track your bid)</P>
<p><span class="formtext">Your Password:</span> <INPUT class="bodyinput" NAME=PASSWORD TYPE=PASSWORD SIZE=30> (must be valid)</p>
<p><span class="formtext">Your Bid:</span> \$<INPUT class="bodyinput" NAME=BID TYPE=TEXT SIZE=7 VALUE="$lowest_new_bid"></p>
EOF
		}
		else {
			print <<"EOF";
<P><span class="formtext">Your Handle/Alias:</span> <INPUT class="bodyinput" NAME=ALIAS TYPE=TEXT SIZE=30 MAXLENGTH=30> (used to track your bid)</P>
<p><span class="formtext">Your E-Mail Address:</span> <INPUT class="bodyinput" NAME=EMAIL TYPE=TEXT SIZE=30> (must be valid)</p>
<p><span class="formtext">Your Bid:</span> \$<INPUT class="bodyinput" NAME=BID TYPE=TEXT SIZE=7 VALUE="$lowest_new_bid">
<P><span class="formtext">Contact Information:</span> (will be given out only to the seller)</P>
<p><TT><span class="formtext">Full Name:</span> </TT><BR><INPUT class="bodyinput" NAME=ADDRESS1 TYPE=TEXT SIZE=30></p>
<p><TT><span class="formtext">Street Address:</span> </TT><BR><INPUT class="bodyinput" NAME=ADDRESS2 TYPE=TEXT SIZE=30></p>
<p><TT><span class="formtext">City, State, ZIP:</span> </TT><BR><INPUT class="bodyinput" NAME=ADDRESS3 TYPE=TEXT SIZE=30></p>
EOF
		}
		print <<"EOF";
<INPUT TYPE=SUBMIT VALUE="Place Bid">
EOF
	}
}

#-#############################################
# Sub: Add New Item
# This allows a new item to be put up for sale

sub new {
	my ($title, $reserve, $inc, $desc, $image, @bids);
	my $inc = '1.00'; # default increment
	if ($form{'REPOST'}) {
		$form{'REPOST'} =~ s/\W//g;
		if (-T "$config{'basepath'}$config{'closedir'}/$form{'REPOST'}.dat") {
			open THEFILE, "$config{'basepath'}$config{'closedir'}/$form{'REPOST'}.dat";
			($title, $reserve, $inc, $desc, $image, @bids) = <THEFILE>;
			close THEFILE;
			chomp($title, $reserve, $inc, $desc, $image, @bids);
		}
	}
	print <<"EOF";
<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>
<H2>Post A New Item</H2>
<TABLE WIDTH=800 class=maintab BGCOLOR=$config{'colortablebody'}>
<INPUT TYPE=HIDDEN NAME=action VALUE=procnew>
<TR><TD VALIGN=TOP><B>Title/Item Name:<BR></B>No HTML</TD><TD><INPUT NAME=TITLE VALUE=\"$title\" TYPE=TEXT SIZE=50 MAXLENGTH=50></TD></TR>
<TR><TD VALIGN=TOP><B>Category:<BR></B>Select One</TD><TD><SELECT NAME=CATEGORY>
<OPTION SELECTED></OPTION>
EOF
	my $key;
	foreach $key (sort keys %category) {
		print "<OPTION VALUE=\"$key\">$category{$key}</OPTION>\n";
	}
	print <<"EOF";
</SELECT></TD></TR>
<TR><TD VALIGN=TOP><B>Image URL:<BR></B>Optional, should be no larger than 200x200</TD><TD><INPUT NAME=IMAGE VALUE=\"$image\" TYPE=TEXT SIZE=50 VALUE="http://"></TD></TR>
<TR><TD VALIGN=TOP><B>Days Until Close:<BR></B>1-14</TD><TD><INPUT NAME=DAYS TYPE=TEXT SIZE=2 MAXLENGTH=2></TD></TR>
<TR><TD VALIGN=TOP><B>Description:<BR></B>May include HTML - This should include the condition of the item, payment and shipping information, and
any other information the buyer should know.</TD><TD><TEXTAREA NAME=DESC ROWS=5 COLS=45>$desc</TEXTAREA></TD></TR>
<TR><TD COLSPAN=2 VALIGN=TOP>Please note that by placing an item up for bid you are making a contract between you and the buyer.
Once you place an item, you may not retract it and you must sell it for the highest bid.
In other words, if you don't want to sell it, don't place it up for bid!
EOF

	if ($config{'regdir'}) {
		print <<"EOF";
<P><B><A HREF="$ENV{'SCRIPT_NAME'}?action=reg">Registration</A> is required to post or bid!</B></TD></TR>
<TR><TD VALIGN=TOP><B>Your Handle/Alias:<BR></B>Used to track your post</TD><TD><INPUT NAME=ALIAS TYPE=TEXT SIZE=30 MAXLENGTH=30>
<TR><TD VALIGN=TOP><B>Your Password:<BR></B>Must be valid</TD><TD><INPUT NAME=PASSWORD TYPE=PASSWORD SIZE=30>
<TR><TD VALIGN=TOP><B>Your Starting Bid:</B></TD><TD>\$<INPUT NAME=BID TYPE=TEXT SIZE=7 VALUE=0>
<TR><TD VALIGN=TOP><B>Your Reserve Price:<BR></B>You are not obligated to sell below this price.  Leave blank if none.</TD><TD>\$<INPUT NAME=RESERVE TYPE=TEXT SIZE=7 VALUE=0>
<TR><TD VALIGN=TOP><B>Bid Increment:</B></TD><TD>\$<INPUT NAME=INC TYPE=TEXT SIZE=7 VALUE="$inc"></TD></TR></TABLE>
EOF
	}
	else {
		print <<"EOF";
</TD></TR>
<TR><TD VALIGN=TOP><B>Your Handle/Alias:<BR></B>Used to track your post</TD><TD><INPUT NAME=ALIAS TYPE=TEXT SIZE=30 MAXLENGTH=30>
<TR><TD VALIGN=TOP><B>Your E-Mail Address:<BR></B>Must be valid</TD><TD><INPUT NAME=EMAIL TYPE=TEXT SIZE=30>
<TR><TD VALIGN=TOP><B>Your Starting Bid:</B></TD><TD>\$<INPUT NAME=BID TYPE=TEXT SIZE=7 VALUE=0>
<TR><TD VALIGN=TOP><B>Your Reserve Price:<BR></B>You are not obligated to sell below this price.  Leave blank if none.</TD><TD>\$<INPUT NAME=RESERVE TYPE=TEXT SIZE=7 VALUE=0>
<TR><TD VALIGN=TOP><B>Bid Increment:</B></TD><TD>\$<INPUT NAME=INC TYPE=TEXT SIZE=7 VALUE="$inc">
<TR><TD VALIGN=TOP><B>Contact Information:<BR></B>Will be given out only to the buyer</TD><TD>
<TT>Full Name: </TT><BR><INPUT NAME=ADDRESS1 TYPE=TEXT SIZE=30><BR>
<TT>Street Address: </TT><BR><INPUT NAME=ADDRESS2 TYPE=TEXT SIZE=30><BR>
<TT>City, State, ZIP: </TT><BR><INPUT NAME=ADDRESS3 TYPE=TEXT SIZE=30></TD></TR></TABLE>
EOF
	}
	print <<"EOF";
<CENTER><INPUT TYPE=SUBMIT VALUE=Preview></CENTER>
EOF
}

#-#############################################
# Sub: Process New Item
# This processes new item to be put up for
# sale from a posted form

sub procnew {
	my ($password, @userbids);
	if ($config{'regdir'} ne "") {
		&oops('Your alias could not be found!') unless ($password, $form{'EMAIL'}, $form{'ADDRESS1'}, $form{'ADDRESS2'}, $form{'ADDRESS3'}, @userbids) = &read_reg_file($form{'ALIAS'});
		$form{'ALIAS'} = ucfirst(lc($form{'ALIAS'}));
		&oops('Your password is incorrect.') unless ((lc $password) eq (lc $form{'PASSWORD'}));
	}
	&oops('You must have an item title that is up to 50 characters.') unless ($form{'TITLE'} && (length($form{'TITLE'}) < 51));
        &oops('You must select a valid category.') unless (-d "$config{'basepath'}$form{'CATEGORY'}" and $category{$form{'CATEGORY'}});
	$form{'IMAGE'} = "" if ($form{'IMAGE'} eq "http://");
	&oops('You must enter the number of days your auction should run, from 1 to 14.') unless (($form{'DAYS'} > 0) and ($form{'DAYS'} < 15));
	&oops('You must enter an item description.') unless ($form{'DESC'});
	&oops('You must enter an alias to track your item.') unless ($form{'ALIAS'});
	&oops('You must enter a valid e-mail address.') unless (&check_email($form{'EMAIL'}));
	&oops('You must enter a valid starting bid.') unless ($form{'BID'} =~ /^(\d+\.?\d*|\.\d+)$/);
	&oops('You must enter a valid bid increment.') unless (($form{'INC'} =~ /^(\d+\.?\d*|\.\d+)$/) and ($form{'INC'} >= .01));
	$form{'INC'} = &parsebid($form{'INC'});
	$form{'RESERVE'} = &parsebid($form{'RESERVE'});
	&oops('You must enter your full name.') unless ($form{'ADDRESS1'});
	&oops('You must enter your street address.') unless ($form{'ADDRESS2'});
	&oops('You must enter your city, state, and zip code.') unless ($form{'ADDRESS3'});
	foreach my $key (keys %form) {
		$form{$key} = &strip_html($form{$key});
	}
	my $item_number = ($form{'DAYS'} * 86400 + time);
	$item_number = ($form{'DAYS'} * 86400 + time) until (!(-f "$config{'basepath'}$form{'CATEGORY'}/$item_number.dat"));
	if ($form{'FROMPREVIEW'}) {
		&oops('We are unable to post your item.  This could be a write permissions problem.') unless (open (NEW, ">$config{'basepath'}$form{'CATEGORY'}/$item_number.dat"));
		print NEW "$form{'TITLE'}\n$form{'RESERVE'}\n$form{'INC'}\n$form{'DESC'}\n$form{'IMAGE'}\n$form{'ALIAS'}\[\]$form{'EMAIL'}\[\]".&parsebid($form{'BID'})."\[\]".time."\[\]$form{'ADDRESS1'}\[\]$form{'ADDRESS2'}\[\]$form{'ADDRESS3'}";
		close NEW;
		if ($config{'regdir'} ne "") {
			&oops('We could not open the registration file.  This could be a server write issue.') unless (open(REGFILE, ">>$config{'basepath'}$config{'regdir'}/$form{'ALIAS'}.dat"));
			print REGFILE "\n$form{'CATEGORY'}$item_number";
			close REGFILE;
		}
		print "<B>$form{'TITLE'} was posted under $category{$form{'CATEGORY'}}...</B>.<BR>You may want to go to <A HREF=\"$ENV{'SCRIPT_NAME'}\?category=$form{'CATEGORY'}\&item=$item_number\">the item</A> to confirm placement.\n\n";
	}
	else {
		my $nowtime = localtime(time);
		my $closetime = localtime($item_number);
		my $html_description = &strip_scripts(&enable_html($form{'DESC'}));
		print "<H2>$form{'TITLE'} PREVIEW</H2><HR><FONT SIZE=+1><B>Information</B></FONT><HR>\n";
		print "<TABLE WIDTH=800><TR>";
		print "<TD BGCOLOR=$config{'colortablebody'}><IMG SRC=$form{'IMAGE'}></TD>" if ($form{'IMAGE'});
		print "<TD><TABLE class=maintab><TR><TD BGCOLOR=$config{'colortablehead'}><B>$form{'TITLE'}</B></TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Category:</B> <A HREF=$ENV{'SCRIPT_NAME'}\?category=$form{'CATEGORY'}>$category{$form{'CATEGORY'}}</A></TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Offered By:</B> <A HREF=mailto:$form{'EMAIL'}>$form{'ALIAS'}</A></TR></TD><TR><TD BGCOLOR=$config{'colortablebody'}><B>Current Time:</B> $nowtime</TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Closes:</B> $closetime<BR><FONT SIZE=-2>Or $config{'aftermin'} minutes after last bid...</FONT></TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Number of Bids:</B> 0</TD></TR><TR><TD BGCOLOR=$config{'colortablebody'}><B>Last Bid:</B> \$$form{'BID'}</TD></TR></TABLE></TD></TR></TABLE>\n";
		print "<HR><FONT SIZE=+1><B>Description</B></FONT><HR>$html_description</FONT></FONT></B></I></U></H1></H2></H3></H4></H5>";
		print "<HR><B><FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>If this looks good, hit <INPUT TYPE=SUBMIT VALUE=\"Post Item\">, else hit the back button on your browser to edit the item.<INPUT TYPE=HIDDEN NAME=FROMPREVIEW VALUE=1></B>\n";
		foreach my $key (keys %form) {
			print "<INPUT TYPE=hidden NAME=\"$key\" VALUE=\"$form{$key}\">\n";
		}
		print "</FORM>\n";
	}
}

#-#############################################
# Sub: Process Bid
# This processes new bids from a posted form

sub procbid {
	my ($password, @userbids);
	if ($config{'regdir'} ne "") {
		&oops('Your alias could not be found!') unless ($password, $form{'EMAIL'}, $form{'ADDRESS1'}, $form{'ADDRESS2'}, $form{'ADDRESS3'}, @userbids) = &read_reg_file($form{'ALIAS'});
		$form{'ALIAS'} = ucfirst(lc($form{'ALIAS'}));
		&oops('Your password is incorrect.') unless ((lc $password) eq (lc $form{'PASSWORD'}));
	}
	&oops('You must enter an alias to track your item.') unless ($form{'ALIAS'});
	&oops('You must enter a valid e-mail address.') unless (&check_email($form{'EMAIL'}));
	&oops('You must enter a valid bid amount.') unless ($form{'BID'} =~ /^(\d+\.?\d*|\.\d+)$/);
	$form{'BID'} = &parsebid($form{'BID'});
	&oops('You must enter your full name.') unless ($form{'ADDRESS1'});
	&oops('You must enter your street address.') unless ($form{'ADDRESS2'});
	&oops('You must enter you city, state, and zip.') unless ($form{'ADDRESS3'});
	my ($title, $reserve, $inc, $desc, $image, @bids) = &read_item_file($form{'CATEGORY'},$form{'ITEM'});
	&oops('The item number you entered cannot be found.  Maybe it has closed or it was moved since you last loaded the page.') if $title eq '';
	my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$#bids]);
	if ((time <= $form{'ITEM'}) or (time <= (60 * $config{'aftermin'} + $time))) {
		&oops('Your bid is too low.  Sorry.') if ($form{'BID'} < ($bid+$inc) and ($#bids)) or ($form{'BID'} < $bid);
		&oops('We are unable to append your bid to the auction item.  It appears to be a file write problem.') unless (open NEW, ">>$config{'basepath'}$form{'CATEGORY'}/$form{'ITEM'}.dat");
		if ($config{'flock'}) {
			flock(NEW, 2);
			seek(NEW, 0, 2);
		}
		print NEW "\n$form{'ALIAS'}\[\]$form{'EMAIL'}\[\]$form{'BID'}\[\]".time."\[\]$form{'ADDRESS1'}\[\]$form{'ADDRESS2'}\[\]$form{'ADDRESS3'}";
		close NEW;
		print "<B>$form{'ALIAS'}, your bid has been placed on item number $form{'ITEM'} for \$$form{'BID'} on ".scalar(localtime(time)).".</B><BR>You may want to print this notice as confirmation of your bid.<P>Go <A HREF=\"$ENV{'SCRIPT_NAME'}\?category=$form{'CATEGORY'}\&item=$form{'ITEM'}\">back to the item</A>\n";
		my $flag=0;
		my $userbid;
		foreach $userbid (@userbids) {
			$flag=1 if ("$form{'CATEGORY'}$form{'ITEM'}" eq $userbid);
		}
		if ($flag==0 && $config{'regdir'} ne "") {
			&oops('We could not open the registration file.  This could be a server write issue.') unless (open(REGFILE, ">>$config{'basepath'}$config{'regdir'}/$form{'ALIAS'}.dat"));
			print REGFILE "\n$form{'CATEGORY'}$form{'ITEM'}";
			close REGFILE;
		}
		&sendemail($email, $config{'admin_address'}, 'You\'ve been outbid!', "You have been outbid on $title\!  If you want to place a higher bid, please visit\:\r\n\r\n\thttp://$config{'scripturl'}$ENV{'SCRIPT_NAME'}\?category=$form{'CATEGORY'}\&item=$form{'ITEM'}\r\n\r\nThe current high bid is \$$form{'BID'}.") if ($config{'scripturl'} and $#bids);
	}
	else {
		print "Item number $form{'ITEM'} in category $form{'CATEGORY'} is now closed!<BR>Sorry...\n";
	}
}

#-#############################################
# Sub: Process Search
# This displays search results

sub procsearch {
	print "<H2>Search Results - $form{'searchstring'}</H2>\n";
	print "<TABLE class=maintab WIDTH=800>\n";
	print "<TR><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Item</B></TD><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Closes</B></TD><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>Num Bids</B></TD><TD ALIGN=CENTER BGCOLOR=$config{'colortablehead'}><B>High Bid</B></TD></TR>\n";
	my $key;
	foreach $key (sort keys %category) {
		opendir THEDIR, "$config{'basepath'}$key" or &oops("Category directory $key could not be opened.");
		my @allfiles = grep -T, map "$config{'basepath'}$key/$_", sort { int($a) <=> int($b) } (readdir THEDIR);
		closedir THEDIR;
		my $file;
		foreach $file (@allfiles) {
			$file =~ s/^$config{'basepath'}$key\///;
			$file =~ s/\.dat$//;
			my ($title, $reserve, $inc, $desc, $image, @bids) = &read_item_file($key,$file);
			if ($title ne '') {
				my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$#bids]);
				my @closetime = localtime($file);
				$closetime[4]++;
				if($form{'searchtype'} eq 'keyword' and ($title =~ /$form{'searchstring'}/i) || ($desc =~ /$form{'searchstring'}/i)) {
					print "<TR><TD BGCOLOR=$config{'colortablebody'}><A HREF=$ENV{'SCRIPT_NAME'}\?category=$key\&item=$file>$title</A>";
					print " <FONT COLOR=#3333FF SIZE=-1>[PIC]</FONT>" if ($image);
					print "</TD><TD BGCOLOR=$config{'colortablebody'}>$closetime[4]/$closetime[3]</TD><TD BGCOLOR=$config{'colortablebody'}>$#bids</TD><TD BGCOLOR=$config{'colortablebody'}>\$$bid</TD></TR>\n";
				}
				elsif($form{'searchtype'} eq 'username' and join(' ',@bids) =~ /$form{'searchstring'}/i) {
					print "<TR><TD BGCOLOR=$config{'colortablebody'}><A HREF=$ENV{'SCRIPT_NAME'}\?category=$key\&item=$file>$title</A>";
					print " <FONT COLOR=#3333FF SIZE=-1>[PIC]</FONT>" if ($image);
					print "</TD><TD BGCOLOR=$config{'colortablebody'}>$closetime[4]/$closetime[3]</TD><TD BGCOLOR=$config{'colortablebody'}>$#bids</TD><TD BGCOLOR=$config{'colortablebody'}>\$$bid</TD></TR>\n";
				}
			}
		}
	}
	print "</TABLE>\n";
}

#-#############################################
# Sub: Change Registration
# This allows a user to change information

sub creg {
	print <<"EOF";
<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>
<H2>Change Street Address and/or Password</H2>
<TABLE WIDTH=800 class=maintab BGCOLOR=$config{'colortablebody'}>
<INPUT TYPE=HIDDEN NAME=action VALUE=proccreg>
<TR><TD COLSPAN=2 VALIGN=TOP> This form will allow you to change your
street address and/or password.
</TD></TR>
<TR><TD VALIGN=TOP><B>Your Handle/Alias:<BR></B>Required for verification</TD><TD><INPUT NAME=ALIAS TYPE=TEXT SIZE=30 MAXLENGTH=30>
<TR><TD VALIGN=TOP><B>Your Current Password:<BR></B>Required for verification</TD><TD><INPUT NAME=OLDPASS TYPE=PASSWORD SIZE=30>
<TR><TD VALIGN=TOP><B>Your New Password:<BR></B>Leave blank if unchanged</TD><TD><INPUT NAME=NEWPASS1 TYPE=PASSWORD SIZE=30>
<TR><TD VALIGN=TOP><B>Your New Password Again:<BR></B>Leave blank if unchanged</TD><TD><INPUT NAME=NEWPASS2 TYPE=PASSWORD SIZE=30>
<TR><TD VALIGN=TOP><B>Contact Information:<BR></B>Leave blank if unchanged</TD><TD>
<TT>Full Name: </TT><BR><INPUT NAME=ADDRESS1 TYPE=TEXT SIZE=30><BR>
<TT>Street Address: </TT><BR><INPUT NAME=ADDRESS2 TYPE=TEXT SIZE=30><BR>
<TT>City, State, ZIP: </TT><BR><INPUT NAME=ADDRESS3 TYPE=TEXT SIZE=30></TD></TR></TABLE>
<CENTER><INPUT TYPE=SUBMIT VALUE="Change Registration"></CENTER>
EOF
}

#-#############################################
# Sub: Process Changed Registration
# This modifies an account

sub proccreg {
	if ($config{'regdir'}) {
		&oops('You must enter your alias so we can validate your account.') unless ($form{'ALIAS'});
		&oops('You must enter your old password so we can validate your account.') unless ($form{'OLDPASS'});
		if ($form{'ADDRESS1'}) {
			&oops('You must enter all of your contact information.  Please enter your street address.') unless ($form{'ADDRESS2'});
			&oops('You must enter all of your contact information.  Please enter your city, state, and zip.') unless ($form{'ADDRESS3'});
		}
		if ($form{'NEWPASS1'}) {
			&oops('Your new passwords do not match.') unless ($form{'NEWPASS2'} eq $form{'NEWPASS1'});
		}
		if (my ($password,$email,$add1,$add2,$add3,@past_bids) = &read_reg_file($form{'ALIAS'})) {
			$form{'ALIAS'} = ucfirst(lc($form{'ALIAS'}));
			&oops('Your old password does not match up.') unless ((lc $password) eq (lc $form{'OLDPASS'}));
			$form{'NEWPASS1'} = $password if !($form{'NEWPASS1'});
			$form{'ADDRESS1'} = $add1 if !($form{'ADDRESS1'});
			$form{'ADDRESS2'} = $add2 if !($form{'ADDRESS2'});
			$form{'ADDRESS3'} = $add3 if !($form{'ADDRESS3'});
			&oops('We cannot open your account.  This could be a server data write issue.') unless (open NEWREG, ">$config{'basepath'}$config{'regdir'}/$form{'ALIAS'}.dat");
			print NEWREG "$form{'NEWPASS1'}\n$email\n$form{'ADDRESS1'}\n$form{'ADDRESS2'}\n$form{'ADDRESS3'}";
			my $bid;
			foreach $bid (@past_bids) {
				print NEWREG "\n$bid";
			}
			close NEWREG;
			print "$form{'ALIAS'}, your information has been successfully changed.\n";
		}
		else {
			print "Sorry...  That Username is not valid.  If you do not have an alias (or cannot remember it) you should create a <A HREF=$ENV{'SCRIPT_NAME'}?action=reg>new account</A>.\n";
		}
	}
	else {
		print "User Registration is Not Implemented on This Server!  The System Administrator Did Not Specify a Registration Directory...\n";
	}
}	

#-#############################################
# Sub: New Registration
# This creates a form for registration

sub reg {
	print <<"EOF";
<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>
<H2>New User Registration</H2>
<TABLE WIDTH=800 class=maintab BGCOLOR=$config{'colortablebody'}>
<INPUT TYPE=HIDDEN NAME=action VALUE=procreg>
<TR><TD COLSPAN=2 VALIGN=TOP>This form will allow you to register to buy or sell
auction items.  You must enter accurate data, and your new password will be e-mailed
to you.  Please be patient after hitting the submit button.  Registration may take
a few seconds.</TD></TR>
<TR><TD VALIGN=TOP><B>Your Handle/Alias:<BR></B>Used to track your post</TD><TD><INPUT NAME=ALIAS TYPE=TEXT SIZE=30 MAXLENGTH=30>
<TR><TD VALIGN=TOP><B>Your E-Mail Address:<BR></B>Must be valid</TD><TD><INPUT NAME=EMAIL TYPE=TEXT SIZE=30>
<TR><TD VALIGN=TOP><B>Contact Information:<BR></B>Will be given out only to the buyer or seller</TD><TD>
<TT>Full Name: </TT><BR><INPUT NAME=ADDRESS1 TYPE=TEXT SIZE=30><BR>
<TT>Street Address: </TT><BR><INPUT NAME=ADDRESS2 TYPE=TEXT SIZE=30><BR>
<TT>City, State, ZIP: </TT><BR><INPUT NAME=ADDRESS3 TYPE=TEXT SIZE=30></TD></TR></TABLE>
<CENTER><INPUT TYPE=SUBMIT VALUE="Register Me"></CENTER>
EOF
}

#-#############################################
# Sub: Process Registration
# This adds new accounts to the database

sub procreg {
	if ($config{'regdir'}) {
		umask(000);  # UNIX file permission junk
		mkdir("$config{'basepath'}$config{'regdir'}", 0777) unless (-d "$config{'basepath'}$config{'regdir'}");		
		&oops('You must enter an alias that consists of alphanumeric characters.') if $form{'ALIAS'} =~ /\W/ or !($form{'ALIAS'});
		&oops('You must enter a valid e-mail address.') unless ($form{'EMAIL'} =~ /^.+\@.+\..+$/);
		&oops('You must enter your full name so buyers or sellers may contact you.') unless ($form{'ADDRESS1'});
		&oops('You must enter a valid street address so buyers or sellers can contact you.') unless ($form{'ADDRESS2'});
		&oops('You must enter a valid city, state, and zip code so buyers or sellers can contact you.') unless ($form{'ADDRESS3'});
		$form{'ALIAS'} = ucfirst(lc($form{'ALIAS'}));
		if (!(-f "$config{'basepath'}$config{'regdir'}/$form{'ALIAS'}.dat")) {
			&oops('We were unable to write to the user directory.') unless (open NEWREG, ">$config{'basepath'}$config{'regdir'}/$form{'ALIAS'}.dat");
			my $newpass = &randompass; 
			print NEWREG "$newpass\n$form{'EMAIL'}\n$form{'ADDRESS1'}\n$form{'ADDRESS2'}\n$form{'ADDRESS3'}";
			close NEWREG;
			print "$form{'ALIAS'}, you should receive an e-mail to $form{'EMAIL'} in a few minutes.  It will contain your password needed to post or bid.  You may change your password once you receive it.  If you do not get an e-mail, please re-register.\n";
			&sendemail($form{'EMAIL'}, $config{'admin_address'}, 'Auction Password', "PLEASE DO NOT REPLY TO THIS E-MAIL.\r\n\r\nThank you for registering to use the online auctions at $config{'sitename'}!\r\n\r\nYour new password is: $newpass\r\nYour alias (as you entered it) is: $form{'ALIAS'}\r\n\r\nThank you for visiting!");
		}
		else {
			print "Sorry...  that alias is taken.  Hit back to try again!\n";
		}
	}
	else {
		print "User Registration is Not Implemented on This Server!  The System Administrator Did Not Specify a Registration Directory...\n";
	}
}	

#-#############################################
# Sub: Closed items 1
# This displays closed items

sub viewclosed1 {
	print <<"EOF";
<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>
<H2>View Closed Items</H2>
<TABLE WIDTH=800 class=maintab BGCOLOR=$config{'colortablebody'}>
<INPUT TYPE=HIDDEN NAME=action VALUE=closed2>
<TR><TD COLSPAN=2 VALIGN=TOP> This form will allow you to view the
status and contact information for closed auction items you bid on or listed for auction.
</TD></TR>
<TR><TD VALIGN=TOP><B>Your Username:<BR></B>Required for verification</TD><TD><INPUT NAME=ALIAS TYPE=TEXT SIZE=30 MAXLENGTH=30>
<TR><TD VALIGN=TOP><B>Your Password:<BR></B>Required for verification</TD><TD><INPUT NAME=PASSWORD TYPE=PASSWORD SIZE=30>
</TD></TR></TABLE>
<CENTER><INPUT TYPE=SUBMIT VALUE="View Closed Items"></CENTER>
EOF
}

#-#############################################
# Sub: Closed items 2
# This displays closed items

sub viewclosed2 {
	&oops('Your alias could not be found!') unless my ($password,$email,$add1,$add2,$add3,@past_bids) = &read_reg_file($form{'ALIAS'});
	&oops('Your password is incorrect.') unless ((lc $password) eq (lc $form{'PASSWORD'}));
	&oops('PASSWORD') unless ((lc $password) eq (lc $form{'PASSWORD'}));
	print "<FORM METHOD=POST ACTION=\"$ENV{'SCRIPT_NAME'}\">\n";
	print "<INPUT TYPE=HIDDEN NAME=action VALUE=closed3><INPUT TYPE=HIDDEN NAME=ALIAS VALUE=\"$form{'ALIAS'}\"><SELECT NAME=BIDTOVIEW>\n";
	my $bid;
	foreach $bid (@past_bids) {
		if (-T "$config{'basepath'}$config{'closedir'}/$bid.dat") {
			open THEFILE, "$config{'basepath'}$config{'closedir'}/$bid.dat";
			my ($title, $reserve, $inc, $desc, $image, @bids) = <THEFILE>;
			close THEFILE;
			chomp($title, $reserve, $inc, $desc, $image, @bids);
			print "<OPTION VALUE=\"$bid\">$bid: $title</OPTION>\n";
		}
	}
	print "</SELECT><BR><INPUT TYPE=SUBMIT VALUE=\"View My Status\"></FORM>\n";
}

#-#############################################
# Sub: Closed items 3
# This displays closed items

sub viewclosed3 {
	$form{'BIDTOVIEW'} =~ s/\W//g;
	open (THEFILE, "$config{'basepath'}$config{'closedir'}/$form{'BIDTOVIEW'}.dat") or &oops('We cannot open the item you are looking for.  This could be a server read issue.');
	my ($title, $reserve, $inc, $desc, $image, @bids) = <THEFILE>;
	close THEFILE;
	chomp($title, $reserve, $inc, $desc, $image, @bids);
	my $html_description = &strip_scripts(&enable_html($desc));
	print "<H2>$title</H2>\n";
	print "<HR><FONT SIZE=+1><B>Description</B></FONT><HR>$html_description</FONT></FONT></B></I></U></H1></H2></H3></H4></H5>";
	print "<HR><FONT SIZE=+1><B>Bid History</B></FONT><HR>\n";
	if ($#bids) {
		for (my $i=1; $i<scalar(@bids); $i++) {
			my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$i]);
			my $bidtime = localtime($time);
			print "<FONT SIZE=-1>$alias \($bidtime\) - \$$bid</FONT><BR>";
		}
	}
	else {
		print "<FONT SIZE=-1>No bids were placed...</FONT><BR>";
	}
	print "<P>Reserve was: \$$reserve<BR>\n";
	print "<HR><FONT SIZE=+1><B>Contact Information</B></FONT><HR>\n";
	if (ucfirst(lc($form{'ALIAS'})) eq (&read_bid($bids[0]))[0]) {
		print "You were the seller...<P>\n";
		if ($#bids) {
			my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$#bids]);
			print "<B>Buyer Information:</B><BR><I>Alias</I>: $alias<BR><I>E-Mail</I>: $email<BR><I>Address</I>: ".&strip_html($add1)."<BR>".&strip_html($add2)."<BR>".&strip_html($add3)."<P><B>High Bid:</B> \$$bid\n";
			print "<P><B>Bidder Contact Info:</B><BR>\n";
			for (my $i=1; $i<scalar(@bids); $i++) {
				my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$i]);
				print "<FONT SIZE=-1>$alias - <A HREF=\"mailto:$email\">$email</A></FONT><BR>\n";
			}
		}
                print "<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>You may repost this item if you want to: <INPUT TYPE=SUBMIT VALUE=\"Repost\"><INPUT TYPE=HIDDEN NAME=action VALUE=\"repost\"><INPUT TYPE=HIDDEN NAME=REPOST VALUE=\"$form{'BIDTOVIEW'}\"></FORM>\n";
	}
	elsif (ucfirst(lc($form{'ALIAS'})) eq (&read_bid($bids[$#bids]))[0]) {
		print "You were a high bidder...<P>\n";
		my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[0]);
		print "<B>Seller Information:</B><BR><I>Alias</I>: $alias<BR><I>E-Mail</I>: $email<BR><I>Address</I>: ".&strip_html($add1)."<BR>".&strip_html($add2)."<BR>".&strip_html($add3)."<P>";
		my ($alias, $email, $bid, $time, $add1, $add2, $add3) = &read_bid($bids[$#bids]);
		print "<B>Your High Bid:</B> \$$bid<P>\n";
		print "<I>Remember, the seller is not required to sell unless your bid price was above the reserve price...</I>";
	}
	else {
		print "You were not a winner...  No further contact information is available.\n";
	}
}

#-#############################################
# Sub: Admin
# Allows the administrator to delete items.

sub admin {
	print <<"EOF";
<FORM ACTION=$ENV{'SCRIPT_NAME'} METHOD=POST>
<H2>Delete Items</H2>
<TABLE WIDTH=800 class=maintab BGCOLOR=$config{'colortablebody'}>
<INPUT TYPE=HIDDEN NAME=action VALUE=procadmin>
<TR><TD COLSPAN=2 VALIGN=TOP> This form will allow you to delete an item.  You will need the
administrator password that should be configured in the script.
</TD></TR>
<TR><TD VALIGN=TOP><B>Category:<BR></B>Select One</TD><TD><SELECT NAME=CATEGORY>
<OPTION SELECTED></OPTION>
EOF
	my $key;
	foreach $key (sort keys %category) {
		print "<OPTION VALUE=\"$key\">$category{$key}</OPTION>\n";
	}
	print <<"EOF";
</SELECT></TD></TR>
<TR><TD VALIGN=TOP><B>Item Number:<BR></B></TD><TD><INPUT NAME=ITEM TYPE=TEXT SIZE=30 MAXLENGTH=30>
<TR><TD VALIGN=TOP><B>Administrator Password:<BR></B>Required for verification</TD><TD><INPUT NAME=PASSWORD TYPE=PASSWORD SIZE=30>
</TD></TR></TABLE>
<CENTER><INPUT TYPE=SUBMIT VALUE="Delete Item"></CENTER>
EOF
}

#-#############################################
# Sub: Process Admin
# Allows the administrator to delete items.

sub procadmin {
	if (lc($form{'PASSWORD'}) eq lc($config{'adminpass'})) {
		&oops('Bad Item Category or Number!') unless &read_item_file($form{'CATEGORY'},$form{'ITEM'});
		if (unlink("$config{'basepath'}$form{'CATEGORY'}/$form{'ITEM'}.dat")) {
			print "File Successfully Removed!\n";
		}
		else {
			print "File Could Not Be Removed!\n";
		}
	}
	else {
		print "Sorry...  Incorrect administrator password for delete!\n";
	}
}

#-#############################################
# Sub: Close Auction
# This sets an item's status to closed.

sub closeit {
	my ($cat,$item) = @_;
	if ($cat ne $config{'closedir'}) {
		my ($title, $reserve, $inc, $desc, $image, @bids) = &read_item_file($cat,$item);
		my @lastbid = &read_bid($bids[$#bids]);
		my @firstbid =  &read_bid($bids[0]);
		if ($#bids) {
			if ($lastbid[2] >= $reserve) {
				&sendemail($lastbid[1], $firstbid[1], "Auction Close: ".&enable_html($title), "Congratulations!  You are the winner of auction number $item.\r\nYour winning bid was \$$lastbid[2].\r\n\r\nPlease contact the seller to make arrangements for payment and shipping:\r\n\r\n$firstbid[4]\r\n$firstbid[5]\r\n$firstbid[6]\r\n$firstbid[1]\r\n\r\nThanks for using $config{'sitename'}!");
			}
			else {
				&sendemail($lastbid[1], $firstbid[1], "Auction Close: ".&enable_html($title), "Congratulations!  You were the high bidder on auction number $item.\r\nYour bid was \$$lastbid[2].\r\n\r\nUnfortunately, your bid did not meet the seller\'s reserve price...\r\n\r\nYou may still wish to contact the seller to negotiate a fair price:\r\n\r\n$firstbid[4]\r\n$firstbid[5]\r\n$firstbid[6]\r\n$firstbid[1]\r\n\r\nThanks for using $config{'sitename'}!");
			}
			&sendemail($firstbid[1], $lastbid[1], "Auction Close: ".&enable_html($title), "Auction number $item is now closed.\r\nThe high bid was \$$lastbid[2] (your reserve was: \$$reserve).\r\n\r\nPlease contact the high bidder to make any necessary arrangements:\r\n\r\n$lastbid[4]\r\n$lastbid[5]\r\n$lastbid[6]\r\n$lastbid[1]\r\n\r\nThanks for using $config{'sitename'}!");
		}
		else {
			&sendemail($firstbid[1], $config{'admin_address'}, "Auction Close: ".&enable_html($title), "Auction number $item is now closed.\r\nThere were no bids on your item.  You may repost your item by using the closed auction manager at http://$config{'scripturl'}$ENV{'SCRIPT_NAME'}.  Thanks for using $config{'sitename'}!");
		}
		if ($config{'closedir'}) {
			umask(000);  # UNIX file permission junk
			mkdir("$config{'basepath'}$config{'closedir'}", 0777) unless (-d "$config{'basepath'}$config{'closedir'}");		
			print "Please notify the site admin that this item cannot be copied to the closed directory even though it is closed.\n" unless &movefile("$config{'basepath'}$cat/$item.dat", "$config{'basepath'}$config{'closedir'}/$cat$item.dat");		
		}
		else {
			print "Please notify the site admin that this item cannot be removed even though it is closed.\n" unless unlink("$config{'basepath'}$cat/$item.dat");
		}
	}
}

#-#############################################
# SUB: Send E-mail
# This is a real quick-and-dirty mailer that
# should work on any platform.  It is my first
# attempt to work with sockets, so if anyone
# has any suggestions, let me know!
#
# Takes:
# (To, Subject, From, Message)

sub sendemail {
        my ($to,$from,$subject,$message) = @_;
        my $trash;
        if ($config{'mailhost'}) {
		eval('use IO::Socket; 1;') or &oops("IO::Socket could not be loaded by the script.  Please see the script documentation for details.  It looks like this server is using perl version $].  IO::Socket may not be included with versions of perl prior to 5.00404."); # don't cause errors on machines where IO::Socket is not available
                my $remote;
                $remote = IO::Socket::INET->new("$config{'mailhost'}:smtp(25)");
                $remote->autoflush();
                print $remote "HELO\r\n";
                $trash = <$remote>;
                print $remote "MAIL From:<$config{'admin_address'}>\r\n";
                $trash = <$remote>;
                print $remote "RCPT To:<$to>\r\n";
                $trash = <$remote>;
                print $remote "DATA\r\n";
                $trash = <$remote>;
                print $remote "From: <$from>\r\nSubject: $subject\r\n\r\n";
                print $remote $message;
                print $remote "\r\n.\r\n";
                $trash = <$remote>;
                print $remote "QUIT\r\n";
        }
        else {
                open MAIL, "|$config{'mailprog'}";
                print MAIL "To: $to\r\nFrom: $from\r\nSubject: $subject\r\n\r\n$message\r\n\r\n";
                close MAIL;
        }
}

#-#############################################
# Sub: Get Form Data
# This gets data from a post.

sub get_form_data {
        my $temp;
        my $buffer;
        my @data;
        read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});
        foreach $temp (split(/&|=/,$buffer)) {
                $temp =~ tr/+/ /;
                $temp =~ s/%([0-9a-fA-F]{2})/pack("c",hex($1))/ge;
		$temp =~ s/[\r\n]/ /g;
                push @data, $temp;
        }
        foreach $temp (split(/&|=/,$ENV{'QUERY_STRING'})) {
                $temp =~ tr/+/ /;
                $temp =~ s/%([0-9a-fA-F]{2})/pack("c",hex($1))/ge;
		$temp =~ s/[\r\n]/ /g;
                push @data, $temp;
        }
        return @data;
}

#-#############################################
# Sub: Random Password
# This generates psudo-random 8-letter
# passwords

sub randompass {
	srand(time ^ $$);
	my @passset = ('a'..'k', 'm'..'n', 'p'..'z', '2'..'9');
	my $randpass = "";
	for (my $i=0; $i<8; $i++) {
		$randpass .= $passset[int(rand($#passset + 1))];
	}
	return $randpass;
}

#-#############################################
# Sub: parse bid
# This formats a bid amount to look good...
# ie. $###.##

sub parsebid {
	$_[0] =~ s/\,//g; 
	my @bidamt = split(/\./, $_[0]);
	$bidamt[0] = "0" if (!($bidamt[0]));
	$bidamt[0] = int($bidamt[0]);
	$bidamt[1] = substr($bidamt[1], 0, 2);
	$bidamt[1] = "00" if (length($bidamt[1]) == 0);
	$bidamt[1] = "$bidamt[1]0" if (length($bidamt[1]) == 1);
	return "$bidamt[0].$bidamt[1]";
}

#-#############################################
# Sub: Oops!
# This generates an error message and dies.

sub oops {
	print "<P><HR SIZE=1 NOSHADE><FONT COLOR=#FF0000><B>Error:</B></FONT><BR>$_[0]<P>Please hit the back browser on your browser to try again or contact <A HREF=\"mailto:$config{'admin_address'}\">the auction administrator</A> if you belive this to be a server problem.<HR SIZE=1 NOSHADE>\n";
	print $config{'footer'};
	die "Error: $_[0]\n";
}

#-#############################################
# Sub: Movefile(file1, file2)
# This moves a file.  Quick and dirty!

sub movefile {
	my ($firstfile, $secondfile) = @_;
	return 0 unless open(FIRSTFILE,$firstfile);
	my @lines=<FIRSTFILE>;
	close FIRSTFILE;
	return 0 unless open(SECONDFILE,">$secondfile");
	my $line;
	foreach $line (@lines) {
		print SECONDFILE $line;
	}
	close SECONDFILE;
	return 0 unless unlink($firstfile);
	return 1;
}

#-#############################################
# Sub: Read Reg File (alias)
# Reads a registration file

sub read_reg_file {
	my $alias = shift;
	return '' unless $alias;
	# verify the user exists
	&oops('Your alias may not contain any non-word characters.') if $alias =~ /\W/;
	$alias = ucfirst(lc($alias));
	return '' unless -r "$config{'basepath'}$config{'regdir'}/$alias.dat" and -T "$config{'basepath'}$config{'regdir'}/$alias.dat";
	open FILE, "$config{'basepath'}$config{'regdir'}/$alias.dat";
	my ($password,$email,$add1,$add2,$add3,@past_bids) = <FILE>;
	close FILE;
	chomp ($password,$email,$add1,$add2,$add3,@past_bids);
	return ($password,$email,$add1,$add2,$add3,@past_bids);
}

#-#############################################
# Sub: Read Item File (cat, item)
# Reads an item file

sub read_item_file {
	my ($cat, $item) = @_;
	# verify the category exists
	return '' unless ($cat) and ($item);
	&oops('The category may not contain any non-word characters.') if $cat =~ /\W/;
	return '' unless $category{$cat};
	# verify the item exists
	&oops('The item number may not contain any non-numeric characters.') if $item =~ /\D/;
	return '' unless (-T "$config{'basepath'}$cat/$item.dat") and (-R "$config{'basepath'}$cat/$item.dat");
	open FILE, "$config{'basepath'}$cat/$item.dat";
	my ($title, $reserve, $inc, $desc, $image, @bids) = <FILE>;
	close FILE;
	chomp ($title, $reserve, $inc, $desc, $image, @bids);
	return ($title, $reserve, $inc, $desc, $image, @bids);
}

#-#############################################
# Sub: Read Bid Information (bid_string)
# Reads an item file

sub read_bid {
	my $bid_string = shift;
	my ($alias, $email, $bid, $time, $add1, $add2, $add3) = split(/\[\]/,$bid_string);
	return ($alias, $email, $bid, $time, $add1, $add2, $add3);
}

#-#############################################
# Sub: Strip Scripts
# Strips client-side script tags from HTML

sub strip_scripts {
  my $line = shift;
  $line =~ s/(<[\s\/]*)(script\b[^>]*>)/$1x$2/gi;
  while ($line =~ s/(<[^>]*?)\b(on\w+\s*=)/$1x$2/gi) {}
  return $line;
}

#-#############################################
# Sub: Strip HTML
# Strips HTML from text

sub strip_html {
  my $line = shift;
  $line =~ s/&/&amp;/g;
  $line =~ s/"/&quot;/g;
  $line =~ s/</&lt;/g;
  $line =~ s/>/&gt;/g;
  return $line;
}

#-#############################################
# Sub: Enable HTML
# Re-enables Stripped HTML

sub enable_html {
  my $line = shift;
  $line =~ s/&quot;/"/gi;
  $line =~ s/&lt;/</gi;
  $line =~ s/&gt;/>/gi;
  $line =~ s/&amp;/&/gi;
  return $line;
}

#-#############################################
# Sub: Check E-mail
# Checks for valid e-mail address

sub check_email {
  my $mail = shift;
  #characters allowed on name: 0-9a-Z-._ on host: 0-9a-Z-. on between: @
  return 0 if ( $mail !~ /^[0-9a-zA-Z\.\-\_]+\@[0-9a-zA-Z\.\-]+$/ );
  #must start or end with alpha or num
  return 0 if ( $mail =~ /^[^0-9a-zA-Z]|[^0-9a-zA-Z]$/);
  #name must end with alpha or num
  return 0 if ( $mail !~ /([0-9a-zA-Z]{1})\@./ );
  #host must start with alpha or num
  return 0 if ( $mail !~ /.\@([0-9a-zA-Z]{1})/ );
  #pair .- or -. or -- or .. not allowed
  return 0 if ( $mail =~ /.\.\-.|.\-\..|.\.\..|.\-\-./g );
  #pair ._ or -_ or _. or _- or __ not allowed
  return 0 if ( $mail =~ /.\.\_.|.\-\_.|.\_\..|.\_\-.|.\_\_./g );
  #host must end with '.' plus 2-4 alpha characters (may need to be modified for new TLDs)
  return 0 if ( $mail !~ /\.([a-zA-Z]{2,4})$/ );
  return 1;
}
