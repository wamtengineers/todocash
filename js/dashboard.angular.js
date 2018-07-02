angular.module('dashboard', ['ngAnimate', 'angularMoment']).controller('dashboardController', 
	function ($scope, $http, $interval, $filter) {
		$scope.categories = [];
		$scope.orders = [];
		$scope.items = {};
		$scope.expenses = [];
		$scope.processing = false;
		$scope.new_expense = {
			"details": "",
			"amount": 0	
		};
		$scope.active_category = 0;
		$scope.new_order = {
			"id": "",
			"items": []	
		};
		
		$scope.change_active_category = function( index ) {
			$scope.active_category = index;
		}
		
		$scope.order_item = function( product_id ) {
			if( $filter('filter')($scope.new_order.items, {id: product_id}).length > 0 ) {
				return $filter('filter')($scope.new_order.items, {id: product_id})[0].quantity;
			}
			else{
				return 0;
			}
		}
		
		$scope.order_item_add = function( product ) {
			if( $filter('filter')($scope.new_order.items, {id: product.id}).length > 0 ) {
				$filter('filter')($scope.new_order.items, {id: product.id})[0].quantity++;
			}
			else{
				$scope.new_order.items.push({id: product.id, title: product.title, unit_price: product.unit_price, quantity: 1});
			}
		}
		$scope.order_item_remove = function( product ) { 
		  	if( $filter('filter')($scope.new_order.items, {id: product.id}).length > 0 ) {
				$filter('filter')($scope.new_order.items, {id: product.id})[0].quantity--;
				if( $filter('filter')($scope.new_order.items, {id: product.id})[0].quantity == 0 ){
					index = jQuery.map( $scope.new_order.items, function( obj, i ) {
						if( obj.id == product.id ) {
							return i;
						}
					});
					$scope.new_order.items.splice( index, 1 );
				}
			}
		}
		$scope.save_order = function () {
			if( $scope.processing == false ){
				$scope.processing = true;
				data = {action: 'save_order', order: JSON.stringify( $scope.new_order )};
				$scope.wctAJAX( data, function( response ){
					$scope.processing = false;
					if( response.status == 1 ) {
						$scope.orders.unshift( response.order );
						$scope.print_receipt( response.order.id )
						$scope.new_order = {
							"id": "",
							"items": []	
						};
						$scope.items_total();
					}
					else{
						alert(response.message);
					}
				});
			}
		}
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctRequest = {
				method: 'POST',
				url: 'index.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
					var str = [];
					for(var p in obj){
						str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
					}
					return str.join("&");
				},
				data: wctData
			}
			$http(wctRequest).then(function(wctResponse){
				wctCallback(wctResponse.data);
			}, function () {
				console.log("Error in fetching data");
			});
		}
		$scope.wctAJAX( {action: 'get_parent_categories'}, function( response ){
			$scope.categories = response;
			for( var i=0; i<$scope.categories.length; i++ ) {
				$scope.wctAJAX( {action: 'get_products', category_id: $scope.categories[ i ].id, category_index: i }, function( response ){
					$scope.categories[ response.category_index ].products = response.products;
				});
			}
		});
		$scope.wctAJAX( {action: 'get_orders'}, function( response ){
			$scope.orders = response;
			$scope.items_total();
		});
		$scope.wctAJAX( {action: 'get_expense'}, function( response ){
			$scope.expenses = response;
		});
		$scope.add_expense = function(){
			if( $scope.processing == false ) {
				if( $scope.new_expense.details == "" || $scope.new_expense.amount <= 0 ){
					alert("Enter Details and Amount.");
				}
				else{
					$scope.processing = true;
					$scope.wctAJAX( {action: 'add_expense', expense: JSON.stringify($scope.new_expense)}, function( response ){
						$scope.processing = false;
						if( response.status == 1 ) {
							$scope.new_expense = {
								"details": "",
								"amount": 0	
							};
							$scope.expenses.unshift(response.expense);
						}
						else{
							alert(response.message);
						}
					});	
				}
			}
		}
		$scope.expense_total = function() {
			total = 0;
			for( i = 0; i < $scope.expenses.length; i++ ) {
				total += parseFloat($scope.expenses[ i ].amount);
			}
			return total;
		}
		$scope.order_total_items = function( order ) {
			if( typeof order === "undefined" ){
				order = $scope.new_order;
			}
			total = 0;
			for( i = 0; i < order.items.length; i++ ) {
				total += order.items[ i ].quantity;
			}
			return total;
		}
		$scope.order_total = function( order ) {
			if( typeof order === "undefined" ){
				order = $scope.new_order;
			}
			total = 0;
			for( i = 0; i < order.items.length; i++ ) {
				total += (parseFloat(order.items[ i ].unit_price) * parseFloat(order.items[ i ].quantity));
			}
			return total;
		}
		$scope.orders_total_items = function( orders ) {
			total = 0;
			for( i = 0; i < orders.length; i++ ) {
				for( j = 0; j < orders[i].items.length; j++ ) {
					total += orders[ i ].items[ j ].quantity;
				}
			}
			return total;
		}
		$scope.items_total = function() {
			$scope.items = {};
			for( i = 0; i < $scope.orders.length; i++ ) {
				for( j = 0; j < $scope.orders[i].items.length; j++ ) {
			 		if( typeof $scope.items['id_'+$scope.orders[i].items[ j ].id] === 'undefined' ) {
			  			$scope.items['id_'+$scope.orders[i].items[ j ].id] = {
			   				"name": $scope.orders[ i ].items[ j ].title,
			   				"quantity": 0
			  			};
			 		}
			 		$scope.items['id_'+$scope.orders[i].items[ j ].id].quantity += $scope.orders[ i ].items[ j ].quantity;
				}
		   	}
		}
		$scope.orders_total = function( orders ) {
			total = 0;
			for( i = 0; i < orders.length; i++ ) {
				for( j = 0; j < orders[i].items.length; j++ ) {
					total += (parseFloat(orders[ i ].items[ j ].unit_price) * parseFloat(orders[ i ].items[ j ].quantity));
				}
			}
			return total;
		}
		$scope.order_total = function( order ) {
			if( typeof order === "undefined" ){
				order = $scope.new_order;
			}
			total = 0;
			for( i = 0; i < order.items.length; i++ ) {
				total += (parseFloat(order.items[ i ].unit_price) * parseFloat(order.items[ i ].quantity));
			}
			return total;
		}
		$scope.print_receipt = function( id ) {
			$("<iframe>")
				.hide()
				.attr("src", "index.php?tab=print_receipt&id="+id)
				.appendTo("body"); 
		}
	}
)