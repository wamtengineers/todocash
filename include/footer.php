<div id="footer" class="bottom_round_corners">
	<div id="footer_content">
    	<address>&copy; <?php echo date("Y");?> - <?php echo $site_title; ?> Admin Panel</address>
    </div>
</div>
</div>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<link href="js/fancybox/jquery.fancybox.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/moment/moment.min.js"></script>
<script type="text/javascript" src="js/full-calendar/fullcalendar.js"></script>
<link href="js/chosen/chosen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/chosen/chosen.jquery.js"></script>
<script type="text/javascript" src="js/js.js"></script>
<script type="text/javascript" src="js/date-range-picker/daterangepicker.js"></script>
<script type="text/javascript" src="js/jQuery-Scanner-Detection-master/jquery.scannerdetection.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<script>
$(document).ready(function(){
	$('.date-picker').daterangepicker({ singleDatePicker: true, format: 'DD/MM/YYYY'});
	$('.fancybox_iframe').fancybox({type: 'iframe'});
	if(window.location != window.parent.location){
		$("body").addClass("popup-content");
	}
	$('.date-timepicker').datetimepicker({
		"format": 'DD/MM/YYYY hh:mm A'
	});
});
</script>
<?php include("include/upload_center.php");?>
<script>
$(document).ready(function(){
	$("#supplier_id").change(function(){
		$supplier = $(this).find("option:selected");
		if( $supplier.val() != "" ){
			$("#supplier_name").val($supplier.data("supplier_name"));
			$("#phone").val($supplier.data("phone"));
			$("#address").val($supplier.data("address"));
		}
	}).change();
	$("#customer_id").change(function(){
		$customer = $(this).find("option:selected");
		if( $customer.val() != "" ){
			$("#customer_name").val($customer.data("customer_name"));
			$("#phone").val($customer.data("phone"));
			$("#address").val($customer.data("address"));
		}
	}).change();
//	$("select.item_select").chosen({width: "100%"});
});
</script>
<script>
$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	avgTimeByChar: 100, // it's not a barcode if a character takes longer than 100ms
	onComplete: function(barcode, qty){
   	 	var barcode = parseInt(barcode);
		if( barcode > 0 ){
			$item_found = false;
			$(".item_select").each(function(){
				$this = $( this );
				
				if( !$item_found && $this.val() == barcode ) {
					$quantity = $this.parents('.sale_item').find(".quantity");
					$quantity.val(parseInt($quantity.val())+1);
					$quantity.trigger("change");
					
					$item_found = true;
				}
				if( !$item_found && $this.val() == "" ) {
					$option = $this.find("option[value="+barcode+"]");
					if( $option.length > 0 ) {
						$option.prop( "selected", true );
						$this.trigger( 'change' );
						$item_found = true;
					}
				}
			});
			if( !$item_found ) {
				$(".sale_item").last().find(".add_list_item").trigger("click");
				
				setTimeout( function(){
					$this = $(".sale_item").last().find(".item_select");
					$option = $this.find("option[value="+barcode+"]");
					if( $option.length > 0 ) {
						$option.prop( "selected", true );
						$this.trigger( 'change' );
						$item_found = true;
					}
				}, 100);
			}
		}
    }
});
$(document).ready(function() {
	var pressed = false;
	var chars = [];
	  //alert( $(window).scannerDetection());
	$(window).keypress(function(e) {
		return;
	//	alert(e.srcElement);
		//alert(JSON.stringify(Object.values(e)));
		Object.keys(e).forEach(function(key,index) {
			//alert(key);
		});
		//return;
		if (e.which >= 48 && e.which <= 57) {
			chars.push(String.fromCharCode(e.which));
		}
		if (pressed == false) {
			pressed = true; // <- SET THIS BEFORE SETTING THE TIMER
			t=setTimeout(function(){ // = 10) {
				var barcode = parseInt(chars.join(""));
				if( barcode > 0 ){
					$item_found = false;
					$(".item_select").each(function(){
						$this = $( this );
						if( !$item_found && $this.val() == barcode ) {
							$quantity = $this.parents('.sale_item').find(".quantity");
							$quantity.val(parseInt($quantity.val())+1);
							$quantity.trigger("change");
							$item_found = true;
						}
						if( !$item_found && $this.val() == "" ) {
							$option = $this.find("option[value="+barcode+"]");
							if( $option.length > 0 ) {
								$option.prop( "selected", true );
								$this.trigger( 'change' );
								$item_found = true;
							}
						}
					});
					if( !$item_found ) {
						$(".sale_item").last().find(".add_list_item").trigger("click");
						setTimeout( function(){
							$this = $(".sale_item").last().find(".item_select");
							$option = $this.find("option[value="+barcode+"]");
							if( $option.length > 0 ) {
								$option.prop( "selected", true );
								$this.trigger( 'change' );
								$item_found = true;
							}
						}, 100);
					}
					clearTimeout(t);
					chars = [];
					pressed = false;
				}
			},100);
		}
	});
});
</script>
<script src="js/jquery.UrduEditor.js" type="text/javascript"></script>    
<script>
$(document).ready(function() {    
    $('#name_in_urdu_text').UrduEditor("14px");   
	$(".sorting").hover(function(){
	  $icon=$(this).find(".sort-icon i");
	  $icon.removeClass("fa-angle-"+$icon.data("hover_out")).addClass("fa-angle-"+$icon.data("hover_in"))
	},function(){
	  $icon=$(this).find(".sort-icon i");
	  $icon.addClass("fa-angle-"+$icon.data("hover_out")).removeClass("fa-angle-"+$icon.data("hover_in"))
	});
	$(".reset_search").click(function(){
		$form = $(this).parents("form");
		$form.find('input[type=text], select, textarea').val('');
		$form.submit();
	});
	$(".barcode_print_button").click(function(e){
		e.preventDefault();
		$a = $(this);
		$copies = prompt("Number of Copies (Multiple of 2)", "1");
		if($copies > 0)
		{
			$("<iframe>")
			.hide()
			.attr("src", $a.attr("href")+"&copies="+$copies)
			.appendTo("body"); 
		}
	});
}); 
</script>  
<script type="text/javascript" src="js/angular.min.js"></script>
<script type="text/javascript" src="js/angular-animate.js"></script>
<script src="assets/js/moment.min.js"></script>
<script src="js/angular-moment.min.js"></script>
<script type="text/javascript" src="js/dashboard.angular.js"></script>
</body>
</html>