<style>
    .current,
    .disabled {
        -moz-border-bottom-colors: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        border-color: #ddd;
        border-image: none;
        border-style: solid;
        border-width: 1px 1px 1px 0;
        float: left;
        line-height: 34px;
        padding: 0 14px;
        text-decoration: none;
    }
</style>


<?php echo $this->Html->css('/css/admin/event-trading.css'); ?>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <?php  echo $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => "Events", 2 => __("Events Management"))))); ?>
        </div>
        
        <div class="row-fluid "> 
            <h3><?php echo __('Event Management'); ?> <span class="go-back"><i class="icon-arrow-left"></i></span></h3>
        </div>
        <br />
        
        <div id="page" class="dashboard">
        <div class="row-fluid ">
            <?php echo $this->element('flash_message'); ?>
        </div>
            
            
        <div class="row-fluid ">   
            <div class="span3" style="padding-left: 20px;"> 
                <h4 style="margin-bottom:5px;">Events Colors:</h4>
                <ul class="list-group">
                    <li><span class="notification red numberst"></span> Live</li>
                    <li><span class="notification green numberst"></span> Soccer Roulette</li>
                    <li><span class="notification numberst"></span> Special Event</li>
                    <li><span class="notification yellow numberst"></span> Outright</li>
                </ul>
            </div>
            
            <div class="span9">     
                <form action="" id="search-form" method="post" accept-charset="utf-8">
                    <div class="row-fluid "> 
                        <div class="search-inputs "><label for="EventId">ID:</label><input name="id" id="EventId" type="text"></div>
                        <div class="search-inputs "><label for="EventImportId">Betradar ID:</label><input name="import_id" id="EventImportId" type="text"></div>
                        <div class="search-inputs "><label for="EventName">Name:</label><input name="name" id="EventName" type="text"></div>
                        <div class="search-inputs "><label for="EventDate">Date:</label><input name="date" id="EventDate" type="text"></div>
                        <div class="search-inputs "><label for="EventActive">Active:</label><input name="active" value="1" id="EventActive" type="checkbox" checked="checked"></div>  
                        <div class="search-inputs "><label for="EventAlter">Changed:</label><input name="alter" value="1" id="EventAlter" type="checkbox"></div>                    
                    </div>
                    <br />
                    <div class="row-fluid "> 
                    <button type="submit" id="search_button" class="btn">Search</button>
                    </div>
                </form>
            </div>
        </div>   
            
            
        <div class="row-fluid ">                             
            <div class="span12" id="sport-table" style="margin-left: 5px;">
                <div class="widget">
                    <div class="widget-body">                              
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="table table-custom">
                                    <?php echo $this->element('tabs');?>
                                    <div class="tab-content">
                                        <table class="table table-hover"  cellpadding="0" cellspacing="0">
                                            <tr>
                                                <th class="text-center">
                                                    <?php echo $this->Paginator->sort('id'); ?>
                                                </th>
                                                <th>
                                                    <?php echo $this->Paginator->sort('name'); ?>
                                                </th>
                                            </tr>

                                            <?php
                                            $i = 1;
                                            foreach ($data as $field):
                                                $class = null;
                                                if ($i++ % 2 == 0) {
                                                    $class = ' alt';
                                                } ?>
                                                <tr>
                                                    <td class="text-center <?php echo $class; ?>"><?php echo $field[$model]['id']; ?></td>
                                                    <td class="<?php echo $class; ?>"><a class="table-link"  _href='/admin/leagues/showall/<?php echo $field[$model]['id']; ?>'><?php echo $field[$model]['name']; ?></a></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                        
                                        <?php if ($this->Paginator->hasPage(2)): ?>
                                        <ul class="pagination">
                                            <?php 
                                            echo $this->Paginator->prev('«', array('tag' => 'li', 'class' => 'btn-prev'), null, array('class' => 'page-num')); 
                                            echo $this->Paginator->numbers(array('tag' => 'li','separator' => '','class' => 'page-num'));
                                            echo $this->Paginator->next('»', array('tag' => 'li', 'class' => 'btn-next'), null, array('class' => 'page-num')); 
                                            ?>
                                        </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="league-table" style="margin-left: 5px;">
            
            </div>
            
            <div id="event-table" style="margin-left: 5px;">
            
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
    </div>


<script type="text/javascript">
    $(document).ready(function() {
        $(document.body).on('click', '#sport-table .table-link', function(e) {   
            $('.sport-selected').removeClass('sport-selected');
            $(this).addClass('sport-selected');
            
            $.get($(this).attr("_href"), function(data) {            
                $('#sport-table').attr('class', '');           
                $('#league-table').attr('class', '');
                $('#sport-table').addClass('span6');
                $('#league-table').addClass('span6');
                $("#event-table").html("");                
                $("#league-table").html(data);
            })
        });
                
        $(document.body).on('click', '#league-table .table-link', function(e) {        
            $('#sport-table').attr('class', '');           
            $('#league-table').attr('class', '');         
            $('#event-table').attr('class', '');
            $('#sport-table').addClass('span2');
            $('#league-table').addClass('span3');
            $('#event-table').addClass('span7');
                
            $('.league-selected').removeClass('league-selected');
            $(this).addClass('league-selected');
            
            $.get($(this).attr("_href"), function(data) {
                $("#event-table").html(data);
            })
        });
                  
        $(document.body).on('click', '.page-num, .btn-prev, .btn-next', function(e) {
            
            
            var _this = this;
            
            $.get($(_this).children('a').attr('href'), function(data) {
                $(_this).parents('.span4').html(data);
            });
        });        
              
        $(document.body).on('click', '#search_button', function(e) {
            e.preventDefault();
            
            $.ajax({
                url:        '/admin/events/search',
                method:     "POST",
                data:       $('#search-form').serialize(),
                success:    function(data) {  
                    $("#sport-table").hide();
                    $("#league-table").hide();
                    $('#event-table').html(data);
                    $('#event-table').removeClass('span7');
                    $('#event-table').addClass('span11');
                    $('.go-back').show();
                },
                error:      function(data) {
                    console.log("Error: " , data);
                }
            });
        });
        
         $(document.body).on('click', '.go-back', function(e) {             
            $('.go-back').hide();
            $("#sport-table").show();
            $("#league-table").show();
            $("#league-table").html("");
            $('#event-table').html("");
            $('#event-table').addClass('span7');
            $('#event-table').removeClass('span11');
         });
    });
</script>