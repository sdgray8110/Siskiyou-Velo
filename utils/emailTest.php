<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Email Test</title>
<link href="css/emailTest.css" type="text/css" rel="stylesheet" />
</head>
<body>

<form id="emailTester" method="post" action="includes/sendEmailTest.php">
    <label for="emailAddresses">Email Addresses:</label>
    <input type="text" name="emailAddresses" id="emailAddresses"/>
    <label for="subject">Subject:</label>
    <input type="text" name="subject" id="subject" />
    <label for="htmlContent">Paste HTML Here:</label>
    <textarea id="htmlContent" name="htmlContent"></textarea>
    <input type="submit" value="Send Email" class="submit" />
    <input type="submit" value="Reset Form" class="submit" id="resetForm">
</form>


<script src="http://ajax.microsoft.com/ajax/jQuery/jquery-1.4.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/emailTest.js"></script>
</body>
</html>
