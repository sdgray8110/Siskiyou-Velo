<div id="userMetricsAdmin">

</div>


<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="jquery.template.min.js"></script>
<script type="text/javascript" src="metricsAdmin.js"></script>

<script id="adminData" type="text/x-jquery-tmpl">
    <table class="wp-list-table widefat fixed">
        <thead>
            <tr>
                {{each(i,header) headers}}
                <th>${header}</th>
                {{/each}}
            </tr>
        </thead>
        <tbody>
            {{each companies}}
            <tr>
                <td><a href="admin.php?page=mhm-user-metrics/pages/metrics.php&view=companyUsers&companyID=${id}">${name}</a></td>
                <td>${availableReports}</td>
                <td>${report}</td>
                <td>${whitePaper}</td>
                <td>${execSummary}</td>
                <td>${brochure}</td>
                <td>${pageviews}</td>
                <td>${companyLogins}</td>
            </tr>
            {{/each}}
        </tbody>
    </table>
</script>