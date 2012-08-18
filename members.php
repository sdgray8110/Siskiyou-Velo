<?php require_once("login/auth.php"); ?>
<?php include("includes/header.php"); ?>
Members Directory</title>
<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.old.php"); ?>


<!------- BEGIN MAIN BODY ------->
<div id="leftContent">
<h1>Siskiyou Velo Members Directory</h1>

<input type="text" id="memberSearch" name="memberSearch" class="memberSearch" placeholder="Search for members by name" />
<ul id="memberList"></ul>
    
</div>

<!-------- END MAIN BODY -------->

<script src="/includes/js/lib/jquery.template.min.js" type="text/javascript"></script>
<script src="/includes/js/members.js" type="text/javascript"></script>
<script id="memberData" type="text/x-jquery-tmpl">
    <li>
        <h2>${name}</h2>

        {{if DisplayAddress == 1 && address}}
            {{html googleMaps }}
            <p>${address}</p>
            <p>${city} ${state}, ${zip}</p>
        {{/if}}

        {{if DisplayContact == 3}}
            <p><a href="mailto:${email1}">${email1}</a></p>
            {{if email2}}
                <p><a href="mailto:${email2}">${email2}</a></p>
            {{/if}}
            <p>${phone}</p>
        {{/if}}

        {{if DisplayContact == 2}}
            <p>${phone}</p>
        {{/if}}

        {{if DisplayContact == 1}}
            <p><a href="mailto:${email1}">${email1}</a></p>
            {{if email2}}
                <p><a href="mailto:${email2}">${email2}</a></p>
            {{/if}}
        {{/if}}
    </li>
</script>

<?php include("includes/generic_feed.html"); ?>
<?php include("includes/foot.html"); ?>

