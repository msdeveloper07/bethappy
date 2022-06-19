<style>
    .pretty-font {
        font-family: "letter-gothic-std",Consolas,"Liberation Mono",Courier,monospace;
        letter-spacing: -.5px;
    }
    
    .text-function {
        color:#2f96b4;
        text-transform: lowercase;
    }
    
    .text-value {
        color:#b35e14;
        text-transform: none;
    }
    
    .text-cmd {
        color:#75438a;
        text-transform:uppercase;
        font-size: 12px;
    }
    
    .alias-star {
        font-size:16px;
        position: relative;
        top:1px;
    }
    
    .ng-date-picker {
        width: 64px !important;
    }
    
    .toolbar {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        opacity: 0;
        -webkit-transition: all 0.4s;
           -moz-transition: all 0.4s;
             -o-transition: all 0.4s;
                transition: all 0.4s;
    }
    
    #board:hover .toolbar { 
        opacity: 1;
    }
        
    .toolbar a {
        padding: 3px;
    }
    
    .query-properties {        
        position: absolute;
        top: 0;
        right: 0;
        z-index: 2;
        bottom: -20px;
        padding: 2px 5px;
        background: #fff;
        border: 1px solid #ddd;
        border-right: none;
        box-sizing: border-box;
        box-shadow: 0px 0px 10px -4px #000;
    }
    
    .query-properties h4 {
        text-align: left;
        padding: 5px;
    }
    
    .query-properties h4 a {
        float:right;
    }
        
    .query-properties table td,
    .query-properties table th {
        vertical-align: middle;
        font-size: 12px;
    }
    
    .query-properties table tr:first-child td,
    .query-properties table tr:first-child th {
        border-top: 1px solid #0088cc;
    }
    
    .query-properties table td input {
        margin-bottom: 0;
    }
    
    .model-selection {
        text-transform: capitalize;
        margin: 5px;
        text-align: left;
        padding: 10px 20px 8px;
        border: 2px solid #ccc;
        border-radius: 3px;
        display: inline-block;
        color: #ccc;
    }
    
    .model-selection:first-of-type {
        margin-left: 0;
    }
        
    .model-selection.available {
        color: #444444;
        border: 2px solid #444444;
    }
        
    .model-selection.available:hover {
        background-color: rgba(68, 68, 68, 0.1);
        cursor: pointer;
    }
    
    .board-lvl {
        display: inline-block;
        margin-right: 40px;
        min-width: 100px;
        vertical-align: middle;
    }
        
    .board-lvl:last-of-type {        
        margin-right: 0;
    }
    
    .node {
        text-transform: capitalize;
        text-align: left;
        padding: 10px;
        border: 2px solid black;
        border-radius: 5px;
        min-width: 230px;
        margin-bottom: 10px;
    }
            
    .node:hover {
        background-color: rgba(47, 150, 180, 0.1);
        cursor: pointer;
    }
    
    .node.first {
        border: 2px solid #2f96b4;
    }
    
    .node.mini {
        min-width: 130px;
    }
    
    .node.selected {
        border: 2px solid #62c462;
    }
    
    .node h4 {
        font-size: 16px;
        margin-bottom: 5px;
    }
    
    .node h4 a {
        padding: 2px;
        position: relative;
        top: -3px;
        float: right;
    }
    
    .node ul {
        margin: 0 0 0px 17px;
    }
    
    .node .field {
        line-height: 20px;
        padding: 1px 0;
    }
    
    .node .field input {
        width: 40px;
        margin-bottom: 0;
        height: 14px;
        padding: 3px;
        border: 1px solid #bbb;
        border-radius: 1px;
    }
    
    .node .field .btns {
        display: inline-block;
        float: right;
        opacity: 0;          
        margin-left: 5px;  
        -webkit-transition: all 0.4s;
           -moz-transition: all 0.4s;
             -o-transition: all 0.4s;
                transition: all 0.4s;
    }    
    
    .node .field:hover > .btns {        
        opacity: 1;
    }
    
    .node .field .btns a {
        text-transform: lowercase;
        font-size: 12px;
        position: relative;
        padding: 1px;
    }
    
    .statements {
        margin: 2px 0 !important;
        list-style-type: none;
        border-top: 1px solid rgba(123, 0, 0, 0.4);
    }
    
    .statements li {
        padding: 2px 0;
        line-height: 22px;
    }
    
    .statements li:hover .btns {
        opacity: 1;
    }
    
    .statements li .case-scenarios {
        padding: 0 0px 5px 10px;
        /*border-top: 1px solid #ccc;*/
        /*margin-top: 5px;*/
    }
        
    .statements li .case-scenarios .case-scenario {
        border-left: 1px solid #ccc;
        position: relative;
        padding: 2px 0 0 10px;
        line-height: 15px;
    }
    
    .statements li .case-scenarios .case-scenario:before {
        content: '';
        position: absolute;
        border-top: 1px solid #ccc;
        width: 8px;
        height: 1px;
        top: 11px;
        left: 0;
    }
    
    .statements li .case-scenarios .case-scenario a {
        
    }
    
    .statements li .case-scenarios select {
        margin-bottom: 0;
        padding: 1px 3px 5px;
        height: 22px;
        border-radius: 1px;
    }
    
    .opt-select > h5 {
        min-width: 120px;
        font-size: 13px;
    }
    
    .opt-select .menu {
        margin: 0;
        padding: 0;
        min-width: 120px;
        list-style-type: none
    }
        
    .opt-select .menu li a {
        text-decoration: none;
        font-size: 12px;
    }
    
    .opt-select .menu li:hover {
        background: #0088cc;
    }
        
    .opt-select .menu li:hover a {
        color: #fff;
    }
    
    .field-panel-a {
        display: inline-block;        
        padding: 1px;
        position:relative;
    }
    
    .field-panel {
        position: absolute;
        display: none;
        z-index: 2;
        background: #fff;
        padding: 5px 9px;;
        border: 1px solid #ccc;
        box-shadow: 0px 1px 10px -3px #000;
        border-radius: 3px;
        /*left: -10px;*/
        /*bottom: -25px;*/
    }
    
    .field-panel a {        
        font-size: 14px !important;
        padding: 5px 2px;
    }
    
    .field-panel a:last-of-type {
        margin-right: 0;
    }
    
    .field-panel-a.open .field-panel {        
        display: block;
    }
    
    .node .assocs {        
        padding: 3px 0;
    }
    
    .node .assocs:first-of-type {
        border-top: 1px solid #929292;
        margin-top: 5px;
        padding-top: 5px;
    }
    
    .qd-lvl {
        display: inline-block;
        vertical-align: middle;
    }
    
    .qd-model {
        margin: 0px 5px;
        font-size: 14px;
        color: #62c462;
        font-size: 12px;
        text-transform: uppercase;
        /*font-size: 12px;*/
    }
    
    .qd-model small {
        color: #000;
        margin-left: 5px;
        font-size: 13px;
        text-transform: lowercase;
    }
    
    .qd-model small abbr {        
        color: #2f96b4;
    }
    
    .loader {
        z-index: 1000;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(255,255,255,.7);
    }    
    
    .loader p {
        margin-top:100px;
        padding: 50px;
        color: #2f96b4;
        font-weight: 600;
        font-size: 18px;
        text-align: center;
    }
