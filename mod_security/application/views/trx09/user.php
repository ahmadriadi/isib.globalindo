<div class="row-fluid">
    <div class="span3">
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f01r">Request</label>
            <div class="controls">
                <input class="span9" id="f01r" name="f01r" type="text"  value="<?php echo set_value('f01r', isset($default['f01r']) ? $default['f01r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f01r'])) ? $default['readonly_f01r'] : ''; ?>
                       />
                <span id="err_f01r"></span>
            </div>  
        </div> 
        <!-- Group end -->
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f02r">Departement</label>
            <div class="controls">
                <input class="span10" id="f02r" name="f02r" type="text"  value="<?php echo set_value('f02r', isset($default['f02r']) ? $default['f02r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f02r'])) ? $default['readonly_f02r'] : ''; ?>
                       />
                <span id="err_f02r"></span>
            </div>  
        </div> 
        <!-- Group end -->

        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f03r">Computer Name</label>
            <div class="controls">
                <input class="span9" id="f03r" name="f03r" type="text"  value="<?php echo set_value('f03r', isset($default['f03r']) ? $default['f03r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f03r'])) ? $default['readonly_f03r'] : ''; ?>
                       />   
                <span id="err_f03r"></span>
            </div>  
        </div> 
        <!-- Group end -->
    </div>
    <div class="span9">
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f04r">No.Doc</label>
            <div class="controls">
                <input class="span3" id="f04r" name="f04r" type="text"  value="<?php echo set_value('f04r', isset($default['f04r']) ? $default['f04r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f04r'])) ? $default['readonly_f04r'] : ''; ?>
                       />
                <span id="err_f04r"></span>
            </div>  
        </div> 
        <!-- Group end -->

        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f05r">No.Process</label>
            <div class="controls">
                <input class="span3" id="f05r" name="f05r" type="text"  value="<?php echo set_value('f05r', isset($default['f05r']) ? $default['f05r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f05r'])) ? $default['readonly_f05r'] : ''; ?>
                       />
                <span id="err_f05rr"></span>
            </div>  
        </div> 
        <!-- Group end -->

        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f06r">Request Date</label>
            <div class="controls">
                <input class="span2" id="f06r" name="f06r" type="text"  value="<?php echo set_value('f06r', isset($default['f06r']) ? $default['f06r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f06r'])) ? $default['readonly_f06r'] : ''; ?>
                       />
                <span id="err_f06rr"></span>
            </div>  
        </div> 
        <!-- Group end -->
      
    </div>
      <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="checkuser">Warning</label>
            <div class="controls">
                <input id="checkuser" name="checkuser" type="checkbox" 
                    value="<?php echo (isset($default['checkuser']['value'])) ? $default['checkuser']['value'] : ''; ?>"
                    <?php echo (isset($default['checkuser']['checked'])) ? $default['checkuser']['checked'] : ''; ?> 
                    />
                    <?php echo (isset($default['checkuser']['display'])) ? $default['checkuser']['display'] : ''; ?>    
              <span id="err_checkuser"></span>
            </div>  
        </div> 
        <!-- Group end -->
</div>


