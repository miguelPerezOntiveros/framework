doSidebarProjects = function(){
	console.log('in do sidebar projects from common');
	$.get('sidebar_projects.php', function(current){
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
	});
}