<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('General %s', $pluralName))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search');?>
                        <div class="row-fluid">
                            <div class="span12">
                                <!--BEGIN TABS-->
                                <div class="table table-custom">
                                    <div class="tab-content">
                                        <?php
                                            $options = array('inputDefaults' => array('label' => false, 'div' => false));
                                            echo $this->Form->create('Setting', $options);
                                            $yesNoOptions = array('1' => 'Yes', '0' => 'No');
                                            $timezones = $this->TimeZone->getTimeZones();
                                            $themes = $this->Beth->getThemesList();
                                        ?>

                                        <?php echo __('General settings controls some of the most basic configuration settings for your site: your site\'s title and location, who may register an account at your site, and how dates and times are calculated and displayed.'); ?>

                                        <br>
                                        <br>

                                        <table class="table table-bordered table-striped">
                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Website name'); ?></td>
                                                <td><?php echo $this->Form->input($data['websiteName']['id'], array('value' => $data['websiteName']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enter the name of your sportsbook here. Most themes will display this title, at the top of every page, and in the reader's browser titlebar. </span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Copyright'); ?></td>
                                                <td><?php echo $this->Form->input($data['copyright']['id'], array('value' => $data['copyright']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Owner rights for product use.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Contact Email'); ?></td>
                                                <td><?php echo $this->Form->input($data['contactMail']['id'], array( 'value' => $data['contactMail']['value']) ); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">All information through contact form will come to this email address.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Registration'); ?></td>
                                                <td><?php echo $this->Form->input($data['registration']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['registration']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enable (Yes) or disable (No) registration on website.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Login'); ?></td>
                                                <td><?php echo $this->Form->input($data['login']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['login']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enable (Yes) or disable (No) login function.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Password reset'); ?></td>
                                                <td><?php echo $this->Form->input($data['passwordReset']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['passwordReset']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enable (Yes) or disable (No) password reset for users.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Default currency'); ?></td>
                                                <td><?php echo $this->Form->input($data['defaultCurrency']['id'], array('type' => 'select', 'options' => $currencies, 'value' => $data['defaultCurrency']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Choose main currency on website. In order to add new please go to Settings -> Currencies.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Default timezone:'); ?></td>
                                                <td><?php echo $this->Form->input($data['defaultTimezone']['id'], array('type' => 'select', 'options' => $timezones, 'value' => $data['defaultTimezone']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Select time zone for whole website.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Default language'); ?></td>
                                                <td><?php echo $this->Form->input($data['defaultLanguage']['id'], array('type' => 'select', 'options' => $locales, 'value' => $data['defaultLanguage']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Select website main language. In order to add new language please contact ChalkPro support team.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Charset'); ?></td>
                                                <td><?php echo $this->Form->input($data['charset']['id'], array('value' => $data['charset']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"> Charsets are identifiers used to describe a series of universal characters used in web and internet protocols such as HTML and Microsoft Windows. Default one is "utf-8".</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Items per page'); ?></td>
                                                <td><?php echo $this->Form->input($data['itemsPerPage']['id'], array('value' => $data['itemsPerPage']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Number of maximum rows which will be displayed in one page (only in administration panel)</td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Referral system'); ?></td>
                                                <td><?php echo $this->Form->input($data['referals']['id'], array('type' => 'select', 'options' => $yesNoOptions,'value' => $data['referals']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Enable (Yes) or disable (No) referral system.</td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Site under maintanance'); ?></td>
                                                <td><?php echo $this->Form->input($data['under_maintanance']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['under_maintanance']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Redirect all user except administrators to maintanance page.'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Show game slider'); ?></td>
                                                <td><?php echo $this->Form->input($data['game_slider']['id'], array('type' => 'select', 'options' => $yesNoOptions, 'value' => $data['game_slider']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Show game slider at front page'); ?></span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Enter KYC acceptable file formats'); ?></td>
                                                <td><?php echo $this->Form->input($data['kyc_file_formats']['id'], array('style' => 'width: 600px','value' => $data['kyc_file_formats']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;"><?php print __('Enter acceptable KYC file formats'); ?></span></td>
                                            </tr>
                                        </table>
                                        <br />
                                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn')); ?>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>