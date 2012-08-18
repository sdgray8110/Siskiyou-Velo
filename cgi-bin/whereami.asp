<%
'#################################################################################
'## Copyright (C) 2000-02 Michael Anderson, Pierre Gorissen,
'##                       Huw Reddick and Richard Kinser
'##
'## This program is free software; you can redistribute it and/or
'## modify it under the terms of the GNU General Public License
'## as published by the Free Software Foundation; either version 2
'## of the License, or any later version.
'##
'## All copyright notices regarding Snitz Forums 2000
'## must remain intact in the scripts and in the outputted HTML
'## The "powered by" text/logo with a link back to
'## http://forum.snitz.com in the footer of the pages MUST
'## remain visible when the pages are viewed on the internet or intranet.
'##
'## This program is distributed in the hope that it will be useful,
'## but WITHOUT ANY WARRANTY; without even the implied warranty of
'## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
'## GNU General Public License for more details.
'##
'## You should have received a copy of the GNU General Public License
'## along with this program; if not, write to the Free Software
'## Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
'##
'## Support can be obtained from support forums at:
'## http://forum.snitz.com
'##
'## Correspondence and Marketing Questions can be sent to:
'## reinhold@bigfoot.com
'##
'## or
'##
'## Snitz Communications
'## C/O: Michael Anderson
'## PO Box 200
'## Harpswell, ME 04079
'#################################################################################

dim strPath, strCustomDatabaseName
strCustomDatabaseName = Request.QueryString("dbname")
if strCustomDatabaseName <> "" then
	strPath = Replace(Request.ServerVariables("PATH_TRANSLATED"), "whereami.asp",strCustomDatabaseName)
else
	strPath = Replace(Request.ServerVariables("PATH_TRANSLATED"), "whereami.asp","snitz_forums_2000.mdb")
end if
Response.Write	"<html>" & vbNewLine & vbNewLine & _
		"<head>" & vbNewLine & _
		"<title>Where Am I?</title>" & vbNewLine & _
		"</head>" & vbNewLine & vbNewLine & _
		"<body>" & vbNewLine & vbNewLine & _
		"<table border=""0"" cellspacing=""0"" cellpadding=""5"" width=""50%"" height=""50%"" align=""center"">" & vbNewLine & _
		"  <tr>" & vbNewLine & _
		"    <td bgColor=""#9FAFDF"" align=""center"">" & vbNewLine & _
		"    <font face=""Verdana,Arial,Helvetica"" size=""2"">" & vbNewLine & _
		"    <p><strong><font size=""4"">Where Am I?</font></strong></p>" & vbNewLine & _
		"    <p></p>" & vbNewLine
if strCustomDatabaseName = "" then Response.Write("    <p><b>snitz_forums_2000.mdb</b> is used as an example database name.<br />If you have changed the name of your database, you'll need to change it in the <b>strConnString</b> example shown below, as well.</p>" & vbNewLine)
Response.Write	"    <p>Physical Path to Database: <b>" & strPath & "</b></p>" & vbNewLine & _
		"    <p>Example <b>strConnString</b>:</p>" & vbNewLine & _
		"    <p>This one will work with either Access97, Access2000 or Access2002:</p>" & vbNewLine & _
		"    <nobr><font color=""brown""><p>strConnString = ""Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" & strPath & """ '## MS Access 2000</p></font></nobr>" & vbNewLine & _
		"    <br /><p>Use the following only if you can't get the first one to work<br />One reason could be that your host doesn't have the MSJet Drivers installed:</p>" & vbNewLine & _
		"    <nobr><font color=""brown""><p>strConnString = ""DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=" & strPath & """ '## MS Access 97</p></font></nobr>" & vbNweLine & _
		"    <p></p>" & vbNewLine & _
		"    </font>" & vbNewLine & _
		"    </td>" & vbNewLine & _
		"  </tr>" & vbNewLine & _
		"</table>" & vbNewLine & vbNewLine & _
                "</body>" & vbNewLine & vbNewLine & _
		"</html>" & vbNewLine
%>