</style>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12"><?= $this->element('breadcrumbs', array('data' => $this->Breadcrumb->buildFromURI(array(0 => '', 1 => __('Create %s', __($singularName)))))); ?></div>
    </div>
    <!-- BEGIN PAGE CONTENT-->
    <div id="page" class="dashboard" ng-controller="ReportCtrl">
        <?= $this->element('flash_message'); ?>
        <div class="row-fluid ">
            <div class="span12">
                <!-- BEGIN INLINE TABS PORTLET-->
                <div class="widget">
                    <div class="widget-body"  ng-cloak>
                        <div class="row-fluid">
                            <div class="span12">
                                <ul class="nav nav-tabs">
                                    <li ng-class="{'active': tab === 0}"><a href="#" ng-click="changeTab(0)"><?=__('Query');?></a></li>
                                    <li ng-class="{'active': tab === 1}"><a href="#" ng-click="changeTab(1);"><?=__('Report');?></a></li>
                                </ul>
                                
                                <!--BEGIN TABS-->
                                <div ng-show="tab === 0" class="table table-custom">
                                    <div class="tab-content" style="position: relative">   
                                        <div class="loader" ng-show="loading"><p><?=__('Loading...');?><p></div>
                                        
                                        <div class="row-fluid">
                                           <div class="span12">
                                               <h3 style="margin-bottom:5px;"><?=__('Build your Report:');?></h3>
                                           </div>
                                        </div>
                                        
                                        <div class="row-fluid" ng-cloak style="margin-bottom:10px;">
                                           <div class="span12">
                                               <div ng-repeat="model in models" class="model-selection" ng-class="{'available': model.available}" ng-click="selectModel(model)">{{model.name}}</div>
                                           </div>
                                        </div>
                                        
                                        <div class="row-fluid">
                                           <div class="span12">
                                               <h3 style="margin-bottom:5px;"><?=__('Query');?>: {{qName || qID}}  
                                                   <button style="float:right;font-size: 13px;" class="btn btn-mini btn-warning" ng-show="lvls[0]" ng-click="clear();"><i class="icon-white icon-refresh"></i> <?=__('Clear');?></button>
                                                   <button style="float:right;margin-right:5px;font-size: 13px;" class="btn btn-mini btn-success" ng-show="lvls[0]" ng-click="submit();"><i class="icon-white icon-play"></i><?=__('Submit');?></button>
                                               </h3>
                                           </div>
                                        </div> 
                                        
                                        <div class="row-fluid" ng-cloak style="position:relative;">
                                            <svg id="svg-container" style="position:absolute;width:100%;height:100%;pointer-events: none;"></svg>
                                            <div id="board" class="span12" style="text-align: center;margin: 20px 0;min-height: 200px;">
                                                <div class="toolbar" ng-show="lvls[0]">
                                                    <a data-toggle="tooltip" title="<?=__('Properties');?>" ng-click="showProperties = !showProperties;"><i class="icon-wrench"></i></a>
                                                    <a data-toggle="tooltip" title="<?=__('Save');?>" ng-click="saveQuery()"><i class="icon-folder-close"></i></a>
                                                    <a ng-show="qID" data-toggle="tooltip" title="<?=__('Delete');?>" ng-click="deleteQuery()"><i class="icon-trash"></i></a>
                                                </div>
                                                <div class="query-properties" ng-show="showProperties">
                                                    <h4><?=__('Query Properties');?>: <a data-toggle="tooltip" title="<?=__('Close');?>" ng-click="showProperties = false;"><i class="icon-remove"></i></a></h4>
                                                    <table>
                                                        <tr><th><?=__('ID');?>:</th><td>{{qID}}</td></tr>
                                                        <tr><th><?=__('Title');?>:</th><td><input type="text" ng-model="qName" /></td></tr>
                                                        <tr><th colspan="2"><?=__('Fields');?>:</th></tr>
                                                        <tr ng-repeat="field in qFields"><td>{{field.name}}</td><td></td></tr>
                                                    </table>
                                                </div>
                                                <div class="board-lvl" ng-repeat="(i, lvl) in lvls">
                                                    <div data-model="{{node.name}}" data-lvl="{{i}}" ng-repeat="node in lvl" class="node pretty-font" ng-class="{'selected': node.selected, 'first': i == 0, 'mini': node.mini}" ng-click="node.select()">
                                                        <h4>{{node.name}}  
                                                            <a data-toggle="tooltip"  title="<?=__('Remove this model');?>" ng-click="node.remove(i);$event.stopPropagation();"><i class="icon-remove"></i></a>
                                                            <a data-toggle="tooltip"  title="<?=__('Refresh model fields');?>" ng-click="node.refresh();$event.stopPropagation();"><i class="icon-refresh"></i></a>
                                                            <a data-toggle="tooltip"  title="<?=__('Hide model details');?>" ng-show="!node.mini" ng-click="node.minify(true); $event.stopPropagation();"><i class="icon-chevron-up"></i></a>
                                                            <a data-toggle="tooltip"  title="<?=__('Show model details');?>" ng-show="node.mini" ng-click="node.minify(false); $event.stopPropagation();"><i class="icon-chevron-down"></i></a>
                                                        </h4>
                                                        <ul>
                                                            <li class="field" ng-repeat="(f, field) in node.fields">
                                                                {{field.name}}
                                                                                                                                
                                                                <div class="btns" ng-show="!node.mini">                                                                    
                                                                    <div class="field-panel-a">
                                                                        <i style="font-size:15px" class="icon-filter"></i>    
                                                                                                            
                                                                        <div ng-if="field.type !== 'date'">   
                                                                            <div class="field-panel" ng-if="!field.values">                                                                    
                                                                                <a ng-repeat="condition in conditions" ng-click="field.addCondition(condition);">{{condition + ($last ? '': ',')}}</a>
                                                                            </div>
                                                                            <div class="field-panel" ng-if="field.values">
                                                                                <div class="opt-select">
                                                                                    <h5><?=__('Choose value:');?></h5>
                                                                                    <ul class="menu">
                                                                                        <li ng-repeat="(value, text) in field.values"><a ng-click="field.addCondition('=', null, value);">{{text}}</a></li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div ng-if="field.type === 'date'">  
                                                                            <div class="field-panel">
                                                                                <div class="opt-select">
                                                                                    <h5><?=__('Choose value:');?></h5>
                                                                                    <ul class="menu">
                                                                                        <li ng-repeat="condition in dateConditions"><a ng-click="field.addCondition(condition);">{{condition}}</a></li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div> 
                                                                    </div>
                                                                    
                                                                    <div class="field-panel-a" ng-if="node.operations && field.type !== 'date' && !field.values">
                                                                        <i style="font-size:15px" class="icon-cog"></i>                                                                        
                                                                        <div class="field-panel">                                                                    
                                                                            <a class="text-function" ng-repeat="op in operations" ng-click="field.addOperation(op);">{{op}}(){{($last ? '': ',')}}</a>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <a ng-click="node.removeField(f);$event.stopPropagation();"><i style="font-size:15px" class="icon-trash"></i></a>
                                                                </div>
                                                                
                                                                <ul class="statements" ng-if="field.statements.length > 0" ng-show="!node.mini">                                                                       
                                                                    <li ng-repeat="(s, statement) in field.statements">
                                                                        <div>   
                                                                            <span ng-if="statement.operation"><span class="text-function">{{statement.operation}}</span>({{field.name}})</span>
                                                                            <span ng-if="!statement.operation">{{field.name}}</span>   
                                                                            
                                                                            <div ng-if="statement.condition" style="display:inline;">                                                                                
                                                                                <div ng-if="field.type !== 'date'" style="display:inline;">
                                                                                    <span>{{statement.condition.operator}}</span>     

                                                                                    <input type="text" ng-if="!field.values" ng-model="statement.condition.value" />
                                                                                    <span class="text-value" ng-if="field.values" />{{humanizeValue(field.values, statement.condition.value)}}</span>
                                                                                </div>
                                                                                                                                                           
                                                                                <div ng-if="field.type === 'date'" style="display:inline;">      
                                                                                    <div ng-if="statement.condition.operator === 'between'" style="display:inline;">   
                                                                                        <span class="text-cmd"><?=__('between')?></span>     
                                                                                        <date-picker value="statement.condition.value1"></date-picker>
                                                                                        <span class="text-cmd"><?=__('and')?></span>   
                                                                                        <date-picker value="statement.condition.value2"></date-picker>
                                                                                    </div>
                                                                                    <div ng-if="statement.condition.operator !== 'between'" style="display:inline;">  
                                                                                        <span class="text-cmd"><?=__('during')?></span>   
                                                                                        <span class="text-value" />{{statement.condition.operator}}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <span class="alias-star" ng-show="statement.alias" data-toggle="tooltip" title="{{statement.alias}}">*</span>
                                                                            
                                                                            <div class="btns">     
                                                                                <div class="field-panel-a" ng-if="statement.operation">
                                                                                    <i style="font-size:15px" class="icon-edit"></i>                                                                        
                                                                                    <div class="field-panel" style="max-width:128px;">                                                                    
                                                                                        <label><?=__('Column Name:')?> <input style="width:120px;margin-top:4px;" type="text" ng-model="statement.alias" /></label>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="field-panel-a" ng-if="!statement.condition && !statement.scenarios">
                                                                                    <i style="font-size:15px" class="icon-filter"></i>                                                                        
                                                                                    <div class="field-panel">                                                                    
                                                                                        <a ng-repeat="condition in conditions" ng-click="field.addCondition(condition, s);$event.stopPropagation();">{{condition + ($last ? '': ',')}}</a>
                                                                                    </div>
                                                                                </div>
 
                                                                                <a ng-if="!statement.condition" ng-click="statement.createCaseScenario(node.name, field.name);$event.stopPropagation();"><i style="font-size:15px" class="icon-magnet"></i></a>
                                                                                <a ng-click="field.removeStatement(s);$event.stopPropagation();"><i style="font-size:15px" class="icon-trash"></i></a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="case-scenarios" ng-if="(statement.scenarios || statement.tmp) && !statement.condition">
                                                                            <div class="case-scenario" ng-repeat="(cs, scenario) in statement.scenarios">
                                                                                <span class="text-cmd"><?=__('when');?></span>
                                                                                <span>{{scenario.field}}</span>
                                                                                         
                                                                                <div ng-if="scenario.type === 'date'" style="display:inline;">     
                                                                                    <div ng-if="scenario.condition === 'between'" style="display:inline;">  
                                                                                        <span class="text-cmd" />{{scenario.condition}}</span>
                                                                                        <span class="text-value" />{{scenario.value1}}</span>   
                                                                                        <span class="text-cmd"><?=__('and')?></span>   
                                                                                        <span class="text-value" />{{scenario.value2}}</span>
                                                                                    </div>
                                                                                    
                                                                                    <div ng-if="scenario.condition !== 'between'" style="display:inline;">     
                                                                                        <span class="text-cmd"><?=__('during')?></span>   
                                                                                        <span class="text-value" />{{scenario.condition}}</span>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div ng-if="scenario.type !== 'date'" style="display:inline;">                                                                                       
                                                                                    <span>{{scenario.condition}}</span>
                                                                                    <span class="text-value">{{scenario.values ? humanizeValue(scenario.values, scenario.value) : scenario.value}}</span>
                                                                                </div>
                                                                                <span class="alias-star" ng-show="scenario.alias" data-toggle="tooltip" title="{{scenario.alias}}">*</span>
                                                                                
                                                                                <div class="btns">                                                                                       
                                                                                    <a ng-click="statement.removeCaseScenario(cs);$event.stopPropagation();"><i style="font-size:15px" class="icon-trash"></i></a>
                                                                                </div>
                                                                            </div>  

                                                                            <div ng-show="statement.tmp" style="border-top: 1px solid #ccc;padding: 3px 0;margin-top: 3px;">
                                                                                <select ng-model="statement.tmp.field" ng-change="statement.alterConditions(node.name);">
                                                                                    <option ng-repeat="option in statement.tmp.fields" value="{{option}}">{{option}}</option>
                                                                                </select>
                                                                                
                                                                                <div ng-show="!statement.tmp.values" style="display:inline">
                                                                                    <select ng-model="statement.tmp.condition" ng-change="redraw();">
                                                                                        <option ng-repeat="option in statement.tmp.conditions" value="{{option}}">{{option}}</option>
                                                                                    </select>                                                                                

                                                                                    <div ng-show="statement.tmp.type !== 'date'" style="display:inline">                                                                                        
                                                                                        <input type="text" ng-model="statement.tmp.value" />
                                                                                    </div>
                                                                                    
                                                                                    <div ng-show="statement.tmp.type === 'date' && statement.tmp.condition === 'between'" style="display:inline">                                                                                        
                                                                                        <date-picker value="statement.tmp.value1"></date-picker>
                                                                                        <span class="text-cmd"><?=__('and')?></span>  
                                                                                        <date-picker value="statement.tmp.value2"></date-picker>                                                                            
                                                                                    </div>
                                                                                </div>
                                                                                                                                                                                                             
                                                                                <div ng-show="statement.tmp.values" style="display:inline">
                                                                                    <select ng-model="statement.tmp.value">
                                                                                        <option ng-repeat="(key, value) in statement.tmp.values" value="{{key}}">{{value}}</option>
                                                                                    </select>      
                                                                                </div>
                                                                                
                                                                                <button class="btn btn-success btn-mini" ng-click="statement.saveCaseScenario();$event.stopPropagation();"><?=__('OK')?></button>
                                                                                <button class="btn btn-danger btn-mini" ng-click="statement.cancelCaseScenario();$event.stopPropagation();"><?=__('Cancel')?></button>
                                                                            </div>
                                                                        </div>                                                                        
                                                                    </li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                        <div class="assocs" data-assoc="{{assoc.name}}" data-lvl="{{i}}" ng-repeat="assoc in node.assocs"><span style="color:#62c462">{{assoc.name}}</span> <span ng-show="!node.mini">({{getModelAssociation(node.name, assoc.name)}})</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <hr />
                                        
                                        <div class="row-fluid">
                                           <div class="span12">
                                               <h3 style="margin-bottom:5px;margin-top:10px;">
                                                    <?=__('Stored Queries:');?>                                                    
                                               </h3>
                                           </div>
                                        </div>
                                        
                                        <div class="row-fluid" ng-cloak>
                                            <div class="span12" style="margin: 0;">
                                                <div class="table table-custom">
                                                    <div class="tab-content">                                                                                                                
                                                        <table class="table table-custom" cellpadding="0" cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th width="60" style="text-align:center"><?=__('ID');?></th>    
                                                                    <th width="120" style=""><?=__('Name');?></th>     
                                                                    <th width="100" style=""><?=__('Date');?></th>    
                                                                    <th style=""><?=__('Query');?></th>              
                                                                    <th></th>                                                                 
                                                                </tr> 
                                                            </thead>
                                                            <tbody>                                                                
                                                                <tr ng-repeat="q in queries">
                                                                    <td style="vertical-align:middle;text-align:center"><a href="javascript:;" ng-click="selectQuery(q);">{{q.id}}</a></td>  
                                                                    <td style="vertical-align:middle;">{{q.name}}</td> 
                                                                    <td style="vertical-align:middle;">{{formatDate(q.date);}}</td>  
                                                                    <td style="vertical-align:middle;" class="pretty-font" ng-bind-html="describeQuery(q);"></td>   
                                                                    <td style="vertical-align:middle;">
                                                                        <a ng-click="deleteQuery(q.id)"><i class="icon-trash"></i></a>
                                                                    </td>                                                                   
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                        
                                <div ng-show="tab === 1" class="table table-custom">
                                    <div class="tab-content">   
                                        <div class="row-fluid">
                                           <div class="span12">
                                               <h3 style="margin-bottom:5px;margin-top:10px;">
                                                    <?=__('View Query:');?>   
                                               </h3>
                                           </div>
                                        </div>
                                        
                                        <div class="row-fluid">
                                           <div class="span12">
                                               <pre>{{sql}}</pre>
                                           </div>
                                        </div>
                                        
                                        <hr />
                                            
                                        <div class="row-fluid">
                                           <div class="span12">
                                               <h3 style="margin-bottom:5px;margin-top:10px;">
                                                    <?=__('View Results:');?>                                                   
                                                    <a title="<?=__('Refresh Data');?>" style="float:right;margin-top: 3px;" class="btn btn-mini btn-primary" ng-click="submit();"><i style="font-size:15px" class="icon-refresh icon-white"></i><?=__('Refresh');?></a>
                                                    
                                                    <label style="float:right;margin-right:10px;">
                                                        <?=__('Refresh Rate');?>:                                                         
                                                        <select ng-model="refreshRate" style="height: 22px;padding: 0;margin: 0;" ng-change="setRefreshRate();">
                                                            <option ng-repeat="option in [0,5,10,30,60]" value="{{option}}">{{option}}</option>
                                                        </select> 
                                                    </label>
                                               </h3>
                                           </div>
                                        </div>
                                        
                                        <div class="row-fluid" ng-cloak>
                                            <div class="span12" style="margin: 0;">
                                                <div class="table table-custom">
                                                    <div class="tab-content">                                                        
                                                        <p style="padding:50px;text-align: center;" ng-show="data.length === 0"><?=__('Query did not yield any results.');?></p>
                                                        
                                                        <table ng-show="data.length > 0" class="table table-custom" cellpadding="0" cellspacing="0">
                                                            <thead>
                                                                <tr>
                                                                    <th ng-repeat="col in cols track by $index" style="text-align:center;text-transform: capitalize;"><a href="javascript:;" ng-click="orderData(col)"><i ng-class="{'icon-chevron-down': order.dir == '-', 'icon-chevron-up': order.dir == '+'}" ng-show="order.col === col"></i>{{prettifyCol(col)}}</a></th>                                                                    
                                                                </tr> 
                                                            </thead>
                                                            <tbody>                                                                
                                                                <tr ng-repeat="row in data | orderBy: (order.dir + order.col)">
                                                                    <td ng-repeat="col in cols track by $index" style="text-align:center">{{row[col]}}</td>                                                                    
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <!--END TABS-->
                            </div>
                            <div class="space10 visible-phone"></div>
                        </div>
                    </div>
                </div>
                <!-- END INLINE TABS PORTLET-->
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>    
    
