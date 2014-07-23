
	/* All Custom Javascript Functions */
	
    function recordshow(recordid) {
	$('.listrecord').empty();
	$('.listrecord').html('<br><center><img src="img/cool_loading_bar.gif"/></center>');
	$('.listrecord').load('list_record.php?recid='+recordid);
	};
	function aff(affid,pagename) {
	  $('#'+pagename+'_content').empty();
	  $('#'+pagename+'_content').html('<br><center><img src="img/cool_loading_bar.gif"/></center>');
	  $('#'+pagename+'_content').load(pagename+'.php?affid='+affid); 
	  if(pagename='view_notes'){
	    $('.submitnote_holder').html('<button class="btn btn-primary submitnote">Submit Note</button>');
	  }
	};
	function deleteNote(notedate,recid){

			  $.ajax({
			  type: "POST",
			   url: "view_notes.php",
			   data: "deletenote=1&recid="+recid+"&notedate="+notedate,
			   success: function(msg){
			     $("#msg_view_notes").append('<p class="field success alert">'+msg+'</p>');
				 
				 $('#view_notes_content').empty();
	             $('#view_notes_content').html('<br><center><img src="img/cool_loading_bar.gif"/></center>');
	             $('#view_notes_content').load('view_notes.php?affid='+recid); 
				 recordshow(recid);
			   }
			  }); 
			  
			  
	};
	function closemsg(msgclass){
			$('.'+msgclass).empty();
	};
	function newpage(pagediv, pageloadname, menupieceid){
	   $('.mentog').hide();
	   $( ".menupieces" ).removeClass("active");
	   $( "#"+menupieceid).addClass("active");
	   $('.pagepieces').empty();
	   $('#'+pagediv).html('<div class="active span12"><center><img src="img/loading_bar_animated.gif"/></center></div>');
	   $('#'+pagediv).load(pageloadname+'.php');
	   
	};
	
	function emptypagedivs(){
	$('.pagepieces').empty();
	}
	
	function assign_bucket(bucketype,username){  
      
	  $.ajax({
	  type: "POST",
	   url: "buckets_assign.php",
	   data: "bucketype="+bucketype+"&un="+username,
	   success: function(msg){

		alert( msg ); //Anything you want
		  location.reload();

	   }
	  });
  
    }; 
	
	function assign_bucket_rec(username,recid){  
      
	  $.ajax({
	  type: "POST",
	   url: "buckets_assign.php",
	   data: "un="+username+"&recid="+recid,
	   success: function(msg){

	   }
	  });
  
    }; 
	
	function admin_assign_bucket(bucketype,username){  
      
	  $.ajax({
	  type: "POST",
	   url: "buckets_assign.php",
	   data: "bucketype="+bucketype+"&un="+username,
	   success: function(msg){

		//alert( msg ); //Anything you want
		 // location.reload();

	   }
	  });
  
    }; 
	
	function admin_assign_bucket_rec(username,recid){  
      
	  $.ajax({
	  type: "POST",
	   url: "buckets_assign.php",
	   data: "un="+username+"&recid="+recid,
	   success: function(msg){

	   }
	  });
  
    };
	
	
	/* All custom onclick events */
	$(".search_records").click( function () {
	
    var recordid = $("#hidden_search_ID").val();    
	$('.listrecord').empty();
	$('.listrecord').html('<br><center><img src="img/cool_loading_bar.gif"/></center>');
	$('.listrecord').load('list_record.php?recid='+recordid);
	
    });
	
	$("#search_records").change( function () {
    var memberid = $("#search_records").val();
    var un = $("#un_hidden").val();
	recordshow(memberid);
	assign_bucket_rec(un,memberid)
	

   });
	
	$('.logout').click(function() {
	  $('#logoutform').submit();
	});
	
	$('.listrecord').on('click', '#closerec', function () {
	  $('.listrecord').empty();
    });
	
	
	
	
	/* All Javascript onload or onready events */
	 $(document).ready(function() {
	  //$('#mtable').load('records_table.php');
	  //$('#buckets').load('buckets.php');
	  $('.mentog').hide();
	  $('#homepiece').load('home_piece.php');
	 });

		
	 
	 $(document).ready(function() { $("#search_records").select2(
	 {
	  placeholder: "",
	  allowClear: false,
	  minimumInputLength: 4
	 }); 
	 });
 
	 $(document).ready(function() {
	
	 //$('#search_records').select2('open');
	  var q = $(".select2-input").val();
      $("#search_records").load("search_posts_list.php?q="+q);
    });

	$(window).load(function(){
	$('#csv').change(function(e) {
		var filepath = this.value;
		var m = filepath.match(/([^\/\\]+)$/);
		var filename = m[1];
		$('#filename').text(' Uploading... ' + filename);
		setTimeout((function(form) {
			return function() {
				form.submit();
			}
		})(this.form), 1000);
	});
	});
	
	$(document).ready(function() {
			
			$('#search_da_list').typeahead({
			 source: function(query, process) {
				var $url ='search_posts_list.php?json=1&q='+query;
				var $items = new Array;
				$items = [""];
				$.ajax({
					url: $url,
					dataType: "json",
					type: "GET",
					success: function(data) {
						console.log(data);
						$.map(data, function(data){
							var group;
							group = {
								id: data.id,
								name: data.name,                            
								toString: function () {
									return JSON.stringify(this);
									//return this.app;
								},
								toLowerCase: function () {
									return this.name.toLowerCase();
								},
								indexOf: function (string) {
									return String.prototype.indexOf.apply(this.name, arguments);
								},
								replace: function (string) {
									var value = '';
									value +=  this.name;
									if(typeof(this.level) != 'undefined') {
										value += ' <span class="pull-right muted">';
										value += this.level;
										value += '</span>';
									}
									return String.prototype.replace.apply('<div style="padding: 4px; font-size: 1.0em;">' + value + '</div>', arguments);
								}
							};
							$items.push(group);
						});

						process($items);
					}
				});
			},
			property: 'name',
			items: 10,
			minLength: 2,
			updater: function (item) {
				var item = JSON.parse(item);
				console.log(item.name); 
				$('#hidden_search_ID').val(item.id);       
				return item.id;
			}
			});
			});
			
	/* Save for later custom scripts */
    /*
	$(document).ready(function() {
	  $.ajaxSetup({ cache: false }); // This part addresses an IE bug.  without it, IE will only load the first number and will never refresh
	  setInterval(function() {
		$('#mtable').load('records_table.php');
	  }, 30000); // the "3000" 
	});
	*/	
	