<div class="row-fluid">
<div class="span3">
    <!-- Group -->
    <div class="control-group">
        <label class="control-label" for="f16r">Access Folder</label>
        <div class="controls">
            <input class="span10" id="f16r" name="f16r" type="text"  value="<?php echo set_value('f16r', isset($default['f16r']) ? $default['f16r'] : ''); ?>" 
            <?php echo (isset($default['readonly_f16r'])) ? $default['readonly_f16r'] : ''; ?>
                   />
            <span id="err_f16r"></span>
        </div>  
    </div> 
    <!-- Group end -->  
</div>

<div class="span9">
    <!-- Group -->
    <div class="control-group">
        <label class="control-label" for="f17r">Action</label>
        <div class="controls">
            <?php
            $no = 'A';
            foreach ($default['f17r'] as $row) {
                ?>  
                <input id="f17r<?php echo $no ?>" name="f17r" type="radio" 
                       value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>" 
                       <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> > 
                       <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> <br/> 
                       <?php
                       $no++;
                       
                   }
                   ?>
            <span id="err_f17r"></span>
        </div>        
    </div> 
    <!-- Group end --> 
</div>
    
    <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="checkafolder">Warning</label>
            <div class="controls">
                <input id="checkafolder" name="checkafolder" type="checkbox" 
                    value="<?php echo (isset($default['checkafolder']['value'])) ? $default['checkafolder']['value'] : ''; ?>"
                    <?php echo (isset($default['checkafolder']['checked'])) ? $default['checkafolder']['checked'] : ''; ?> 
                    />
                    <?php echo (isset($default['checkafolder']['display'])) ? $default['checkafolder']['display'] : ''; ?>    
              <span id="err_checkafolder"></span>
            </div>  
        </div> 
        <!-- Group end -->
</div>