<script type="text/javascript">  
$(document).ready(function() {
    $(document).tooltip({ selector: '[data-toggle="tooltip"]'});
    
    $(document).on('click', '.field-panel', function(e) { e.stopPropagation(); });
    
    $(document).on('click', '.field-panel-a', function(e) {
        e.stopPropagation();
        
        $(this).toggleClass('open');
    });
        
    $(document).on('click', function(e) {
        if($(e.target).parents('.field-panel-a').length > 0) return;
        
        $('.field-panel-a').removeClass('open');
    });
});
    
app.directive('datePicker', [function() {
    return {
        restrict: "E",
        replace: true,
        require: 'ngModel',
        scope: { 'value': '='},
        template: '<input class="ng-date-picker" type="text" ng-model="value" />',
        link: function(scope, element, attrs) {
            $(element).datepicker().on('changeDate', function(e) {
                scope.value = moment.utc(e.date).format('YYYY-MM-DD');
                scope.$apply();
            });
        }
    };
}]).controller('ReportCtrl', ['$scope', '$timeout', '$sce', function ($scope, $timeout, $sce) {   
    $scope.operations = ['count', 'sum', 'min', 'max'];
    $scope.dateConditions = ['between', 'last hour', 'last day', 'last week', 'last month'];
    $scope.conditions = ['>', '<', '=', '>=', '<=', 'like'];     
        
    $scope.models = JSON.parse('<?= json_encode($data);?>');    
    $scope.queries = JSON.parse(localStorage.customQueries || $('#demo-queries').html()) || {};
    
    $scope.models.forEach(function(x) { x.available = true; });    
        
    var query = {};   
    var selectedNode = {};   
    var associations = ['one to one', 'one to many', 'many to one', 'many to many'];  
 
    function drawAssocs() {
        var svgNS = "http://www.w3.org/2000/svg";  
        document.getElementById("svg-container").innerHTML = '';

        function createDot(x, y) {    
            var dot = document.createElementNS(svgNS, "circle");
            dot.setAttributeNS(null, "cx", x);
            dot.setAttributeNS(null, "cy", y);
            dot.setAttributeNS(null, "r",  2);
            dot.setAttributeNS(null, "fill", "none");
            dot.setAttributeNS(null, "stroke", "#444");
            dot.setAttributeNS(null, "stroke-width", 2); 

            document.getElementById("svg-container").appendChild(dot);
        };
                
        function createPath(pA, pB, c) {                 
            var path = document.createElementNS(svgNS, "path");
            path.setAttributeNS(null, "d", 'M' + (pA.x + 2) + ' ' + pA.y + ' L' + ((pA.x + 2) + (((pB.x - 2) - (pA.x + 2)) / 2) - 2 + (c*3)) + ' ' + pA.y + ' L' + ((pA.x + 2) + (((pB.x - 2) - (pA.x + 2)) / 2) - 2 + (c*3)) + ' ' + pB.y + ' L' + (pB.x - 2) + ' ' + pB.y);
            path.setAttributeNS(null, "fill", "none");
            path.setAttributeNS(null, "stroke", "#444");
            path.setAttributeNS(null, "stroke-width", 2); 

            document.getElementById("svg-container").appendChild(path);
        }; 
        
        var $assocs = $('[data-assoc]');
                                                            
        for(var i = 0; i < $assocs.length; i++) {                
            var $target = $('[data-model="' + $($assocs[i]).data('assoc') + '"][data-lvl="' + parseInt($($assocs[i]).data('lvl') + 1) + '"]');
            
            var pA = { 
                x: $assocs[i].offsetLeft + $($assocs[i]).width() + 14,
                y: $assocs[i].offsetTop + $($assocs[i]).height() - 3
            };
            var pB = { 
                x: $target[0].offsetLeft - 4,
                y: $target[0].offsetTop + ($target.outerHeight() / 2) - 3
            };
            
            createDot(pA.x, pA.y);            
            createDot(pB.x, pB.y);
            createPath(pA, pB, i)
        }
    };
    
    function generateDisplay() {
        $scope.lvls = {};
    
        function populateLvls(node, lvl) {
            if(!$scope.lvls[lvl]) $scope.lvls[lvl] = [];

            $scope.lvls[lvl].push(node);

            if(node.assocs.length > 0) {
                for(var i = 0;i < node.assocs.length;populateLvls(node.assocs[i], lvl+1),i++); 
            }
        };

        if(!query.root) return;

        populateLvls(query.root, 0);
        
        $scope.redraw();
    };
    
    function filterModels(name) {
        var names = [];
        
        (function iterate(node) {
            if(!node) return;
            
            names.push(node.name);
            
            for(var i = 0; i < node.assocs.length; iterate(node.assocs[i]), i++);
        })(query.root);
        
        for(var i in $scope.models) {
            var model = $scope.models[i];

            model.available = !name;
            
            for(var z in model.assocs) {
                var assoc = model.assocs[z];

                if(assoc.model === name && names.indexOf(model.name) === -1) {
                    model.available = true;
                }
            }
        }
    };
        
    function toJSON(data) {
        return JSON.parse(JSON.stringify(data));
    };
        
    var Node = function(data) {
       var that = {};
       
       that.name = data.name;
       that.assocs = data.assocs && data.assocs.length > 0 ? data.assocs.map(function(x) { return new Node(x); }) : [];
       that.fields = data.fields && data.fields.length > 0 ? data.fields.map(function(x) { return new Field(x);}) : [];

       that.mini = false;
       that.selected = false;
       that.operations = data.operations || false;

        that.select = function() {
            selectedNode.selected = false;
            that.selected = true;
            selectedNode = that;

            filterModels(that.name);
        };
       
        that.minify = function(mini) {
            that.mini = mini;

            $scope.redraw();
        };
       
        that.refresh = function() {
            var model = $scope.models.filter(function(x) { return x.name === that.name})[0];
         
            for(var i in model.fields) {
                if(that.fields.filter(function(x) { return x.name === model.fields[i].name}).length === 0) {          
                    that.fields.push(new Field(model.fields[i]));
                }
            }
            
            generateDisplay();
        };
       
        that.remove = function(lvl) {
            lvl = parseInt(lvl);
            
            $('[data-model="' + that.name + '"][data-lvl="' + lvl + '"] [data-toggle="tooltip"]').tooltip('hide');
            
            if(lvl === 0) return $scope.clear();
        
            function remove(node, l) {
                for(var i in node.assocs) {     
                    if(that.name === node.assocs[i].name && l + 1 === lvl) {                    
                        return node.assocs.splice(i, 1);
                    } else {
                        remove(node.assocs[i], l + 1);
                    }
                }
            }

            remove(query.root, 0);
            
            generateDisplay();       
            query.root.select();
       };
       
        that.removeField = function(i) {
            that.fields.splice(i,1);    
            $scope.redraw();
       };

       return that;
    };
   
    var Field = function(data) {
        var that = {};
       
        that.name = data.name;
        that.type = data.type;
        that.values = data.values;
        that.statements = data.statements && data.statements.length > 0 ? data.statements.map(function(x) { return new Statement(x); }) : [];
              
        that.addCondition = function(condition, i, val) {            
            if(typeof i !== 'undefined' && i !== null) {
                that.statements[i].condition = { operator: condition, value: val };
            } else {
                that.statements.push(new Statement({ condition: { operator: condition, value: val } })); 
            } 
             
            $('.field-panel-a').removeClass('open');
            
            $scope.redraw();
        };
                      
        that.addOperation = function(operation) {        
            that.statements.push(new Statement({ operation: operation }));
            
            $('.field-panel-a').removeClass('open');
            
            $scope.redraw();
        };

        that.removeStatement = function(i) {
            that.statements.splice(i, 1);
            
            $scope.redraw();
        };
        
       return that;
    };
    
    var Statement = function(data) {
        var that = {};
        
        that.alias = data.alias;
        that.condition = data.condition;
        that.operation = data.operation;
        that.scenarios = data.scenarios;
        
        that.createCaseScenario = function(nodeName, fieldName) { 
            var model = $scope.models.filter(function(x) { return x.name === nodeName})[0];

            that.tmp = { 
                fields: [], 
                conditions: $scope.conditions 
            };

            for(var i in model.fields) {
                if(model.fields[i].name !== fieldName) that.tmp.fields.push(model.fields[i].name);
            }

            $scope.redraw();
        };
        
        that.alterConditions = function(nodeName) {
            var model = $scope.models.filter(function(x) { return x.name === nodeName; })[0];
            var field = model.fields.filter(function(x) { return x.name === that.tmp.field; })[0];
            
            delete that.tmp.values;
            delete that.tmp.type;
                        
            if(field.type === 'date') {
                that.tmp.type = 'date';
                that.tmp.conditions = $scope.dateConditions;
            } else if(field.values && Object.keys(field.values).length > 0) {
                that.tmp.values = field.values;
            } else { 
                that.tmp.conditions = $scope.conditions;
            }
            
            $scope.redraw();
        };

        that.cancelCaseScenario = function() {        
            delete that.tmp;

            $scope.redraw();
        };

        that.saveCaseScenario = function() {                
            if(!that.scenarios) that.scenarios = [];

            var scenario = angular.copy(that.tmp);
            
            scenario.condition = that.tmp.values && Object.keys(that.tmp.values).length > 0 ? '=' : that.tmp.condition;

            delete scenario.fields;
            delete scenario.conditions;
            
            that.scenarios.push(scenario);
            
            delete that.tmp;

            generateDisplay();
        };

        that.removeCaseScenario = function(i) {
            that.scenarios.splice(i, 1);

            if(that.scenarios.length === 0) delete that.scenarios;

            generateDisplay();  
        };
        
        
        return that;
    };
    
    // misc functions    
    $scope.humanizeValue = function(values, val) {
        return values[val];
    };
    
    $scope.formatDate = function(date) {
        return moment.utc(date).format('D/M/YY HH:mm');
    };
    
    $scope.prettifyCol = function(name) {
        return name.replace('_', ' ');
    }
    
    $scope.changeTab = function(tab) {
        $scope.tab = tab;
    };
    
    $scope.redraw = function() {        
        $timeout(drawAssocs, 0);
    };
    
    // model operations 
    $scope.getModelAssociation = function(model1, model2) {
        for(var i in $scope.models) {
            var model = $scope.models[i];

            if(model.name !== model1) continue;
            
            for(var z in model.assocs) {
                var assoc = model.assocs[z];

                if(assoc.model === model2) return associations[assoc.type];
            }            
        }
    };
                     
    $scope.selectModel = function(model) {
        if(!model.available) return;
        
        var node = new Node({
            name: model.name,
            fields: model.fields
        });
        
        if(!query.root) {
            query.root = node;
        } else {
            var mirrors = selectedNode.assocs.filter(function(x) { return x.name === model.name;});
            
            if(mirrors.length > 0) {
                node = mirrors[0];
            } else {
                var assoc = $scope.getModelAssociation(selectedNode.name, model.name);
                
                node.operations = (assoc === 'one to many' || assoc === 'many to many');                
                selectedNode.assocs.push(node);
            }
        }
        
        generateDisplay();
        node.select();
    };
                      
    // query operations    
    $scope.clear = function() {
        selectedNode.selected = false;
        
        query = {};
        selectedNode = {};
        $scope.lvls = {};
        
        $scope.qID = null;
        
        filterModels();        
        $scope.redraw();
    };
    
    $scope.saveQuery = function() {        
        query.date = Date.now();
        selectedNode.selected = false;
        
        if(!query.id) query.id = parseInt(Math.random() * 1000000);
        
        query.name = $scope.qName;        
        $scope.queries[query.id] = query;
        
        $scope.qID = query.id;
        $scope.qName = query.name;
                
        localStorage.setItem("customQueries", JSON.stringify($scope.queries));     
        
        selectedNode.selected = true;
    };
    
    $scope.deleteQuery = function(id) {        
        delete $scope.queries[id || query.id];
        
        localStorage.setItem("customQueries", JSON.stringify($scope.queries));  
        
        $scope.clear();
    };
        
    $scope.selectQuery = function(q) {        
        $scope.clear();
        
        query.id = q.id;
        query.name = q.name;
        query.root = new Node(q.root);
        $scope.qID = query.id;
        $scope.qName = query.name;
                                
        generateDisplay();
        query.root.select();
    };
    
    $scope.describeQuery = function(q) {                     
        var lvls = {};
    
        function populateLvls(node, lvl) {
            if(!lvls[lvl]) lvls[lvl] = [];

            lvls[lvl].push(node);

            if(node.assocs.length > 0) {
                for(var i = 0;i < node.assocs.length;populateLvls(node.assocs[i], lvl+1),i++); 
            }
        };

        if(!q.root) return;

        populateLvls(q.root, 0);
        
        var cols = [];
        
        for(var i in lvls) {
            var html = '<div class="qd-lvl">';
           
            for(var y in lvls[i]) {
                var node = lvls[i][y];
                var conditions = [];
                var operations = [];
                
                for(var z in node.fields) {
                    if(node.fields[z].statements) {
                        for(var j in node.fields[z].statements) {
                            var statement = node.fields[z].statements[j];
                            
                            if(statement.condition) {
                                if(node.fields[z].type === 'date') {
                                    if(statement.condition.operator === 'between') {
                                        conditions.push(node.fields[z].name + '<span class="text-cmd"> between </span><span class="text-value">' + statement.condition.value1 + '</span><span class="text-cmd"> and </span><span class="text-value">' + statement.condition.value2 + '</span>');      
                                    } else {
                                        conditions.push(node.fields[z].name + '<span class="text-cmd"> during </span><span class="text-value">' + statement.condition.operator + '</span>');      
                                    }
                                } else {
                                    conditions.push((statement.operation ? statement.operation + '(' + node.fields[z].name + ')':  node.fields[z].name) + statement.condition.operator + '<span class="text-value">' + statement.condition.value + '</span>');
                                }
                            } else if(statement.operation) {
                                operations.push('<abbr>' + statement.operation + '</abbr>(' + node.fields[z].name + ')');
                            }
                        }   
                    }
                }
                                
                html += '<p class="qd-model">' + node.name + (operations.length > 0 ? '<small>[:' + operations.join(',')  + ']</small>' : '') + (conditions.length > 0 ? '<small><span class="text-cmd">where</span>(' + conditions.join(',')  + ')</small>': '') + '</p>';
            }
           
            html += '</div>';
            
            cols.push(html);
        }
        
        return $sce.trustAsHtml(cols.join('<span class="qd-split">-></span>')); // <i class="icon-arrow-right"></i>
    };
      
    $scope.submit = function() {     
        console.log(query);
        
        $scope.loading = true;
        
        $.ajax({ 
            url: location.pathname,
            type: 'POST',
            data: toJSON(query),
            success: function(data) {                     
                $scope.loading = false;
                $scope.tab = 1;
                
                $timeout(function() {
                    if(data.data.length > 0)  $scope.cols = Object.keys(data.data[0]);
                    
                    $scope.cols.splice($scope.cols.indexOf('pf'), 1);
                   
                    $scope.data = data.data;
                    $scope.sql = data.query;
                }, 0);                
            },
            error: function(err) {                
                $scope.loading = false;
            }
        });
    };
    
    // table data operations
    var lID = 0;
    
    $scope.setRefreshRate = function() {
        console.log($scope.refreshRate);
        
        clearInterval(lID);
        
        if($scope.refreshRate == 0) return;
        
        lID = setInterval($scope.submit, 1000 * $scope.refreshRate);
    }
    
    $scope.orderData = function(col) {
        if(!$scope.order) $scope.order = {};
        
        if($scope.order.col === col) $scope.order.dir = $scope.order.dir === '-' ? '+' : '-';
        else $scope.order = { col: col, dir: '-'};
    }
    
    $scope.changeTab(0);
}]);
</script>

