<?php 
include("include/db.php");
include("include/utility.php");
include("include/session.php");
define("APP_START", 1);
include("modules/dashboard/ajax.php");
$page="index"
//ALTER TABLE `sales` ADD `is_printed` INT(1) NOT NULL DEFAULT '0' AFTER `net_price`;
?>
<?php include("include/header.php");?>		
   	<div class="page-header">
        <h1 class="title">Dashboard</h1>
        <ol class="breadcrumb">
            <li class="active">Welcome to <?php echo $site_title?> Dashboard.</li>
        </ol>
        <!-- Start Page Header Right Div -->
        <div class="right">
            <div class="btn-group" role="group" aria-label="...">
                <a href="<?php echo $site_url?>" class="btn btn-light" target="_blank" title="View Website">View Website</a>              
            </div>
        </div>
        <!-- End Page Header Right Div -->
    </div>
    <div ng-app="dashboard" ng-controller="dashboardController" id="item-row">
    	<div class="item-menu clearfix">
        	<div class="row">
                <ul>
                    <li ng-repeat="category in categories" class="col-md-4 col-xs-4" ng-class="{'active': active_category==$index}" ng-click="change_active_category($index)"><a href="#">{{ category.title }}</a></li>
                </ul>
            </div>
        </div>
        <div ng-repeat="category in categories" ng-show="active_category==$index" class="item-col">
            <div ng-repeat="product_category in category.products" class="row">
            	<div class="col-lg-12">
                	<h3>{{ product_category.title }}</h3>
                </div>
                <div ng-repeat="product in product_category.products" class="col-md-2 col-sm-3 col-xs-6" ng-class="{'active': order_item(product.id)}">
                    <div class="item">
                        <div class="item-img">
                            <img src="{{ product.image }}" />
                            <div class="item-img-hover">
                            	<form>
                                    <h3>
                                        <span class="dec" ng-click="order_item_remove(product)">-</span>
                                            <input value="{{ order_item(product.id) }}" data-variationid="0" type="text">
                                        <span class="inc" ng-click="order_item_add(product)">+</span>
                                    </h3>
                                </form>
                            </div>
                        </div>    
                        <div class="item-text">
                            <h2>{{ product.title }}</h2>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
        <div id="cart" class="panel-body table-responsive">
        	<table width="100%" class="table table-hover list">
            	<thead>
                    <tr>
                        <th width="5%">S.N</th>
                        <th width="20%">Time</th>
                        <th width="40%">Items</th>
                        <th width="10%" class="text-right">Total Items</th>
                        <th width="10%" class="text-right">Price</th>
                        <th width="15%" class="text-center">Actions</th>
                    </tr>
                </thead>
            	<tr ng-repeat="order in orders">
                	<td>{{ $index+1 }}</td>
                	<td>{{ order.date }}</td>
                    <td>
                    	<ul>
                            <li ng-repeat="item in order.items">{{ item.quantity }} x {{ item.title }}</li>
                        </ul>
                    </td>
                	<td class="text-right">{{ order_total_items(order) }}</td>
                	<td class="text-right">{{ order_total(order)|currency:'Rs. ':0 }}</td>
                	<td class="text-center">
                    	<a href="" title="Print" ng-click="print_receipt(order.id)"><i class="fa fa-print" aria-hidden="true"></i></a>
                    </td>
                </tr>
            	<tr ng-repeat="item in items">
                	<th colspan="5" class="text-right">{{item.name}}:</th>
                	<th colspan="1" class="text-right">{{ item.quantity|currency:"":"" }}</th>
                </tr>
            	<tr>
                	<th colspan="5" class="text-right">Total Amount:</th>
                	<th colspan="1" class="text-right">{{ orders_total(orders)|currency:'Rs. ':0 }}</th>
                </tr>
            </table>
        </div>
        <table width="100%" class="table table-hover list">
            <thead>
                <tr>
                    <th width="5%">S.N</th>
                    <th width="29%">Time</th>
                    <th width="45%">Details</th>
                    <th width="15%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tr ng-repeat="expense in expenses">
                <td>{{ $index+1 }}</td>
                <td>{{ expense.datetime_added }}</td>
                <td>{{ expense.details }}</td>
                <td class="text-right">{{ expense.amount }}</td>
            </tr>
            <tr>
                <td>1</td>
                <td colspan="3">
                	<form class="form-horizontal expense-form">
                    	<div class="row">
                            <div class="col-md-4">New Expense</div>
                            <div class="col-md-4 padding-none"><textarea class="form-control" placeholder="Details" ng-model="new_expense.details"></textarea></div>
                            <div class="col-md-3"><input type="text" ng-model="new_expense.amount" id="amount" class="form-control text-right" placeholder="Amount"></div>
                            <div class="col-md-1 text-right"><input type="button" class="btn btn-default btn-l" value="Save" ng-click="add_expense()"></div>
                        </div>
                    </form>
                </td>
            </tr>
            <tr bgcolor="#EFF7FF">
                <th colspan="3" class="text-right">Total Expense Today:</th>
                <th colspan="1" class="text-right">{{ expense_total()|currency:'Rs. ':0 }}</th>
            </tr>
            <tr bgcolor="#EFF7FF">
                <th colspan="3" class="text-right">Total Cash:</th>
                <th colspan="1" class="text-right">{{  (orders_total(orders)-expense_total())|currency:'Rs. ':0 }}</th>
            </tr>
        </table>
        <div class="nav-bar bg-info color5-bg" ng-show="order_total_items()>0">
            <div class="col-md-1 order">
                <h2>Order</h2>
            </div>
            <div class="col-md-5 items-margin">
                <strong>Items Name</strong>
                <ul>
                    <li ng-repeat="item in new_order.items">{{ item.quantity }} x {{ item.title }}</li>
                </ul>
            </div>
            <div class="col-md-2 text-right total-item">
                <strong>Total Items</strong>
                <ul>
                    <li>{{ order_total_items() }}</li>
                </ul>
            </div>
            <div class="col-md-2 text-right total-item">
                <strong>Total Price</strong>
                <ul>
                    <li>{{ order_total()|currency:'Rs. ':0 }}</li>
                </ul>
            </div>
            <div class="col-md-2 text-right"><a href="" class="cart-button" ng-click="save_order()">Place Order</a></div>
        </div>
        
    </div>
</div>
<?php include("include/footer.php");?>