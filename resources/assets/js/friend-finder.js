var fbFriendFind = (function($){
	/**
	 * All variables sored in a data object
	 * @type {Object}
	 */
	var data = {
		host : window.location.host,
		href : window.location.href,
		appId : '1420267864675222',
		queryCount : 0,
		user : {
			loggedIn : false,
			id : null,
			name : null,
			first_name : null,
			img : null
		},
		friends : {}
	},
	url = window.location.href,
	slug = $('article#quiz').data('name');

	// Localhost Testing
	if (data.host !== 'ghostedonfox.com') data.appId = '167628857133825';

	/**
	 * Check if user is logged in on page load
	 */
	$(document).ready(function(){
		$('span.top').fitText(.9);
		$('span.bottom').fitText(1.05);
		
		$.ajaxSetup({ cache: true });
		$.getScript('//connect.facebook.net/en_US/sdk.js', function(){
			FB.init({
				appId	: data.appId,
				status  : true,
				version : 'v2.9',
			});
			checkLoginStatus();
		});
	});

	/**
	 * Check login status with FB API
	 * Change data.user.loggedIn to bool
	 */
	var checkLoginStatus = function(){
		FB.getLoginStatus(function(response){
			data.user.loggedIn = (response.status === 'connected');
			if (data.user.loggedIn === true) {
				$('#loginbutton').removeAttr('disabled').removeClass('facebook').text('FIND OUT');
			} else {
				$('#loginbutton').removeAttr('disabled');
			}
		});
	}
	/**
	 * Use FB API for user login
	 */
	var login = function(){
		FB.login(function(response){
			if (response.authResponse) {
				FriendFind.start();
			} else {
				console.log('User Login Failed');
				// $('#loginbutton').removeAttr('disabled').html('<i class="fa fa-facebook" aria-hidden="true"></i> LOG IN TO FIND OUT');
			}
		},
		{ scope:'user_posts,user_photos,email,public_profile' });
	}

	/**
	 * Use FB API to share page
	 */
	$('body').on('click', '#sharequizbutton', function(){
		var finalShareImg = $(this).data('img');
		if (data.user && data.user.id !== null) {
			FB.ui({
				method: 'share',
				display: 'popup',
				href: 'http://'+data.host+'/share/'+data.user.id,
			}, function (response) {
				console.log(response);
			});
		} else {
			alert('something went wrong');
		}
	});

	/**
	 * Check if user is logged in
	 * If true: start FriendFind
	 * If false: login()
	 */
	// $('#canvas').click(function(){
	$('#loginbutton').click(function(){
		$('#loginbutton').attr('disabled', 'disabled').text('LOADING...');
		if(data.user.loggedIn){
			FriendFind.start();
		} else {
			login();
		}
		return false;
	});

	/**
	 * Put all FriendFind functions in one object
	 * @return {object}
	 */
	var FriendFind = (function(){
		var start = function(){
			FB.api('/me', {'fields':['id','first_name','name','picture.height(400).width(400)']}, function(response) {
				data.user.id = response.id;
				data.user.name = response.name;
				data.user.first_name = response.first_name;
				data.user.img = response.picture.data.url;
				// console.log(data.user);
				checkPosts();
			});
		}

		var addToFriends = function(user, rating){
			if (user.id !== data.user.id) {
				// data.friends
				if ( data.friends[user.id] ) {
					data.friends[user.id]['rating'] += rating;
				} else {
					data.friends[user.id] = {
						id : user.id,
						name : user.name,
						rating : rating
					}
				}
			}
		}

		var checkPosts = function(){
			FB.api("/me/posts?fields=likes.limit(1000),comments.limit(1000),with_tags,from,to&limit=25",
				"GET",
				function(response) {
					// Getting info
					if (response && response.data){
						for (var i = 0; i < response.data.length; i++) {
							var post = response.data[i];
							if (post.likes) {
								// 1
								for (var j = 0; j < post.likes.data.length; j++) {
									var user = post.likes.data[j];
									if (user.id)
										addToFriends(user, 1);
								}
							}
							if (post.comments) {
								// 2
								for (var j = 0; j < post.comments.data.length; j++) {
									var comment = post.comments.data[j];
									var user = comment.from;
									if (comment.id)
										addToFriends(user, 2);
								}
							}
							if (post.to) {
								// 3
								for (var j = 0; j < post.to.data.length; j++) {
									var user = post.to.data[j];
									if (user.id)
										addToFriends(user, 3);
								}
							}
							if (post.with_tags) {
								// 3
								for (var j = 0; j < post.with_tags.data.length; j++) {
									var user = post.with_tags.data[j];
									if (user.id)
										addToFriends(user, 3);
								}
							}
						}
					}
					checkPhotos();
				}
			);
		}

		var checkPhotos = function(){
			FB.api('me/photos?fields=from&limit=10', 'GET', function(response){
				if (response && response.data) {
					for (var i = 0; i < response.data.length; i++) {
						var post = response.data[i];
						if (post.from.id !== data.user.id)
							addToFriends(post.from, 2);
					}
				}
				getHighestRated();
			});
		}

		var getHighestRated = function(){
			var allFriends = data.friends;
			var sorted = _.sortBy(allFriends, ['rating']);
			_.reverse(sorted);
			sorted = sorted.slice(0,5);

			var selectFriends = [],
				numNeeded = 1;

			while (selectFriends.length < numNeeded) {
				var arrIndex = Math.floor( Math.random() * sorted.length );
				selectFriends.push(sorted[arrIndex]);
				sorted.splice(arrIndex, 1);
			}

			getFriendInfo(selectFriends);
		}

		var getFriendInfo = function(friends){
			var bestFriendArr = [];

			for (var i = 0; i < friends.length; i++) {
				var user = friends[i];
				FB.api(user.id, {'fields':['id','first_name','name','picture.height(400).width(400)']}, function(response) {
					resUser = {
						id : response.id,
						name : response.name,
						first_name : response.first_name,
						img : response.picture.data.url
					}
					bestFriendArr.push(resUser);
					if (bestFriendArr.length == friends.length) {
						// console.log(bestFriendArr);
						makeQuizImg(bestFriendArr);
					}
				});
			}
		}

		var makeQuizImg = function(bestFriendArr){
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});

			$.ajax({
				url : '/user-images',
				method : 'POST',
				dataType : 'json',
				data : {
					'user' : data.user,
					'best_friend' : bestFriendArr
				}
			})
			.done(function(output){
				if (output.response === 'error') {
					alert('Something Went Wrong');
					window.location.reload();
				} else {
					$('meta[property="og:title"]').attr('content', 'I found my Partner in the Paranormal');
					$('meta[property="og:description"]').attr('content', 'Find yours here!');
					$('meta[property="og:image"]').attr('content', output.img_path);

					history.replaceState(null, null, '/share/' + data.user.id);

					replaceViews(output, data.user.id);
				}
			})
			.fail(function(){
				// AJAX Error
				alert('Something Went Wrong');
			});
		}

		var replaceViews = function(output, user_id){

			console.log(output);

			$('h1 .top').text(output.best_friend[0].name).fitText(1);
			$('h1 .bottom').text('is your partner in the paranormal').fitText(1.8);

			// Add Images
			$('.left-image img').attr('src', output.user.img);
			$('.right-image img').attr('src', output.best_friend[0].img);

			// Add Button
			$('button#loginbutton')
				.after('<button id="sharequizbutton" class="btn btn-primary" data-img="'+output.img_path+'">SHARE</button><div class="try-again">Not the right partner? <a href="/">try again!</a>')
				.remove();
		}

		return{
			start:start
		}
	})();

})(jQuery);