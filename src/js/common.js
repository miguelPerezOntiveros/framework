doSidebarProjects = function(){
	console.log('in do sidebar projects from common');
	$.get('sidebar_projects.php?project=maker_mike', function(current){
		try{
			current = JSON.parse(current);
			var old = [];
			$.each($('.sidebar_projects li'), function(i, e){
				old.push($(e).text())
			});

			// Delete commons between old and current
			// Old will end up with the ones to be deleted
			// Current will end up with the ones to add
			var commonBetweenArrays =[];
			$.each(old, function(i, e){
				if(current.includes(e)){
					commonBetweenArrays.push(e);
				}
			})
			$.each(commonBetweenArrays, function(i, e){
				current.splice(current.indexOf(e), 1);
				old.splice(old.indexOf(e), 1);
			})

			// console.log("to be deleted: %o", old);
			// console.log("to be added: %o", current);

			// Delete old entries
			$.each(old, function(i, e){
				$('.sidebar_projects [data-project='+e+']').toggle('slow');
				setTimeout(function(){
					$('.sidebar_projects [data-project='+e+']').remove();
					console.log(e + ' removed.');
				}, 2000);
			})

			// Add new entries
			$.each(current, function(i, e){
				$('.sidebar_projects').append('<li data-project="'+e+'" class="'+(window._projectName == e? 'active':'')+'" style="display: none;"><a href="/projects/'+e+'/admin/index.php?sidebar=1">'+e+'</a></li>');
				$('.sidebar_projects [data-project='+e+']').toggle('slow');
			})
		} catch(e){
			$('a[href="#projectsSubmenu"]').addClass('disabled');
		}
	});
	if(window.location.href.includes('/login.php')){
		$('#menu_page a').prop("onclick", null).off("click");
		$('#menu_portlet a').prop("onclick", null).off("click");
		$('#menu_theme a').prop("onclick", null).off("click");
		$('#menu_user_type a').prop("onclick", null).off("click");
		$('#menu_user a').prop("onclick", null).off("click");

		$('#menu_page a').addClass('disabled');
		$('#menu_portlet a').addClass('disabled');
		$('#menu_theme a').addClass('disabled');
		$('#menu_user_type a').addClass('disabled');
		$('#menu_user a').addClass('disabled');
	}
}

doModal = function(intention, message, millis){
	if(intention == 'error')
		$('.modal_body').html('<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-circle"></i>&nbsp;'+message+'</div>');
	if(intention == 'success')
		$('.modal_body').html('<div class="alert alert-success" role="alert"><i class="fas fa-check-circle"></i>&nbsp;'+message+'</div>');
	
	$("#feedbackModal").modal("show");
	console.log(intention + ': ' + message);
	setTimeout(function(){
		$("#feedbackModal").modal("hide");
	}, millis);
}