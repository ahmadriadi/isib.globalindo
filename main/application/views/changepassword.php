<!-- Content -->
<div id="content"">
    <h3>Change Password</h3>
    <div class="innerLR">

        <!-- Form -->
        <form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">

            <!-- Widget -->
            <div class="widget">

                <!-- Widget heading -->
                <div class="widget-head">
                    <h4 class="heading">Validate a form with jQuery</h4>
                </div>
                <!-- // Widget heading END -->

                <div class="widget-body">

                    <!-- Row -->
                    <div class="row-fluid">

                        <!-- Column -->
                        <div class="span6">

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="firstname">First name</label>
                                <div class="controls"><input class="span12" id="firstname" name="firstname" type="text" /></div>
                            </div>
                            <!-- // Group END -->

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="lastname">Last name</label>
                                <div class="controls"><input class="span12" id="lastname" name="lastname" type="text" /></div>
                            </div>
                            <!-- // Group END -->

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="username">Username</label>
                                <div class="controls"><input class="span12" id="username" name="username" type="text" /></div>
                            </div>
                            <!-- // Group END -->

                        </div>
                        <!-- // Column END -->

                        <!-- Column -->
                        <div class="span6">

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="password">Password</label>
                                <div class="controls"><input class="span12" id="password" name="password" type="password" /></div>
                            </div>
                            <!-- // Group END -->

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="confirm_password">Confirm password</label>
                                <div class="controls"><input class="span12" id="confirm_password" name="confirm_password" type="password" /></div>
                            </div>
                            <!-- // Group END -->

                            <!-- Group -->
                            <div class="control-group">
                                <label class="control-label" for="email">E-mail</label>
                                <div class="controls"><input class="span12" id="email" name="email" type="email" /></div>
                            </div>
                            <!-- // Group END -->

                        </div>
                        <!-- // Column END -->

                    </div>
                    <!-- // Row END -->

                    <hr class="separator" />

                    <!-- Row -->
                    <div class="row-fluid uniformjs">

                        <!-- Column -->
                        <div class="span4">
                            <h4 style="margin-bottom: 10px;">Policy &amp; Newsletter</h4>
                            <label class="checkbox" for="agree">
                                <input type="checkbox" class="checkbox" id="agree" name="agree" />
                                Please agree to our policy
                            </label>
                            <label class="checkbox" for="newsletter">
                                <input type="checkbox" class="checkbox" id="newsletter" name="newsletter" />
                                Receive Newsletter
                            </label>
                        </div>
                        <!-- // Column END -->

                        <!-- Column -->
                        <div class="span8">
                            <div id="newsletter_topics">
                                <h4>Topics</h4>
                                <p>Select at least two topics you would like to receive in the newsletter.</p>
                                <label for="topic_marketflash">
                                    <input type="checkbox" id="topic_marketflash" value="marketflash" name="topic" />
                                    Marketflash
                                </label>
                                <label for="topic_fuzz">
                                    <input type="checkbox" id="topic_fuzz" value="fuzz" name="topic" />
                                    Latest fuzz
                                </label>
                                <label for="topic_digester">
                                    <input type="checkbox" id="topic_digester" value="digester" name="topic" />
                                    Mailing list digester
                                </label>
                            </div>
                        </div>
                        <!-- // Column END -->

                    </div>
                    <!-- // Row END -->

                    <hr class="separator" />

                    <!-- Form actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Save</button>
                        <button type="button" class="btn btn-icon btn-default glyphicons circle_remove"><i></i>Cancel</button>
                    </div>
                    <!-- // Form actions END -->

                </div>
            </div>
            <!-- // Widget END -->

        </form>
        <!-- // Form END -->

    </div>	

</div>
<!-- // Content END -->