<script id="demo-queries" type="text/json">
{"307960":{"root":{"name":"tickets","assocs":[{"name":"users","assocs":[{"name":"deposits","assocs":[],"fields":[{"name":"id","statements":[{"condition":{"operator":">","value":"2"},"operation":"count","$$hashKey":"object:294"}],"$$hashKey":"object:155"}],"mini":false,"selected":false,"operations":true,"$$hashKey":"object:152"},{"name":"withdraws","assocs":[],"fields":[{"name":"id","statements":[{"condition":{"operator":">","value":"2"},"operation":"count","$$hashKey":"object:310"}],"$$hashKey":"object:218"}],"mini":false,"selected":false,"operations":true,"$$hashKey":"object:215"}],"fields":[],"mini":false,"selected":false,"operations":false,"$$hashKey":"object:80"}],"fields":[{"name":"id","statements":[],"$$hashKey":"object:24"},{"name":"amount","statements":[],"$$hashKey":"object:25"},{"name":"date","type":"date","statements":[],"$$hashKey":"object:26"},{"name":"odd","statements":[],"$$hashKey":"object:27"},{"name":"status","values":{"0":"Pending","1":"Won","2":"Void","-4":"Rejected","-2":"Cancelled","-1":"Lost"},"statements":[],"$$hashKey":"object:28"},{"name":"type","values":{"1":"Single","2":"Multi","3":"System"},"statements":[],"$$hashKey":"object:29"}],"mini":false,"selected":false,"operations":false,"$$hashKey":"object:22"},"date":1447761955996,"id":307960},"688538":{"id":688538,"root":{"name":"users","assocs":[{"name":"tickets","assocs":[],"fields":[{"name":"amount","statements":[{"alias":"Tickets Lost","operation":"sum","scenarios":[{"fields":["id","date","odd","status","type"],"conditions":[">","<","=",">=","<=","like"],"field":"status","values":{"0":"Pending","1":"Won","2":"Void","-4":"Rejected","-2":"Cancelled","-1":"Lost"},"value":"-1","condition":"=","$$hashKey":"object:185"},{"field":"date","type":"date","condition":"last month","$$hashKey":"object:171"}],"$$hashKey":"object:119"}],"$$hashKey":"object:101"},{"name":"return","statements":[{"alias":"Tickets Won","operation":"sum","scenarios":[{"field":"status","values":{"0":"Pending","1":"Won","2":"Void","-4":"Rejected","-2":"Cancelled","-1":"Lost"},"value":"1","condition":"=","$$hashKey":"object:366"},{"field":"date","type":"date","condition":"last month","$$hashKey":"object:386"}],"$$hashKey":"object:146"}],"$$hashKey":"object:102"}],"mini":false,"selected":false,"operations":true,"$$hashKey":"object:36"}],"fields":[{"name":"id","statements":[],"$$hashKey":"object:24"},{"name":"username","statements":[],"$$hashKey":"object:25"},{"name":"balance","statements":[],"$$hashKey":"object:26"},{"name":"country","statements":[],"$$hashKey":"object:27"},{"name":"status","values":{"0":"UnConfirmed","1":"Active","-1":"Locked Out","-2":"Self Excluded","-3":"Self Deleted","-4":"Banned"},"statements":[{"condition":{"operator":"=","value":"1"},"$$hashKey":"object:79"}],"$$hashKey":"object:28"},{"name":"registration_date","type":"date","statements":[{"condition":{"operator":"between","value1":"2011-04-25","value2":"2015-11-02"},"$$hashKey":"object:92"}],"$$hashKey":"object:29"}],"mini":false,"selected":false,"operations":false,"$$hashKey":"object:22"},"date":1447753734729}}
</script>