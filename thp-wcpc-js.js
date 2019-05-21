jQuery(document).ready(function(){
	jQuery('#thp-check-form').hide();
});

jQuery('#thp-check-file').submit(function( e ){
	
    e.preventDefault();
	
    var csvpath = jQuery('#thp-csv-path').val();
	jQuery('#thp-csvpath-hidden').val(csvpath);
	
	jQuery.ajax({
		url: csvpath,
		type:'HEAD',
		error: function()
		{
			alert("The file you specify does not exist!");
		},
		success: function()
		{
			//alert("File exists!");
			jQuery.ajax({
				url : ajaxurl,
				type : 'post',
				data : {
					action : 'thp_check_file',
					thp_path : csvpath
				},
				success : function( data ) {
					
					var obj = JSON.parse(data);
					var csvheader = '';
					
					for (var i = 0, len = obj.length; i < len; i++) {
						csvheader += '<option value="' + i + '">' + obj[i] + '</option>';
					}
					
					jQuery('#thp-check-form').show();
					jQuery('#thp-csv-columns').html(csvheader);
				}
			});
		}
	});

});

jQuery('#thp-check-form').submit(function( e ){
	
    e.preventDefault();
	
    var wcfield = jQuery('#thp-wc-columns option:selected').val();
	var csvfield = jQuery('#thp-csv-columns option:selected').val();
	var csvpathhidden = jQuery('#thp-csvpath-hidden').val();
		
    jQuery.ajax({
        url : ajaxurl,
        type : 'post',
        data : {
            action : 'thp_wcpc_run_check',
            thp_wcfield : wcfield,
			thp_csvfield : csvfield,
			thp_csvpath_hidden : csvpathhidden
        },
        success : function( data ) { 
            jQuery('#thp-results').html(data);
        }
    });

});
//var wcResult = thp_wcpc_vars.wcresults;

//if (wcResult) {
//	document.getElementById("thp-results").innerHTML = wcResult;
//}