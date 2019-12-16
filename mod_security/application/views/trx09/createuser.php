<div class="row-fluid">
    <div class="span3">
     
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f07r">Status User</label>
            <div class="controls">
                <?php
                $no = 'A';
                foreach ($default['f07r'] as $row) {
                    ?>  
                    <input id="f07r<?php echo $no ?>" name="f07r" type="radio" 
                           value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                           <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                           <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> <br/>
                           <?php
                           $no++;
                       }
                       ?>
                <span id="err_f07r"></span>
            </div>        
        </div> 
        <!-- Group end -->    
        <!-- Group -->
             <div class="control-group">
            <label class="control-label" for="f08r">User ID</label>
            <div class="controls">
                <input class="span10" id="f08r" name="f08r" type="text"  value="<?php echo set_value('f08r', isset($default['f08r']) ? $default['f08r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f08r'])) ? $default['readonly_f08r'] : ''; ?>
                       />
                <span id="err_f08r"></span>
            </div>  
        </div> 
        <!-- Group end -->
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f09r">Internal Email</label>
            <div class="controls">
                <input class="span10" id="f09r" name="f09r" type="text"  value="<?php echo set_value('f09r', isset($default['f09r']) ? $default['f09r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f09r'])) ? $default['readonly_f09r'] : ''; ?>
                       />
                <span id="err_f09r"></span>
            </div>  
        </div> 
        <!-- Group end -->
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f10r">External Email</label>
            <div class="controls">
                <input class="span10" id="f10r" name="f10r" type="text"  value="<?php echo set_value('f10r', isset($default['f10r']) ? $default['f10r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f10r'])) ? $default['readonly_f10r'] : ''; ?>
                       />
                <span id="err_f10r"></span>
            </div>  
        </div> 
        <!-- Group end -->    
        
         <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="checkcuser">Warning</label>
            <div class="controls">
                <input id="checkcuser" name="checkcuser" type="checkbox" 
                    value="<?php echo (isset($default['checkcuser']['value'])) ? $default['checkcuser']['value'] : ''; ?>"
                    <?php echo (isset($default['checkcuser']['checked'])) ? $default['checkcuser']['checked'] : ''; ?> 
                    />
                    <?php echo (isset($default['checkcuser']['display'])) ? $default['checkcuser']['display'] : ''; ?>    
              <span id="err_checkcuser"></span>
            </div>  
        </div> 
        <!-- Group end -->
        
    </div>
    <div class="span9">
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f11r">Access Internet</label>
            <div class="controls">
                <?php
                $no = 'A';
                foreach ($default['f11r'] as $row) {
                    ?>  
                    <input id="f11r<?php echo $no ?>" name="f11r" type="radio" 
                           value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                           <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                           <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> <br/>
                           <?php
                           $no++;
                       }
                       ?>
                <span id="err_f11r"></span>
            </div>        
        </div> 
        <!-- Group end -->    
    </div>
   
</div>

