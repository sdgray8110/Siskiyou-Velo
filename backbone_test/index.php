<html>
<head></head>
<body>

<div id="content"></div>

<script src="js/lib/jquery-1.5.1.min.js"></script>
<script src="js/lib/underscore.js"></script>
<script src="js/lib/backbone.js"></script>

<script src="js/model/content.js"></script>
<script src="js/view/content.js"></script>
<script src="js/router/content.js"></script>

<script type="text/template" id="template">
    <span><%= title %></span>
    <span><%= dingdong %></span>
</script>

<script type="text/template" id="library-template">
    <h1>Library</h1>
    <ul class="contents">
    </ul>
</script>

<script>
    $(document).ready(function() {
        window.library.fetch();
    });
</script>

</body>
</html>