<div class="row-fluid">
<div class="span3">
    <!-- Group -->
    <div class="control-group">
        <label class="control-label" for="f14r">Create Folder</label>
        <div class="controls">
            <input class="span10" id="f14r" name="f14r" type="text"  value="<?php echo set_value('f14r', isset($default['f14r']) ? $default['f14r'] : ''); ?>" 
            <?php echo (isset($default['readonly_f14r'])) ? $default['readonly_f14r'] : ''; ?>
                   />
            <span id="err_f14r"></span>
        </div>  
    </div> 
    <!-- Group end -->  
</div>

<div class="span9">
    <!-- Group -->
    <div class="control-group">
        <label class="control-label" for="f15r">Action</label>
        <div class="controls">
            <?php
            $no = 'A';
            foreach ($default['f15r'] as $row) {
                ?>  
                <input id="f15r<?php echo $no ?>" name="f15r" type="radio" 
                       value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                       <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                       <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> <br/>
                       <?php
                       $no++;
                   }
                   ?>
            <span id="err_f14r"></span>
        </div>        
    </div> 
    <!-- Group end --> 
</div>
    <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="checkcfolder">Warning</label>
            <div class="controls">
                <input id="checkcfolder" name="checkcfolder" type="checkbox" 
                    value="<?php echo (isset($default['checkcfolder']['value'])) ? $default['checkcfolder']['value'] : ''; ?>"
                    <?php echo (isset($default['checkcfolder']['checked'])) ? $default['checkcfolder']['checked'] : ''; ?> 
                    />
                    <?php echo (isset($default['checkcfolder']['display'])) ? $default['checkcfolder']['display'] : ''; ?>    
              <span id="err_checkcfolder"></span>
            </div>  
        </div> 
        <!-- Group end -->
</div>
