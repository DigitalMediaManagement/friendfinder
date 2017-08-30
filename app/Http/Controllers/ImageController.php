<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Image;

class ImageController extends Controller
{
	public function store(Request $request)
	{
		$user = $request->input('user');
		$best_friend = $request->input('best_friend');

		try {
			// Create inital canvas
			$canvas = Image::canvas(1200, 630, '#ff5555');

			// Insert user images
			$image1 = Image::make($user['img'])->resize(288, 288);
			$canvas->insert($image1, 'top-left', 267, 217);
			$image2 = Image::make($best_friend[0]['img'])->resize(288, 288);
			$canvas->insert($image2, 'top-left', 641, 217);

			// Insert main 'Ghosted' image
			$imgTemplate = Image::make( public_path('images/share-image.png') )->resize(1200, 630);
			$canvas->insert($imgTemplate, 'top-left', 0, 0);

			// Insert name and text (with border)
			for( $x = -1; $x <= 1; $x++ ) {
				for( $y = -1; $y <= 1; $y++ ) {
					$canvas->text(strtoupper($best_friend[0]['name']), 600 + $x, 25 + $y, function($font){
						$font->file( public_path('fonts/Eurostile-Bold-Condensed.ttf') );
						$font->size(80);
						$font->color('#67bdff');
						$font->align('center');
						$font->valign('top');
					});
					$canvas->text('IS YOUR PARTNER IN THE PARANORMAL', 600 + $x, 102 + $y, function($font){
						$font->file( public_path('fonts/Eurostile-Bold-Condensed.ttf') );
						$font->size(44);
						$font->color('#67bdff');
						$font->align('center');
						$font->valign('top');
					});
				}
			}
			$canvas->text(strtoupper($best_friend[0]['name']), 600, 25, function($font){
				$font->file( public_path('fonts/Eurostile-Bold-Condensed.ttf') );
				$font->size(80);
				$font->color('#FFFFFF');
				$font->align('center');
				$font->valign('top');
			});
			$canvas->text('IS YOUR PARTNER IN THE PARANORMAL', 600, 102, function($font){
				$font->file( public_path('fonts/Eurostile-Bold-Condensed.ttf') );
				$font->size(44);
				$font->color('#FFFFFF');
				$font->align('center');
				$font->valign('top');
			});

			$canvas->encode('png', 50);

			Storage::disk('public')->put( 'userimages/' . $user['id'] . '.png', $canvas, 'public' );

			return json_encode([
				'response' => 'success',
				'img_path' => asset('storage/userimages/'.$user['id'].'.png'),
				'user' => $request->input('user'),
				'best_friend' => $request->input('best_friend')
			]);
		} catch (Exception $e) {
			return json_encode([
				'response' => 'error',
				'exception' => $e
			]);
		}

		// return $request->all();
	}
}
