        <div id="mainContainer">
        
            <h1 class="title">Blog Administration</h1>
            
            <div class="mainBody">
                <div class="mainBody_top"></div>
                <h2>Upload File</h2>

                <form id="profile" action="includes/file_upload.php" enctype="multipart/form-data" action="includes/file_upload.php" method="POST">
                	<div id="subCont">
                        <label for title="title">Choose File:</label>
                        <input style="background:#fff;" class="required" type="file" id="uploaded" name="uploaded" tabindex="1" />                  
						<input class="submit" style="float:right;" type="submit" name="upload" id="upload" value="Upload File" />
                    </div>                  
                  
              	</form>

                <div class="mainBody_bottom"></div>
            </div>
                        
		</div>