<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\UserRating;

class ArticleSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 20; $i++) {
			$data = [
				'slug' => 'Article title',
				'title' => 'Article title',
				'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent luctus elit ut commodo euismod. Curabitur sed vestibulum turpis, in ultricies libero. Suspendisse eget mauris porta, ultricies nulla vel, aliquet lectus. Phasellus eu vestibulum turpis. Aenean ante nisl, fermentum eget odio vel, volutpat viverra ligula. Nam varius vel tellus at efficitur. Nunc tincidunt mattis nisl ac sodales. Donec a euismod tellus, ut pellentesque quam.

Morbi ac nunc ex. Nunc quam felis, lobortis sit amet efficitur nec, tristique quis sapien. Sed non mauris at lacus ultricies sagittis. Vivamus eu ligula auctor, ullamcorper nisl eu, gravida lectus. Nulla erat neque, bibendum vel velit ut, dictum feugiat ipsum. Donec consequat faucibus felis, eget congue ipsum facilisis vitae. Sed ullamcorper enim ac molestie vulputate. Nam id sodales enim. Nullam nec arcu sapien.

Cras at tincidunt metus. Duis consectetur enim ut justo sagittis iaculis. Ut ex ligula, venenatis aliquet risus a, molestie ornare nulla. Duis pretium et felis non sollicitudin. In id quam ut dolor ullamcorper pulvinar. Vivamus nec tincidunt neque. Praesent pharetra lectus a massa auctor, sit amet ultrices enim tristique. Praesent dapibus porta mauris, non pharetra leo semper id. Vivamus tincidunt dolor vel nibh sagittis facilisis. Integer et mattis dui. Pellentesque ultrices lorem eros. Etiam ante sem, convallis nec faucibus a, lobortis id dui. Donec eros dui, ullamcorper ut elit vitae, blandit iaculis erat.

Morbi eu urna ac risus consectetur tincidunt in non mi. Nullam malesuada ultrices velit, ut maximus enim. Fusce ultricies vehicula porttitor. Praesent vel eros semper ipsum tristique fermentum non ut mi. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer blandit tortor est. Fusce in quam nec orci ornare blandit quis eu ante. Mauris nulla erat, hendrerit vitae rhoncus nec, placerat nec purus.

Sed ut lacus tortor. Etiam eu tristique dui, at blandit justo. Sed et nibh id ante condimentum euismod in vitae orci. Aliquam erat volutpat. Phasellus elementum gravida metus quis hendrerit. Ut ac blandit est, suscipit venenatis nibh. Vivamus ut tortor ex. Donec vestibulum at tortor id dapibus.',
			];

			\App\Article::addArticle($data);
		}

	}




}