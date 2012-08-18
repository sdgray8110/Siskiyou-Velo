<style type="text/css">
    #addAnother, #finalTotalContainer {display:none;}
    .quantity {width:30px; text-align:center;}
    .productRow {display:inline-block;}
    .productRow span {margin:0 10px; display:inline-block; width:115px; text-align:center;}
    select {width:275px;}
</style>

<form id="buildOrder" name="buildOrder" action="" method="">

    <?php include('includes/categorySelection.php'); ?>

</form>
<p><a href="" id="addAnother">Add Another Item &raquo;</a></p>
<p id="saveOrder"><a href="">Save Order &raquo;</a></p>
<p id="finalTotalContainer">Final Total: <span id="finalTotal">$0.00</span></p>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.min.js"></script>
<script type="text/javascript" src="docroot/preventKeyCode.js"></script>
<script type="text/javascript" src="docroot/teamOrder.js"></script>