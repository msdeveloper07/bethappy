<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">SEO settings</li>
    </ol>
</nav>

<?php echo $this->element('flash_message'); ?>


<h1 class="h3 mb-1 text-gray-800"><i class="fas fa fa-desktop"></i> <?= __('SEO settings'); ?></h1>
<p>
    <?php echo __('What is SEO/Search Engine Optimization?'); ?>
    <br>
    <?php echo __('SEO stands for "search engine optimization." It is the process of getting traffic from the "free," "organic," "editorial" or "natural" listings on search engines. All major search engines such as Google, Yahoo and Bing have such results, where web pages and other content such as videos or local listings are shown and ranked based on what the search engine considers most relevant to users. Payment is not involved, as it is with paid search ads.'); ?>
</p>
<?php echo $this->Form->create('Setting'); ?>
<ul class="list-group">
    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0"><?= __('Title tag'); ?><br>
                    <small class="text-muted font-italic"><?= __('A title tag is the main text that describes an online document. It appears in three key places: browsers, search engine results pages, and external websites.'); ?></small>
                </p>
            </div>
            <div class="col-md-4">
                <?= $this->Form->input($data['defaultTitle']['id'], array('value' => $data['defaultTitle']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
    </li>

    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0"><?= __('Meta author'); ?><br>
                    <small class="text-muted font-italic"><?= __('Name of website creator.'); ?></small>
                </p>
            </div>
            <div class="col-md-4">
                <?= $this->Form->input($data['metaAuthor']['id'], array('value' => $data['metaAuthor']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
    </li>

    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0"><?= __('Meta keywords'); ?><br>
                    <small class="text-muted font-italic"><?= __('Specify important keywords related to the website. Later these keywords are used by the search engines while indexing your website for searching purpose. Keywords are words or phrases which desribe your website content. Use comma for separation.'); ?></small>
                </p>
            </div>
            <div class="col-md-4">
                <?= $this->Form->input($data['metaKeywords']['id'], array('value' => $data['metaKeywords']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
    </li>

    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0"><?= __('Meta description'); ?><br>
                    <small class="text-muted font-italic"><?= __('Short description about the website. This again can be used by various search engines while indexing your website for searching purpose.'); ?></small>
                </p>
            </div>
            <div class="col-md-4">
                <?= $this->Form->input($data['metaDescription']['id'], array('value' => $data['metaDescription']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
    </li>

    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0"><?= __('Reply to email'); ?><br>
                    <small class="text-muted font-italic"><?= __('Email which would be visible in search engines as part of SEO outputs.'); ?></small>
                </p>
            </div>
            <div class="col-md-4">
                <?= $this->Form->input($data['metaReplayTo']['id'], array('value' => $data['metaReplayTo']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
    </li>

    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0"><?= __('Copyright'); ?><br>
                    <small class="text-muted font-italic"><?= __('Owner of product which would appear in SEO outputs.'); ?></small>
                </p>
            </div>
            <div class="col-md-4">
                <?= $this->Form->input($data['metaCopyright']['id'], array('value' => $data['metaCopyright']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
    </li>

    <li class="list-group-item form-group">
        <div class="row align-items-center">
            <div class="col-md-8">
                <p class="mb-0"><?= __('Bot content revisit time'); ?><br>
                    <small class="text-muted font-italic"><?= __('How often search engine bot should revisit your website for new content.'); ?></small>
                </p>
            </div>
            <div class="col-md-4">
                <?= $this->Form->input($data['metaRevisitTime']['id'], array('value' => $data['metaRevisitTime']['value'], 'class' => 'form-control form-control-sm', 'label' => false, 'div' => false)); ?>
            </div>
        </div>
    </li>
</ul>
<div class="row form-group">
    <div class="col-sm-12 col-md-2 offset-md-10 my-3">
        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn btn-primary btn-block')); ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>

<!--<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => $pluralName, 2 => __('SEO %s', $pluralName))))); ?>
        </div>
    </div>
    <div id="page" class="dashboard">
        <?php echo $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <div class="widget">
                    <div class="widget-body">
                        <?php echo $this->element('search'); ?>
                        <div class="row-fluid">
                            <div class="span12">
                                BEGIN TABS
                                <div class="table table-custom">
                                    <div class="tab-content">

                                        <?php
                                        $options = array(
                                            'inputDefaults' => array(
                                                'label' => false,
                                                'div' => false)
                                        );

                                        echo $this->Form->create('Setting', $options);
                                        ?>

                                        <?php echo __('What Is <b>SEO</b> / Search Engine Optimization?'); ?>

                                        <br><br>

                                        <?php echo __('SEO stands for "search engine optimization." It is the process of getting traffic from the "free," "organic," "editorial" or "natural" listings on search engines. All major search engines such as Google, Yahoo and Bing have such results, where web pages and other content such as videos or local listings are shown and ranked based on what the search engine considers most relevant to users. Payment is not involved, as it is with paid search ads.'); ?>

                                        <br><br>

                                        <table class="table table-bordered table-striped">

                                            <tr>
                                                <th><?php echo __('Description'); ?></th>
                                                <th><?php echo __('Value'); ?></th>

                                            </tr>

                                            <tr>
                                                <td><?php echo __('Title tag'); ?></td>
                                                <td><?php echo $this->Form->input($data['defaultTitle']['id'], array('value' => $data['defaultTitle']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">A title tag is the main text that describes an online document. It appears in three key places: browsers, search engine results pages, and external websites.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Keywords'); ?></td>
                                                <td><?php echo $this->Form->input($data['metaKeywords']['id'], array('value' => $data['metaKeywords']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Keywords are words or phrases which desribes your website content. Use comma for separation.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Description'); ?></td>
                                                <td><?php echo $this->Form->input($data['metaDescription']['id'], array('value' => $data['metaDescription']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">This description would appear in search engines.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Reply to email'); ?></td>
                                                <td><?php echo $this->Form->input($data['metaReplayTo']['id'], array('value' => $data['metaReplayTo']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Email which would be visible in search engines as part of SEO outputs.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Copyright:'); ?></td>
                                                <td><?php echo $this->Form->input($data['metaCopyright']['id'], array('value' => $data['metaCopyright']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Owner of product which would appear in SEO outputs.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Bot content revisit time'); ?></td>
                                                <td><?php echo $this->Form->input($data['metaRevisitTime']['id'], array('value' => $data['metaRevisitTime']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Write how often search engine bot should revisit you for new content.</span></td>
                                            </tr>
                                            <tr>
                                                <td><?php echo __('Author'); ?></td>
                                                <td><?php echo $this->Form->input($data['metaAuthor']['id'], array('value' => $data['metaAuthor']['value'])); ?><span style="font-size: x-small; font-style:italic; padding-left: 10px;">Name of product creator.</span></td>
                                            </tr>
                                        </table>
                                        <br />
                                        <?php echo $this->Form->submit(__('Save', true), array('class' => 'btn')); ?>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                                END TABS
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>-->