<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/formElementBuilder.css" rel="stylesheet" type="text/css" />
<title>Form Element Builder</title>
</head>
<body>

<form id="formBuilder" method="" action="">
    <fieldset id="initial">
        <label for="type">Type of form element</label>
        <select id="type" name="type">
            <option value="choose" selected="selected">Choose Element Type:</option>
            <option value="text">Text Input</option>
            <option value="checkbox">Checkbox Set</option>
            <option value="radio">Radio Set</option>
            <option value="select">Select/Dropdown</option>
            <option value="textarea">Text Area</option>
            <option value="submit">Submit (Input)</option>
            <!--<option value="button">Button</option>-->
        </select>
    </fieldset>

    <fieldset id="formAssembly">
        
    </fieldset>

    <fieldset id="markup">
        
    </fieldset>
</form>


<script src="http://ajax.microsoft.com/ajax/jQuery/jquery-1.4.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/formElementBuilder.js"></script>
<script type="text/javascript" src="js/serializeJSON.js"></script>
</body>
</html>
