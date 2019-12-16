<div class="row-fluid">
    <div class="span3">
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f12r">Software</label>
            <div class="controls">
                <input class="span10" id="f12r" name="f12r" type="text"  value="<?php echo set_value('f12r', isset($default['f12r']) ? $default['f12r'] : ''); ?>" 
                <?php echo (isset($default['readonly_f12r'])) ? $default['readonly_f12r'] : ''; ?>
                       />
                <span id="err_f12r"></span>
            </div>  
        </div> 
        <!-- Group end -->    
    </div>
    <div class="span9">
        <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="f13r">Action</label>
            <div class="controls">
                <?php
                $no = 'A';
                foreach ($default['f13r'] as $row) {
                    ?>  
                    <input id="f13r<?php echo $no ?>" name="f13r" type="radio" 
                           value="<?php echo (isset($row['value'])) ? $row['value'] : ''; ?>"
                           <?php echo (isset($row['checked'])) ? $row['checked'] : ''; ?> >
                           <?php echo (isset($row['display'])) ? $row['display'] : ''; ?> <br/>
                           <?php
                           $no++;
                       }
                       ?>
                <span id="err_f13r"></span>
            </div>        
        </div> 
        <!-- Group end -->    
    </div>
    <!-- Group -->
        <div class="control-group">
            <label class="control-label" for="checksoftware">Warning</label>
            <div class="controls">
                <input id="checksoftware" name="checksoftware" type="checkbox" 
                    value="<?php echo (isset($default['checksoftware']['value'])) ? $default['checksoftware']['value'] : ''; ?>"
                    <?php echo (isset($default['checksoftware']['checked'])) ? $default['checksoftware']['checked'] : ''; ?> 
                    />
                    <?php echo (isset($default['checksoftware']['display'])) ? $default['checksoftware']['display'] : ''; ?>    
              <span id="err_checksoftware"></span>
            </div>  
        </div> 
        <!-- Group end -->
</div>

